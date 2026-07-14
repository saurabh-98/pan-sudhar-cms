<?php

namespace App\Http\Controllers\Retailer\itr;

use App\DTO\FileTdsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\TdsPreviewRequest;
use App\Services\TdsFileService;
use App\Services\ServiceGuidelineService;
use Illuminate\Http\JsonResponse;
use App\Models\Charge;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class FileTdsController extends Controller
{
    public function __construct(
        protected TdsFileService $tdsFileService,
        protected ServiceGuidelineService $serviceGuidelineService
    ) {}


    private function getTdsCharge(): float
    {
        return (float) Charge::query()

            ->where(
                'code',
                'itr_filing_tds_refund'
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
        $session = get_tds_session();

        $data = [];
        $files = [];

        if ($session) {

            $data = $session['data'] ?? [];

            $files = $session['files'] ?? [];
        }

        return view(
            'retailer.tds.file',
            [
                'data'      => $data,
                'files'     => $files,
                'tdsCharge' => $this->getTdsCharge(),

                  'guideline' =>

                    $this->serviceGuidelineService
                        ->getActiveGuideline('file-tds'),

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW
    |--------------------------------------------------------------------------
    */

    public function preview(
    TdsPreviewRequest $request
): JsonResponse {

    try {

        $user = auth()->user();

        $tdsCharge = $this->getTdsCharge();

        if ($tdsCharge <= 0) {

            return response()->json([

                'status'  => false,

                'message' => 'TDS charge is not configured.'

            ], 422);
        }

        if ($user->wallet_balance < $tdsCharge) {

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

        $dto = FileTdsDTO::fromRequest(
            $request
        );

        $this->tdsFileService
            ->preview($dto);

        /*
        |--------------------------------------------------------------------------
        | GET SAVED SESSION
        |--------------------------------------------------------------------------
        */

        $preview = get_tds_session();

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

        $preview['charge'] = $tdsCharge;

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

        save_tds_session(
            $preview
        );

        return response()->json([

            'status' => true,

            'message' => 'Preview generated successfully.',

            'redirect_url' => route(
                'retailer.tds.preview-page'
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
        $session = get_tds_session();

        if (!$session) {

            return redirect()
                ->route('retailer.tds.index')
                ->with(
                    'error',
                    'Preview session expired.'
                );
        }

        $preview = prepare_tds_preview(
            $session
        );

        if (!$preview) {

            return redirect()
                ->route('retailer.tds.index')
                ->with(
                    'error',
                    'Preview session expired.'
                );
        }

        return view(
            'retailer.tds.preview',
            [
                'data'      => $preview['data'],
                'files'     => $preview['files'],
                'tdsCharge' => $session['charge'] ?? $this->getTdsCharge()
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

            $tdsCharge = $this->getTdsCharge();

            if ($tdsCharge <= 0) {

                DB::rollBack();

                return response()->json([
                    'status'  => false,
                    'message' => 'TDS charge is not configured.'
                ], 422);
            }

            if ($user->wallet_balance < $tdsCharge) {

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

            $application = $this->tdsFileService
                ->storeFromSession();

            /*
            |--------------------------------------------------------------------------
            | WALLET UPDATE
            |--------------------------------------------------------------------------
            */

            $user->decrement(
                'wallet_balance',
                $tdsCharge
            );

            $admin->increment(
                'wallet_balance',
                $tdsCharge
            );

            $user->refresh();
            $admin->refresh();

            /*
            |--------------------------------------------------------------------------
            | UPDATE APPLICATION
            |--------------------------------------------------------------------------
            */

            $application->update([

                'amount'              => $tdsCharge,

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

                'amount'          => $tdsCharge,

                'before_balance'  => $retailerBefore,

                'after_balance'   => $user->wallet_balance,

                'type'            => 'debit',

                'status'          => 'success',

                'transaction_no'  =>
                    'TXN'
                    . now()->format('YmdHis')
                    . rand(1000,9999),

                'remark'          =>
                    'TDS Filing Charge'

            ]);

            /*
            |--------------------------------------------------------------------------
            | ADMIN TRANSACTION
            |--------------------------------------------------------------------------
            */

            WalletTransaction::create([

                'user_id'         => $admin->id,

                'amount'          => $tdsCharge,

                'before_balance'  => $adminBefore,

                'after_balance'   => $admin->wallet_balance,

                'type'            => 'credit',

                'status'          => 'success',

                'transaction_no'  =>
                    'ADM'
                    . now()->format('YmdHis')
                    . rand(1000,9999),

                'remark'          =>
                    'TDS Filing Amount Received'

            ]);

            DB::commit();

            return response()->json([

                'status' => true,

                'message' => 'TDS filed successfully.',

                'redirect_url' => route(
                    'retailer.tds.history'

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

            $this->tdsFileService
                ->find(
                    $id,
                    auth()->id()
                );

        return view(

            'retailer.tds.acknowledgement',

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

        $records = $this->tdsFileService
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

    return view('retailer.tds.history');
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

            $this->tdsFileService
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

        $this->tdsFileService
            ->delete(
                $id,
                auth()->id()
            );

        return response()->json([

            'status' => true,

            'message' =>
                'TDS record deleted successfully.'

        ]);
    }
}