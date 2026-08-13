<?php

namespace App\Http\Controllers\Retailer\Itr;

use App\DTO\FileItrDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileItrPreviewRequest;
use App\Services\ItrFileService;
use App\Services\ServiceGuidelineService;
use Illuminate\Http\JsonResponse;
use App\Models\Charge;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class FileItrController extends Controller
{
    public function __construct(
        protected ItrFileService $itrFileService,
        protected ServiceGuidelineService $serviceGuidelineService
    ) {}


    private function getItrCharge(): float
    {
        return (float) Charge::query()

            ->where(
                'code',
                'file_itr'
            )

            ->where(
                'is_active',
                1
            )

            ->value(
                'value'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $session = get_itr_session();

        $data = [];
        $files = [];

        if ($session) {

            $data = $session['data'] ?? [];

            $files = $session['files'] ?? [];
        }

        return view(
            'retailer.itr.file',
            [
                'data'      => $data,
                'files'     => $files,
                'itrCharge' => $this->getItrCharge(),

                  'guideline' =>

                    $this->serviceGuidelineService
                        ->getActiveGuideline('file-itr'),

            ]
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

    try {

        $user = auth()->user();

        $itrCharge = $this->getItrCharge();

        if ($itrCharge <= 0) {

            return response()->json([

                'status'  => false,

                'message' => 'ITR charge is not configured.'

            ], 422);
        }

        if ($user->wallet_balance < $itrCharge) {

            return response()->json([

                'status'  => false,

                'message' => 'Insufficient wallet balance.'

            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE DTO & SAVE PREVIEW
        |--------------------------------------------------------------------------
        */

        $dto = FileItrDTO::fromRequest(
            $request
        );

        $this->itrFileService
            ->preview($dto);

        /*
        |--------------------------------------------------------------------------
        | GET SAVED SESSION
        |--------------------------------------------------------------------------
        */

        $preview = get_itr_session();

        if (!$preview) {

            return response()->json([

                'status'  => false,

                'message' => 'Unable to create preview session.'

            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE CHARGE
        |--------------------------------------------------------------------------
        */

        $preview['charge'] = $itrCharge;

        /*
        |--------------------------------------------------------------------------
        | MARK SESSION AS RETURNABLE
        |--------------------------------------------------------------------------
        */

        $preview['returnable'] = true;

        /*
        |--------------------------------------------------------------------------
        | SAVE LAST FORM VALUES
        |--------------------------------------------------------------------------
        */

        $preview['data'] = array_merge(

            $preview['data'] ?? [],

            [

                'name'    => $request->name,
                'mobile'  => $request->mobile,
                'email'   => $request->email,
                'remarks' => $request->remarks,

            ]

        );

        save_itr_session(
            $preview
        );

        return response()->json([

            'status' => true,

            'message' => 'Preview generated successfully.',

            'redirect_url' => route(
                'retailer.itr.preview-page'
            )

        ]);

    } catch (\Throwable $e) {

        return response()->json([

            'status' => false,

            'message' => config('app.debug')
                ? $e->getMessage()
                : 'Something went wrong.'

        ], 500);
    }
}
    /*
    |--------------------------------------------------------------------------
    | PREVIEW PAGE
    |--------------------------------------------------------------------------
    */

    public function previewPage()
    {
        $session = get_itr_session();

        if (!$session) {

            return redirect()
                ->route('retailer.itr.index')
                ->with(
                    'error',
                    'Preview session expired.'
                );
        }

        $preview = prepare_itr_preview(
            $session
        );

        if (!$preview) {

            return redirect()
                ->route('retailer.itr.index')
                ->with(
                    'error',
                    'Preview session expired.'
                );
        }

        return view(
            'retailer.itr.preview',
            [
                'data'      => $preview['data'],
                'files'     => $preview['files'],
                'itrCharge' => $session['charge'] ?? $this->getItrCharge()
            ]
        );
    }
    
    /*
    |--------------------------------------------------------------------------
    | FINAL SUBMIT
    |--------------------------------------------------------------------------
    */

    public function finalSubmit(): JsonResponse
    {
        DB::beginTransaction();

        try {

            $user = User::query()
                ->lockForUpdate()
                ->find(auth()->id());

            $admin = User::query()
                ->role('Admin')
                ->lockForUpdate()
                ->first();

            if (!$admin) {

                DB::rollBack();

                return response()->json([
                    'status'  => false,
                    'message' => 'Admin account not found.'
                ], 500);
            }

            $itrCharge = $this->getItrCharge();

            if ($itrCharge <= 0) {

                DB::rollBack();

                return response()->json([
                    'status'  => false,
                    'message' => 'ITR charge is not configured.'
                ], 422);
            }

            if ($user->wallet_balance < $itrCharge) {

                DB::rollBack();

                return response()->json([
                    'status'  => false,
                    'message' => 'Insufficient wallet balance.'
                ], 422);
            }

            $retailerBefore = $user->wallet_balance;
            $adminBefore    = $admin->wallet_balance;

            /*
            |--------------------------------------------------------------------------
            | STORE APPLICATION
            |--------------------------------------------------------------------------
            */

            $application = $this->itrFileService
                ->storeFromSession();

            /*
            |--------------------------------------------------------------------------
            | WALLET UPDATE
            |--------------------------------------------------------------------------
            */

            $user->decrement(
                'wallet_balance',
                $itrCharge
            );

            $admin->increment(
                'wallet_balance',
                $itrCharge
            );

            $user->refresh();
            $admin->refresh();

            /*
            |--------------------------------------------------------------------------
            | UPDATE APPLICATION
            |--------------------------------------------------------------------------
            */

            $application->update([

                'amount'              => $itrCharge,

                'payment_status'      => 'Paid',

                'wallet_deducted'     => true,

                'wallet_deducted_at'  => now(),

                'status'              => 'Processing'

            ]);

            /*
            |--------------------------------------------------------------------------
            | RETAILER TRANSACTION
            |--------------------------------------------------------------------------
            */

            WalletTransaction::create([

                'user_id'         => $user->id,

                'amount'          => $itrCharge,

                'before_balance'  => $retailerBefore,

                'after_balance'   => $user->wallet_balance,

                'type'            => 'debit',

                'status'          => 'success',

                'transaction_no'  =>
                    'TXN'
                    . now()->format('YmdHis')
                    . rand(1000,9999),

                'remark'          =>
                    'ITR Filing Charge'

            ]);

            /*
            |--------------------------------------------------------------------------
            | ADMIN TRANSACTION
            |--------------------------------------------------------------------------
            */

            WalletTransaction::create([

                'user_id'         => $admin->id,

                'amount'          => $itrCharge,

                'before_balance'  => $adminBefore,

                'after_balance'   => $admin->wallet_balance,

                'type'            => 'credit',

                'status'          => 'success',

                'transaction_no'  =>
                    'ADM'
                    . now()->format('YmdHis')
                    . rand(1000,9999),

                'remark'          =>
                    'ITR Filing Amount Received'

            ]);

            DB::commit();

            return response()->json([

                'status' => true,

                'message' => 'ITR filed successfully.',

                'redirect_url' => route(
                    'retailer.itr.history'
                   
                )

            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([

                'status' => false,

                'message' => $e->getMessage()

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

        $records = $this->itrFileService
            ->history(auth()->id());

        $records->getCollection()->transform(function ($item) {

            $document = $item->documents->first();

            $item->service_document = (
                $document &&
                file_exists_custom($document->file_path)
            )
                ? file_url($document->file_path)
                : null;

            return $item;
        });

        return response()->json([
            'status' => true,
            'data' => $records->items(),
            'pagination' => [
                'current_page' => $records->currentPage(),
                'last_page'    => $records->lastPage(),
                'per_page'     => $records->perPage(),
                'total'        => $records->total(),
            ]
        ]);
    }

    return view('retailer.itr.history');
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