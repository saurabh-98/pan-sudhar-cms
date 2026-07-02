<?php

namespace App\Http\Controllers\Admin;

use App\DTO\ServiceGuidelineDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceGuidelineRequest;
use App\Models\Module;
use App\Services\ServiceGuidelineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ServiceGuidelineController extends Controller
{
    public function __construct(
        protected ServiceGuidelineService $service
    ) {}

    /**
     * ==========================================================
     * INDEX
     * ==========================================================
     */
    public function index()
    {
        // Only leaf-level, non-history modules qualify as a
        // "service" that can have a guideline attached to it.
        // Parent/category modules (e.g. "PAN Services") and
        // history/listing routes are excluded.
        $services = Module::with('parent')
            ->whereNotNull('parent_id')
            ->where('status', 1)
            ->where('slug', 'not like', '%history%')
            ->orderBy('sort_order')
            ->get()
            ->groupBy(function ($module) {
                return $module->parent->name ?? 'Other';
            });

        return view(
            'admin.service-guidelines.index',
            compact('services')
        );
    }

    /**
     * ==========================================================
     * DATATABLE LIST
     * ==========================================================
     */
    public function list(Request $request): JsonResponse
    {
        if (!$request->ajax()) {
            abort(404);
        }

        return DataTables::of(
            $this->service->getList()
        )

            ->addIndexColumn()

            ->editColumn('service_code', function ($row) {
                return ucwords(str_replace('_', ' ', $row->service_code));
            })

            ->editColumn('pdf', function ($row) {

                if (!$row->pdf) {
                    return '-';
                }

                return '<a href="' . file_url($row->pdf) . '"
                            target="_blank"
                            class="btn btn-sm btn-primary">
                            View PDF
                        </a>';
            })

            ->editColumn('is_active', function ($row) {

                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })

            ->addColumn('action', function ($row) {

                return '

                    <button
                        class="btn btn-warning btn-sm editBtn"
                        data-id="' . $row->id . '">

                        <i class="fa fa-edit"></i>

                    </button>

                    <button
                        class="btn btn-danger btn-sm deleteBtn"
                        data-id="' . $row->id . '">

                        <i class="fa fa-trash"></i>

                    </button>

                ';
            })

            ->rawColumns([
                'pdf',
                'is_active',
                'action'
            ])

            ->make(true);
    }

    /**
     * ==========================================================
     * STORE
     * ==========================================================
     */
    public function store(ServiceGuidelineRequest $request)
    {
        try {

            $this->service->store(
                ServiceGuidelineDTO::fromRequest($request)
            );

            return response()->json([

                'status' => true,

                'message' => 'Service guideline created successfully.'

            ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([

                'status' => false,

                'message' => 'Something went wrong.'

            ], 500);
        }
    }

    /**
     * ==========================================================
     * EDIT
     * ==========================================================
     */
    public function edit(int $id)
    {
        try {

            return response()->json([

                'status' => true,

                'data' => $this->service->find($id)

            ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([

                'status' => false,

                'message' => 'Record not found.'

            ], 404);
        }
    }

    /**
     * ==========================================================
     * UPDATE
     * ==========================================================
     */
    public function update(
        ServiceGuidelineRequest $request,
        int $id
    ) {
        try {

            $this->service->update(

                $id,

                ServiceGuidelineDTO::fromRequest($request)

            );

            return response()->json([

                'status' => true,

                'message' => 'Service guideline updated successfully.'

            ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([

                'status' => false,

                'message' => 'Something went wrong.'

            ], 500);
        }
    }

    /**
     * ==========================================================
     * DELETE
     * ==========================================================
     */
    public function destroy(int $id)
    {
        try {

            $this->service->delete($id);

            return response()->json([

                'status' => true,

                'message' => 'Service guideline deleted successfully.'

            ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([

                'status' => false,

                'message' => 'Unable to delete record.'

            ], 500);
        }
    }
}