<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\DTO\BannerDTO;
use App\Services\BannerService;

class BannerController extends Controller
{
    protected $service;

    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    /* ================= LIST ================= */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(
                $this->service->getAll()
            );
        }

        return view('admin.banner.index');
    }

    /* ================= STORE ================= */
    public function store(BannerRequest $request)
    {
        $dto = BannerDTO::fromRequest($request);

        // ✅ Always array (important for multiple images)
        $files = $request->file('image') ?? [];

        if ($files && !is_array($files)) {
            $files = [$files];
        }

        $this->service->store($dto, $files);

        return response()->json([
            'status' => true,
            'message' => 'Banner created successfully'
        ]);
    }

    /* ================= UPDATE ================= */
    public function update(BannerRequest $request, $id)
    {
        $dto = BannerDTO::fromRequest($request);

        // ✅ Always array
        $files = $request->file('image') ?? [];

        if ($files && !is_array($files)) {
            $files = [$files];
        }

        $updated = $this->service->update($id, $dto, $files);

        if (!$updated) {
            return response()->json([
                'status' => false,
                'message' => 'Banner not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Banner updated successfully'
        ]);
    }

    /* ================= DELETE ================= */
    public function destroy($id)
    {
        $deleted = $this->service->deleteById($id);

        if (!$deleted) {
            return response()->json([
                'status' => false,
                'message' => 'Banner not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Banner deleted successfully'
        ]);
    }
}