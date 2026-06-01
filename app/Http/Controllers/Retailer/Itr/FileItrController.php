<?php

namespace App\Http\Controllers\Retailer\Itr;

use App\DTO\FileItrDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileItrPreviewRequest;
use App\Services\ItrFileService;
use Illuminate\Http\JsonResponse;

class FileItrController extends Controller
{
    public function __construct(
        protected ItrFileService $itrFileService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view(
            'retailer.itr.file'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW
    |--------------------------------------------------------------------------
    */

    public function preview(
        FileItrPreviewRequest $request
    ): JsonResponse {

        $dto =
            FileItrDTO::fromRequest(
                $request
            );

        $this->itrFileService
            ->preview($dto);

        return response()->json([

            'status' => true,

            'redirect_url' => route(
                'itr.preview-page'
            )

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW PAGE
    |--------------------------------------------------------------------------
    */

    public function previewPage()
    {
        $preview =
            prepare_itr_preview(
                get_itr_session()
            );

        if (!$preview) {

            return redirect()
                ->route('itr.index');
        }

        return view(
            'retailer.itr.preview',
            compact('preview')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FINAL SUBMIT
    |--------------------------------------------------------------------------
    */

    public function finalSubmit(): JsonResponse
    {
        try {

            $application =

                $this->itrFileService
                    ->storeFromSession();

            return response()->json([

                'status' => true,

                'message' =>
                    'ITR filed successfully.',

                'redirect_url' => route(

                    'itr.acknowledgement',

                    $application->id

                )

            ]);

        } catch (\Throwable $e) {

            return response()->json([

                'status' => false,

                'message' =>
                    $e->getMessage()

            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ACKNOWLEDGEMENT
    |--------------------------------------------------------------------------
    */

    public function acknowledgement(
        int $id
    ) {

        $application =

            $this->itrFileService
                ->find(
                    $id,
                    auth()->id()
                );

        return view(

            'retailer.itr.acknowledgement',

            compact('application')

        );
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        if (request()->ajax()) {

            $records =

                $this->itrFileService
                    ->history(
                        auth()->id()
                    );

            return response()->json([

                'status' => true,

                'data' => $records->items(),

                'pagination' => [

                    'current_page' =>
                        $records->currentPage(),

                    'last_page' =>
                        $records->lastPage(),

                    'per_page' =>
                        $records->perPage(),

                    'total' =>
                        $records->total(),

                ]

            ]);
        }

        return view(
            'retailer.itr.history'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        int $id
    ): JsonResponse {

        $application =

            $this->itrFileService
                ->find(
                    $id,
                    auth()->id()
                );

        return response()->json([

            'status' => true,

            'data' => $application

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        int $id
    ): JsonResponse {

        $this->itrFileService
            ->delete(
                $id,
                auth()->id()
            );

        return response()->json([

            'status' => true,

            'message' =>
                'ITR record deleted successfully.'

        ]);
    }
}