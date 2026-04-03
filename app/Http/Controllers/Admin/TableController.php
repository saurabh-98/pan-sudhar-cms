<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TableService;
use Illuminate\Http\Request;

class TableController extends Controller
{
    protected $service;

    public function __construct(TableService $service)
    {
        $this->service = $service;
    }

    /* =========================
       INDEX
    ========================= */
    public function index()
    {
        return view('admin.tables.index');
    }

    /* =========================
       LIST (DATATABLE)
    ========================= */
    public function list()
    {
        return response()->json([
            'data' => $this->service->list()->map(function ($table) {

                return [
                    'id'       => $table->id,
                    'name'     => $table->name,
                    'capacity' => $table->capacity,
                    'is_active'=> $table->is_active
                ];
            })
        ]);
    }

    /* =========================
       STORE
    ========================= */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:50',
            'capacity' => 'required|integer|min:1|max:20',
        ]);

        $this->service->create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Table created successfully'
        ]);
    }

    /* =========================
       UPDATE
    ========================= */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:50',
            'capacity' => 'required|integer|min:1|max:20',
        ]);

        $this->service->update($id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Table updated successfully'
        ]);
    }

    /* =========================
       DELETE
    ========================= */
    public function delete($id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Table deleted successfully'
        ]);
    }

    /* =========================
       TOGGLE STATUS
    ========================= */
    public function toggle($id)
    {
        $this->service->toggle($id);

        return response()->json([
            'success' => true,
            'message' => 'Status updated'
        ]);
    }
}