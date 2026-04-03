<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeatureRequest;
use App\DTO\FeatureDTO;
use App\Models\Feature;
use App\Services\FeatureService;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    protected $service;

    public function __construct(FeatureService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(
                $this->service->getAll()
            );
        }

        return view('admin.features.index');
    }

    public function store(FeatureRequest $request)
    {
        $dto = FeatureDTO::fromRequest($request);

        $this->service->store($dto);

        return response()->json(['message'=>'Created']);
    }

    public function update(FeatureRequest $request, Feature $feature)
    {
        $dto = FeatureDTO::fromRequest($request);

        $this->service->update($feature, $dto);

        return response()->json(['message'=>'Updated']);
    }

    public function destroy(Feature $feature)
    {
        $this->service->delete($feature);

        return response()->json(['message'=>'Deleted']);
    }
}