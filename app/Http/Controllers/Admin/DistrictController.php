<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDistrictRequest;
use App\Services\DistrictService;
use App\DTO\DistrictDTO;

class DistrictController extends Controller
{
    protected $service;

    public function __construct(DistrictService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $districts = $this->service->getAll();
        return view('admin.districts.index', compact('districts'));
    }

    public function list()
    {
        $districts = $this->service->getAll();

        return response()->json([
            'data' => $districts->map(function($d){
                return [
                    'id' => $d->id,
                    'name' => $d->name,
                    'status' => $d->status,
                    'state_id' => $d->state_id,
                    'state_name' => $d->state->name ?? ''
                ];
            })
        ]);
    }
    public function store(StoreDistrictRequest $request)
    {
        $dto = DistrictDTO::fromRequest($request);

        $this->service->store($dto);

        return back()->with('success', 'District added successfully');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return back()->with('success', 'District deleted');
    }

    /* 🔥 AJAX */
    public function getByState($stateId)
    {
        return response()->json(
            $this->service->getByState($stateId)
        );
    }
}