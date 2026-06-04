<?php

namespace App\Http\Controllers\Retailer\Pan;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\DTO\PanCorrectionDTO;

use App\Http\Requests\PanCorrectionPreviewRequest;

use App\Models\District;
use App\Models\PanCorrectionApplication;
use App\Models\State;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\Charge;

use App\Services\DistrictService;
use App\Services\PanCorrectionService;
use App\Services\StateService;

use Yajra\DataTables\Facades\DataTables;

class PanCorrectionController extends Controller
{
   

    public function __construct(

        protected PanCorrectionService $panCorrectionService,

        protected StateService $stateService,

        protected DistrictService $districtService

    ) {}

    
    private function getPanCorrectionCharge(): float
    {
        return (float) Charge::query()

            ->where(
                'code',
                'pan_correction'
            )

            ->where(
                'is_active',
                1
            )

            ->value(
                'value'
            );
    }

    public function index()
    {
        if (request()->ajax()) {

            $applications = PanCorrectionApplication::query()

                ->with([

                    'user.retailer',

                    'stateData',

                    'districtData',

                    'documents'

                ])

                ->where(
                    'user_id',
                    auth()->id()
                )

                ->latest();

            return DataTables::of($applications)

                ->addIndexColumn()

                ->addColumn('shop_name', function ($row) {

                    return '

                        <div class="fw-semibold text-primary">

                            '

                            . (

                                $row->user?->retailer?->shop_name

                                ?? 'N/A'

                            )

                            . '

                        </div>

                    ';
                })

                ->addColumn('applicant_name', function ($row) {

                    return '

                        <div>

                            <div class="fw-semibold text-dark">

                                '

                                . e($row->applicant_name)

                                . '

                            </div>

                            <small class="text-muted">

                                '

                                . e($row->mobile_no)

                                . '

                            </small>

                        </div>

                    ';
                })

                ->addColumn('state_name', function ($row) {

                    return '

                        <span class="badge bg-light text-dark border">

                            '

                            . (

                                $row->stateData?->name

                                ?? 'N/A'

                            )

                            . '

                        </span>

                    ';
                })

                ->addColumn('district_name', function ($row) {

                    return '

                        <span class="badge bg-light text-dark border">

                            '

                            . (

                                $row->districtData?->name

                                ?? 'N/A'

                            )

                            . '

                        </span>

                    ';
                })

                ->addColumn('payment', function ($row) {

                    return $row->payment_badge;
                })

                ->addColumn('amount', function ($row) {

                    return '

                        <span class="fw-bold text-success">

                            ₹'

                            . number_format(
                                $row->amount,
                                2
                            )

                            . '

                        </span>

                    ';
                })

                ->addColumn('created_at', function ($row) {

                    return '

                        <div>

                            <div class="fw-semibold">

                                '

                                . $row->created_at->format(
                                    'd M Y'
                                )

                                . '

                            </div>

                            <small class="text-muted">

                                '

                                . $row->created_at->format(
                                    'h:i A'
                                )

                                . '

                            </small>

                        </div>

                    ';
                })

                ->addColumn('status', function ($row) {

                    return $row->status_badge;
                })

                ->addColumn('document_status', function ($row) {

                    $document = $row->documents->first();

                    if (

                        $document

                        &&

                        file_exists_custom(
                            $document->file_path
                        )

                        &&

                        in_array(

                            strtolower($row->status),

                            [

                                'approved',

                                'completed'

                            ]

                        )

                    ) {

                        return '

                            <div class="d-flex gap-2 flex-wrap">

                                <a
                                    href="'

                                    . file_url(
                                        $document->file_path
                                    )

                                    . '"

                                    target="_blank"

                                    class="btn btn-sm btn-success"

                                >

                                    <i class="fa fa-eye me-1"></i>

                                    View

                                </a>

                                <a
                                    href="'

                                    . file_url(
                                        $document->file_path
                                    )

                                    . '"

                                    download

                                    class="btn btn-sm btn-primary"

                                >

                                    <i class="fa fa-download me-1"></i>

                                    Download

                                </a>

                            </div>

                        ';
                    }

                    if (

                        $document

                        &&

                        file_exists_custom(
                            $document->file_path
                        )

                    ) {

                        return '

                            <span class="badge bg-info">

                                Uploaded

                            </span>

                        ';
                    }

                    return '

                        <span class="badge bg-warning text-dark">

                            Pending

                        </span>

                    ';
                })

                ->addColumn('action', function ($row) {

                    return '

                        <div class="d-flex gap-2">

                            <a
                                href="'

                                . route(
                                    'retailer.pan-correction.show',
                                    $row->id
                                )

                                . '"

                                class="btn btn-sm btn-primary"

                            >

                                <i class="fa fa-eye"></i>

                            </a>

                        </div>

                    ';
                })

                ->rawColumns([

                    'shop_name',

                    'applicant_name',

                    'old_pan_number',

                    'state_name',

                    'district_name',

                    'payment',

                    'amount',

                    'created_at',

                    'status',

                    'document_status',

                    'action'

                ])

                ->make(true);
        }

        return view(
            'retailer.pan-correction.history'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE FORM
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $preview = get_pan_correction_session();

        return view(

            'retailer.pan-correction.apply',

            [

                'states' =>

                    $this->stateService
                        ->getAll(),


               'walletBalance' =>

                    auth()->user()
                        ->wallet_balance,

                'panCharge' =>

                    $this->getPanCorrectionCharge(),

                'data' =>

                    $preview['data'] ?? [],


                'files' =>

                    $preview['files'] ?? []

            ]

        );
    }

  

    public function preview(
    PanCorrectionPreviewRequest $request
): JsonResponse {

    try {

        $user = auth()->user();

        $panCharge = $this->getPanCorrectionCharge();

        if ($panCharge <= 0) {

            return response()->json([

                'status' => false,

                'message' => 'PAN correction charge is not configured.'

            ], 422);

        }

        if ($user->wallet_balance < $panCharge) {

            return response()->json([

                'status' => false,

                'message' =>
                    'Insufficient wallet balance.'

            ], 422);

        }

        $dto = PanCorrectionDTO::fromRequest(
            $request
        );

        /*
        |--------------------------------------------------------------------------
        | CLOUDINARY UPLOAD + SESSION SAVE FROM SERVICE
        |--------------------------------------------------------------------------
        */

        $preview =

            $this->panCorrectionService
                ->preview($dto);

        /*
        |--------------------------------------------------------------------------
        | ADD STATE NAME
        |--------------------------------------------------------------------------
        */

        $preview['data']['state_name'] =

            State::where(

                'id',

                $request->state

            )->value('name')

            ??

            'N/A';

        /*
        |--------------------------------------------------------------------------
        | ADD DISTRICT NAME
        |--------------------------------------------------------------------------
        */

        $preview['data']['district_name'] =

            District::where(

                'id',

                $request->district

            )->value('name')

            ??

            'N/A';

        /*
        |--------------------------------------------------------------------------
        | STORE CHARGE IN SESSION
        |--------------------------------------------------------------------------
        */

        $preview['data']['pan_charge'] =
            $panCharge;

        /*
        |--------------------------------------------------------------------------
        | UPDATE SESSION
        |--------------------------------------------------------------------------
        */

        save_pan_correction_session(
            $preview
        );

        return response()->json([

            'status' => true,

            'message' =>
                'Preview generated successfully.',

            'redirect_url' =>

                route(
                    'retailer.pan-correction.preview-page'
                )

        ]);

    } catch (\Throwable $e) {

        Log::error(

            'PAN PREVIEW ERROR',

            [

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile()

            ]

        );

        return response()->json([

            'status' => false,

            'message' => $e->getMessage()

        ], 500);

    }
}
   

    public function previewPage()
    {
        $preview = get_pan_correction_session();

        if (

            empty($preview)

            ||

            !isset($preview['data'])

            ||

            !is_array($preview['data'])

        ) {

            return redirect()

                ->route(

                    'retailer.pan-correction.apply'

                )

                ->with(

                    'error',

                    'Preview session expired.'

                );

        }

        return view(

            'retailer.pan-correction.preview',

            [

                'data' =>

                    $preview['data'],

                'files' =>

                    $preview['files']

                    ??

                    [],

                'panCharge' =>

                    $preview['data']['pan_charge']

                    ??

                    $this->getPanCorrectionCharge()

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

            /*
            |--------------------------------------------------------------------------
            | DYNAMIC PAN CORRECTION CHARGE
            |--------------------------------------------------------------------------
            */

            $panCharge = $this->getPanCorrectionCharge();

            if ($panCharge <= 0) {

                DB::rollBack();

                return response()->json([
                    'status'  => false,
                    'message' => 'PAN correction charge is not configured.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | SESSION CHECK
            |--------------------------------------------------------------------------
            */

            $session = get_pan_correction_session();

            if (!$session) {

                DB::rollBack();

                return response()->json([
                    'status'  => false,
                    'message' => 'Preview session expired. Please apply again.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | FILE CHECK
            |--------------------------------------------------------------------------
            */

            foreach ($session['files'] ?? [] as $file) {

                if (!$file) {

                    DB::rollBack();

                    return response()->json([
                        'status'  => false,
                        'message' => 'Uploaded document missing. Please upload again.'
                    ], 422);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | WALLET CHECK
            |--------------------------------------------------------------------------
            */

            if ($user->wallet_balance < $panCharge) {

                DB::rollBack();

                return response()->json([
                    'status'  => false,
                    'message' => 'Insufficient wallet balance.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | STORE APPLICATION
            |--------------------------------------------------------------------------
            */

            $application = $this->panCorrectionService
                ->storeFromSession();

            $retailerBefore = $user->wallet_balance;
            $adminBefore    = $admin->wallet_balance;

            /*
            |--------------------------------------------------------------------------
            | WALLET UPDATE
            |--------------------------------------------------------------------------
            */

            $user->decrement(
                'wallet_balance',
                $panCharge
            );

            $admin->increment(
                'wallet_balance',
                $panCharge
            );

            $user->refresh();
            $admin->refresh();

            /*
            |--------------------------------------------------------------------------
            | UPDATE APPLICATION
            |--------------------------------------------------------------------------
            */

            $application->update([

                'amount'              => $panCharge,

                'wallet_deducted'     => true,

                'wallet_deducted_at'  => now(),

                'payment_status'      => 'Paid',

                'status'              => 'Processing'

            ]);

            /*
            |--------------------------------------------------------------------------
            | RETAILER TRANSACTION
            |--------------------------------------------------------------------------
            */

            WalletTransaction::create([

                'user_id'         => $user->id,

                'amount'          => $panCharge,

                'before_balance'  => $retailerBefore,

                'after_balance'   => $user->wallet_balance,

                'type'            => 'debit',

                'status'          => 'success',

                'transaction_no'  =>
                    'TXN'
                    . now()->format('YmdHis')
                    . rand(1000, 9999),

                'remark'          =>
                    'PAN Correction Application Charge'

            ]);

            /*
            |--------------------------------------------------------------------------
            | ADMIN TRANSACTION
            |--------------------------------------------------------------------------
            */

            WalletTransaction::create([

                'user_id'         => $admin->id,

                'amount'          => $panCharge,

                'before_balance'  => $adminBefore,

                'after_balance'   => $admin->wallet_balance,

                'type'            => 'credit',

                'status'          => 'success',

                'transaction_no'  =>
                    'ADM'
                    . now()->format('YmdHis')
                    . rand(1000, 9999),

                'remark'          =>
                    'PAN Correction Application Received Amount'

            ]);

            DB::commit();

            return response()->json([

                'status'       => true,

                'message'      =>
                    'PAN Correction Application Submitted Successfully.',

                'redirect_url' => route(
                    'retailer.pan-correction.receiving',
                    $application->id
                )

            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'PAN CORRECTION FINAL SUBMIT ERROR',
                [
                    'message' => $e->getMessage(),
                    'line'    => $e->getLine(),
                    'file'    => $e->getFile()
                ]
            );

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(int $id)
    {
        return view(

            'retailer.pan-correction.show',

            [

                'application' =>

                    $this->panCorrectionService->find(
                        $id,
                        auth()->id()
                    )

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(int $id)
    {
        return view(

            'retailer.pan-correction.edit',

            [

                'application' =>

                    $this->panCorrectionService->find(
                        $id,
                        auth()->id()
                    ),

                'states' =>

                    $this->stateService
                        ->getAll()

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdatePanCorrectionRequest $request,
        int $id
    )
    {
        $this->panCorrectionService->update(

            $request,

            $id,

            auth()->id()

        );

        return redirect()

            ->route(
                'retailer.pan-correction.history'
            )

            ->with(

                'success',

                'PAN Correction Application Updated Successfully.'

            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(int $id)
    {
        $this->panCorrectionService->delete(

            $id,

            auth()->id()

        );

        return redirect()

            ->back()

            ->with(

                'success',

                'PAN Correction Application Deleted Successfully.'

            );
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public function status(int $id)
    {
        return view(

            'retailer.pan-correction.status',

            [

                'application' =>

                    $this->panCorrectionService->find(
                        $id,
                        auth()->id()
                    )

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACKNOWLEDGEMENT
    |--------------------------------------------------------------------------
    */

    public function acknowledgement(int $id)
    {


        return view(

            'retailer.pan-correction.acknowledge',

            [

                'application' =>

                    $this->panCorrectionService->find(
                        $id,
                        auth()->id()
                    )

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | PRINT
    |--------------------------------------------------------------------------
    */

    public function print(int $id)
    {
        return view(

            'retailer.pan-correction.print',

            [

                'application' =>

                    $this->panService->find(
                        $id,
                        auth()->id()
                    )

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | STATES
    |--------------------------------------------------------------------------
    */

    public function getStates(): JsonResponse
    {
        return response()->json(

            State::query()

                ->select(
                    'id',
                    'name'
                )

                ->orderBy('name')

                ->get()

        );
    }

    /*
    |--------------------------------------------------------------------------
    | DISTRICTS
    |--------------------------------------------------------------------------
    */

    public function getDistricts(
        int $stateId
    ): JsonResponse {

        return response()->json(

            District::query()

                ->where(
                    'state_id',
                    $stateId
                )

                ->select(
                    'id',
                    'name'
                )

                ->orderBy('name')

                ->get()

        );
    }
}
