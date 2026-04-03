<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CampaignRequest;
use App\DTO\CampaignDTO;
use App\Models\Campaign;
use App\Services\CampaignService;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    protected $service;

    public function __construct(CampaignService $service)
    {
        $this->service = $service;
    }

    /* ================= INDEX ================= */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(
                Campaign::latest()->get()
            );
        }

        return view('admin.campaigns.index');
    }

    /* ================= STORE ================= */
    public function store(CampaignRequest $request)
    {
        $dto = CampaignDTO::fromRequest($request);

        $file = $request->file('image');

        $this->service->store($dto, $file);

        return response()->json([
            'status' => true,
            'message' => 'Campaign created successfully'
        ]);
    }

    /* ================= UPDATE ================= */
    public function update(CampaignRequest $request, Campaign $campaign)
    {
        $dto = CampaignDTO::fromRequest($request);

        $file = $request->file('image');

        $this->service->update($campaign, $dto, $file);

        return response()->json([
            'status' => true,
            'message' => 'Campaign updated successfully'
        ]);
    }

    /* ================= DELETE ================= */
    public function destroy(Campaign $campaign)
    {
        $this->service->delete($campaign);

        return response()->json([
            'status' => true,
            'message' => 'Campaign deleted successfully'
        ]);
    }
}