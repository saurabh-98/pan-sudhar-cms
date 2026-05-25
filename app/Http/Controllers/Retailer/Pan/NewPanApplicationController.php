<?php

namespace App\Http\Controllers\Retailer\Pan;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use App\DTO\PanApplicationDTO;

use App\Http\Requests\StorePanApplicationRequest;
use App\Http\Requests\UpdatePanApplicationRequest;

use App\Models\District;
use App\Models\PanApplication;
use App\Models\State;
use App\Models\User;
use App\Models\WalletTransaction;

use App\Services\DistrictService;
use App\Services\PanApplicationService;
use App\Services\StateService;

use Yajra\DataTables\Facades\DataTables;

class NewPanApplicationController extends Controller
{
    protected const PAN_CHARGE = 107;

    public function __construct(

        protected PanApplicationService $panService,

        protected StateService $stateService,

        protected DistrictService $districtService

    ) {}

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function index()
{
    if(request()->ajax())
    {

        $applications = PanApplication::query()

            ->with([

                /*
                |--------------------------------------------------------------------------
                | RELATIONS
                |--------------------------------------------------------------------------
                */

                'user.retailer',

                'stateData',

                'districtData',

                'documents'

            ])

            /*
            |--------------------------------------------------------------------------
            | RETAILER APPLICATIONS
            |--------------------------------------------------------------------------
            */

            ->where(

                'user_id',

                auth()->id()

            )

            ->latest();

        return DataTables::of($applications)

            ->addIndexColumn()

            /*
            |--------------------------------------------------------------------------
            | SHOP NAME
            |--------------------------------------------------------------------------
            */

            ->addColumn('shop_name', function($row){

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

            /*
            |--------------------------------------------------------------------------
            | APPLICANT NAME
            |--------------------------------------------------------------------------
            */

            ->addColumn('applicant_name', function($row){

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

            /*
            |--------------------------------------------------------------------------
            | STATE NAME
            |--------------------------------------------------------------------------
            */

            ->addColumn('state_name', function($row){

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

            /*
            |--------------------------------------------------------------------------
            | DISTRICT NAME
            |--------------------------------------------------------------------------
            */

            ->addColumn('district_name', function($row){

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

            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            ->addColumn('payment', function($row){

                return $row->payment_badge;

            })

            /*
            |--------------------------------------------------------------------------
            | AMOUNT
            |--------------------------------------------------------------------------
            */

            ->addColumn('amount', function($row){

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

            /*
            |--------------------------------------------------------------------------
            | CREATED DATE
            |--------------------------------------------------------------------------
            */

            ->addColumn('created_at', function($row){

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

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            ->addColumn('status', function($row){

                return $row->status_badge;

            })

           /*
            |--------------------------------------------------------------------------
            | DOCUMENT STATUS
            |--------------------------------------------------------------------------
            */

            ->addColumn('document_status', function($row){

                /*
                |--------------------------------------------------------------------------
                | DOCUMENT
                |--------------------------------------------------------------------------
                */

                $document = $row->documents->first();

                /*
                |--------------------------------------------------------------------------
                | SHOW RECEIPT IF APPROVED/COMPLETED
                |--------------------------------------------------------------------------
                */

                if(

                    $document

                    &&

                    in_array(

                        strtolower($row->status),

                        [

                            'approved',

                            'completed'

                        ]

                    )

                )
                {

                    return '

                        <div class="d-flex gap-2 flex-wrap">

                            <a
                                href="'

                                . asset(

                                    'storage/'.$document->file_path

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

                                . asset(

                                    'storage/'.$document->file_path

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

                /*
                |--------------------------------------------------------------------------
                | UPLOADED BUT NOT APPROVED
                |--------------------------------------------------------------------------
                */

                if($document)
                {

                    return '

                        <span class="badge bg-info">

                            Uploaded

                        </span>

                    ';

                }

                /*
                |--------------------------------------------------------------------------
                | PENDING
                |--------------------------------------------------------------------------
                */

                return '

                    <span class="badge bg-warning text-dark">

                        Pending

                    </span>

                ';

            })

            /*
            |--------------------------------------------------------------------------
            | ACTION
            |--------------------------------------------------------------------------
            */

            ->addColumn('action', function($row){

                return '

                    <div class="d-flex gap-2">

                        <a
                            href="'

                            . route(

                                'retailer.pan.view',

                                $row->id

                            )

                            . '"

                            class="btn btn-sm btn-primary"

                            title="View"

                        >

                            <i class="fa fa-eye"></i>

                        </a>

                    </div>

                ';

            })

            /*
            |--------------------------------------------------------------------------
            | RAW COLUMNS
            |--------------------------------------------------------------------------
            */

            ->rawColumns([

                'shop_name',

                'applicant_name',

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

        'retailer.pan.index'

    );
}
    /*
    |--------------------------------------------------------------------------
    | CREATE FORM
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $preview = session(
            'pan_application',
            []
        );

        return view(

            'retailer.pan.new-pan-apply',

            [

                'states' =>

                    $this->stateService
                        ->getAll(),

                'walletBalance' =>

                    auth()->user()
                        ->wallet_balance,

                'data' =>

                    $preview['data'] ?? [],

                'files' =>

                    $preview['files'] ?? []

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW
    |--------------------------------------------------------------------------
    */

    public function preview(
        StorePanApplicationRequest $request
    ): JsonResponse {

        DB::beginTransaction();

        try {

            $user = auth()->user();

            /*
            |--------------------------------------------------------------------------
            | WALLET CHECK
            |--------------------------------------------------------------------------
            */

            if (
                $user->wallet_balance
                < self::PAN_CHARGE
            ) {

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Insufficient wallet balance.'

                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | DTO
            |--------------------------------------------------------------------------
            */

            $dto =
                PanApplicationDTO::fromRequest(
                    $request
                );

            /*
            |--------------------------------------------------------------------------
            | PREVIEW
            |--------------------------------------------------------------------------
            */

            $preview =
                $this->panService
                    ->preview($dto);

            /*
            |--------------------------------------------------------------------------
            | EXTRA DATA
            |--------------------------------------------------------------------------
            */

            $preview['data']['state_name'] =

                State::where(

                    'id',
                    $request->state

                )->value('name');

            $preview['data']['district_name'] =

                District::where(

                    'id',
                    $request->district

                )->value('name');

            /*
            |--------------------------------------------------------------------------
            | SESSION
            |--------------------------------------------------------------------------
            */

            session([

                'pan_application' =>
                    $preview

            ]);

            DB::commit();

            return response()->json([

                'status' => true,

                'message' =>
                    'Preview generated successfully.',

                'redirect_url' =>

                    route(
                        'retailer.pan.preview.page'
                    )

            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(

                'PAN PREVIEW ERROR',

                [

                    'message' =>
                        $e->getMessage(),

                    'line' =>
                        $e->getLine(),

                    'file' =>
                        $e->getFile()

                ]

            );

            return response()->json([

                'status' => false,

                'message' =>
                    $e->getMessage()

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
        $preview = session(
            'pan_application'
        );

        if (!$preview) {

            return redirect()

                ->route(
                    'retailer.pan.apply'
                )

                ->with(

                    'error',

                    'Preview session expired.'

                );
        }

        return view(

            'retailer.pan.preview',

            [

                'data' =>
                    $preview['data'],

                'files' =>
                    $preview['files']

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

            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */

            $user = User::query()

                ->lockForUpdate()

                ->find(auth()->id());

            $admin = User::query()

                ->role('Admin')

                ->lockForUpdate()

                ->first();

            /*
            |--------------------------------------------------------------------------
            | SESSION CHECK
            |--------------------------------------------------------------------------
            */

            $session =
                session('pan_application');

            if (!$session) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Preview session expired. Please apply again.'

                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | BALANCE CHECK
            |--------------------------------------------------------------------------
            */

            if (
                $user->wallet_balance
                < self::PAN_CHARGE
            ) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Insufficient wallet balance.'

                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | STORE APPLICATION
            |--------------------------------------------------------------------------
            */

            $application =
                $this->panService
                    ->storeFromSession();

            /*
            |--------------------------------------------------------------------------
            | RETAILER WALLET
            |--------------------------------------------------------------------------
            */

            $retailerBefore =
                $user->wallet_balance;

            $user->wallet_balance -=
                self::PAN_CHARGE;

            $user->save();

            /*
            |--------------------------------------------------------------------------
            | ADMIN WALLET
            |--------------------------------------------------------------------------
            */

            $adminBefore =
                $admin->wallet_balance;

            $admin->wallet_balance +=
                self::PAN_CHARGE;

            $admin->save();

            /*
            |--------------------------------------------------------------------------
            | APPLICATION STATUS
            |--------------------------------------------------------------------------
            */

            $application->update([

                'wallet_deducted' =>
                    true,

                'wallet_deducted_at' =>
                    now(),

                'payment_status' =>
                    'Paid',

                'status' =>
                    'Processing'

            ]);

            /*
            |--------------------------------------------------------------------------
            | RETAILER TRANSACTION
            |--------------------------------------------------------------------------
            */

            WalletTransaction::create([

                'user_id' =>
                    $user->id,

                'amount' =>
                    self::PAN_CHARGE,

                'before_balance' =>
                    $retailerBefore,

                'after_balance' =>
                    $user->wallet_balance,

                'type' =>
                    'debit',

                'status' =>
                    'success',

                'transaction_no' =>

                    'TXN'

                    . now()->format('YmdHis')

                    . rand(1000,9999),

                'remark' =>
                    'New PAN Application Charge'

            ]);

            /*
            |--------------------------------------------------------------------------
            | ADMIN TRANSACTION
            |--------------------------------------------------------------------------
            */

            WalletTransaction::create([

                'user_id' =>
                    $admin->id,

                'amount' =>
                    self::PAN_CHARGE,

                'before_balance' =>
                    $adminBefore,

                'after_balance' =>
                    $admin->wallet_balance,

                'type' =>
                    'credit',

                'status' =>
                    'success',

                'transaction_no' =>

                    'ADM'

                    . now()->format('YmdHis')

                    . rand(1000,9999),

                'remark' =>
                    'PAN Application Received Amount'

            ]);

            /*
            |--------------------------------------------------------------------------
            | CLEAR SESSION
            |--------------------------------------------------------------------------
            */

            Session::forget(
                'pan_application'
            );

            DB::commit();

            return response()->json([

                'status' => true,

                'message' =>
                    'PAN Application Submitted Successfully.',

                'redirect_url' =>

                    route(

                        'retailer.pan.acknowledgement',

                        $application->id

                    )

            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(

                'PAN FINAL SUBMIT ERROR',

                [

                    'message' =>
                        $e->getMessage(),

                    'line' =>
                        $e->getLine(),

                    'file' =>
                        $e->getFile()

                ]

            );

            return response()->json([

                'status' => false,

                'message' =>
                    $e->getMessage()

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

            'retailer.pan.show',

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
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(int $id)
    {
        return view(

            'retailer.pan.edit',

            [

                'application' =>

                    $this->panService->find(
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
        UpdatePanApplicationRequest $request,
        int $id
    )
    {
        $this->panService->update(

            $request,

            $id,

            auth()->id()

        );

        return redirect()

            ->route(
                'retailer.pan.history'
            )

            ->with(

                'success',

                'PAN Application Updated Successfully.'

            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(int $id)
    {
        $this->panService->delete(

            $id,

            auth()->id()

        );

        return redirect()

            ->back()

            ->with(

                'success',

                'PAN Application Deleted Successfully.'

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

            'retailer.pan.status',

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
    | ACKNOWLEDGEMENT
    |--------------------------------------------------------------------------
    */

    public function acknowledgement(int $id)
    {
        return view(

            'retailer.pan.acknowledgement',

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
    | PRINT
    |--------------------------------------------------------------------------
    */

    public function print(int $id)
    {
        return view(

            'retailer.pan.print',

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