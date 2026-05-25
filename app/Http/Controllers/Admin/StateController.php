<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStateRequest;
use App\Services\StateService;
use App\DTO\StateDTO;
use Illuminate\Http\Request;

class StateController extends Controller
{
    protected $service;

    public function __construct(StateService $service)
    {
        $this->service = $service;
    }

    /* ================= LIST ================= */
    public function index()
    {
        $states = $this->service->getAll();

        return view('admin.states.index', compact('states'));
    }

    public function list(Request $request)
    {
        $states = $this->service->getAll();

        return response()->json([
            'data' => $states
        ]);
    }

    /* ================= STORE ================= */
    public function store(StoreStateRequest $request)
    {
        $dto = StateDTO::fromRequest($request);

        $state = $this->service->store($dto);

        return $this->response($request, $state, 'State added successfully');
    }

    /* ================= SHOW ================= */
    public function show($id)
    {
        $state = $this->service->find($id);

        return response()->json([
            'success' => true,
            'data' => $state
        ]);
    }

    /* ================= UPDATE ================= */
    public function update(StoreStateRequest $request, $id)
    {
        $dto = StateDTO::fromRequest($request);

        $state = $this->service->update($id, $dto);

        return $this->response($request, $state, 'State updated successfully');
    }

    /* ================= DELETE ================= */
    public function destroy(Request $request, $id)
    {
        $this->service->delete($id);

        return $this->response($request, null, 'State deleted successfully');
    }

    /* ================= COMMON RESPONSE ================= */
    private function response($request, $data, $message)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ]);
        }

        return back()->with('success', $message);
    }
}