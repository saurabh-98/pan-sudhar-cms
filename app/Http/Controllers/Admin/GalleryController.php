<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GalleryService;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\DTO\GalleryDTO;

class GalleryController extends Controller
{
    protected $service;

    public function __construct(GalleryService $service)
    {
        $this->service = $service;
    }

    /* ================= LIST ================= */
    public function index()
    {
        if (request()->ajax()) {
            return response()->json($this->service->getAll());
        }

        return view('admin.gallery.index');
    }

    /* ================= STORE ================= */
    public function store(StoreGalleryRequest $request)
    {
        $dto = GalleryDTO::fromRequest($request);

        $this->service->store($dto);

        // AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Gallery added successfully'
            ]);
        }

        // NORMAL RESPONSE
        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Gallery added successfully');
    }

    /* ================= UPDATE ================= */
    public function update(UpdateGalleryRequest $request, $id)
    {
        $dto = GalleryDTO::fromRequest($request);

        $this->service->update($dto, $id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Gallery updated successfully'
            ]);
        }

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Gallery updated successfully');
    }

    /* ================= DELETE ================= */
    public function delete($id)
    {
        $this->service->delete($id);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully'
            ]);
        }

        return back()->with('success', 'Deleted successfully');
    }
}