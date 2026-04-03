<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Services\PageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    protected $service;

    public function __construct(PageService $service)
    {
        $this->service = $service;
    }

    /* =========================
       INDEX
    ========================= */
    public function index()
    {
        return view('admin.pages.index');
    }

    /* =========================
       LIST (DATATABLE READY)
    ========================= */
    public function list()
    {
        return response()->json([
            'data' => $this->service->list()
        ]);
    }

    /* =========================
       STORE
    ========================= */
    public function store(PageRequest $request)
    {
        try {

            $this->service->store($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Page created successfully'
            ]);

        } catch (\Throwable $e) {

            Log::error('Page Store Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    /* =========================
       UPDATE
    ========================= */
    public function update(PageRequest $request, $id)
    {
        try {

            $this->service->update($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Page updated successfully'
            ]);

        } catch (\Throwable $e) {

            Log::error('Page Update Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Update failed'
            ], 500);
        }
    }

    /* =========================
       DELETE
    ========================= */
    public function destroy($id)
    {
        try {

            $this->service->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Page deleted successfully'
            ]);

        } catch (\Throwable $e) {

            Log::error('Page Delete Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Delete failed'
            ], 500);
        }
    }
}