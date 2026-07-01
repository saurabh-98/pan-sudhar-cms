<?php

namespace App\Http\Controllers\Retailer\Pan;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Barryvdh\DomPDF\Facade\Pdf;

use App\DTO\PanApplicationDTO;

use App\Http\Requests\StorePanApplicationRequest;
use App\Http\Requests\UpdatePanApplicationRequest;

use App\Models\District;
use App\Models\PanApplication;
use App\Models\State;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\Charge;
use Carbon\Carbon;
use App\Services\DistrictService;
use App\Services\PanApplicationService;
use App\Services\StateService;

use Yajra\DataTables\Facades\DataTables;

class NewPanApplicationController extends Controller
{
    

    public function __construct(

        protected PanApplicationService $panService,

        protected StateService $stateService,

        protected DistrictService $districtService

    ) {}

   

    private function getPanCharge(): float
    {
        return (float) Charge::query()

            ->where(
                'code',
                'new_pan_apply'
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

            $applications = PanApplication::query()

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
                                    'retailer.pan.view',
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
        $preview = get_pan_session();

        if (!empty($preview['data']['dob'])) {

            try {

                $preview['data']['dob'] = Carbon::parse(
                    $preview['data']['dob']
                )->format('d/m/Y');

            } catch (\Exception $e) {
                //
            }
        }

        if (!empty($preview['data']['confirm_dob'])) {

            try {

                $preview['data']['confirm_dob'] = Carbon::parse(
                    $preview['data']['confirm_dob']
                )->format('d/m/Y');

            } catch (\Exception $e) {
                //
            }
        }

        return view(

            'retailer.pan.new-pan-apply',

            [

                'states' =>
                    $this->stateService
                        ->getAll(),

                'walletBalance' =>
                    auth()->user()
                        ->wallet_balance,

                'panCharge' =>
                    $this->getPanCharge(),

                'data' =>
                    $preview['data'] ?? [],

                'files' =>
                    $preview['files'] ?? []

            ]

        );
    }
  

    public function preview(
        StorePanApplicationRequest $request
    ): JsonResponse {

        try {

            $user = auth()->user();

            $panCharge = $this->getPanCharge();

            if ($panCharge <= 0) {

                return response()->json([

                    'status' => false,

                    'message' => 'PAN charge is not configured.'

                ], 422);

            }

            if ($user->wallet_balance < $panCharge) {

                return response()->json([

                    'status' => false,

                    'message' => 'Insufficient wallet balance.'

                ], 422);

            }

            $dto = PanApplicationDTO::fromRequest(
                $request
            );

            /*
            |--------------------------------------------------------------------------
            | CLOUDINARY UPLOAD + SESSION SAVE FROM SERVICE
            |--------------------------------------------------------------------------
            */

            $preview = $this->panService->preview(
                $dto
            );

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

            save_pan_session(
                $preview
            );

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
        $preview = get_pan_session();

        if (
            empty($preview)
            ||
            !isset($preview['data'])
            ||
            !is_array($preview['data'])
        ) {

            return redirect()
                ->route('retailer.pan.apply')
                ->with(
                    'error',
                    'Preview session expired.'
                );
        }

        $data = $preview['data'];

        // Format DOB for preview display
        if (!empty($data['dob'])) {

            try {

                $data['dob'] = Carbon::parse(
                    $data['dob']
                )->format('d/m/Y');

            } catch (\Exception $e) {
                //
            }
        }

        // Format Confirm DOB for preview display
        if (!empty($data['confirm_dob'])) {

            try {

                $data['confirm_dob'] = Carbon::parse(
                    $data['confirm_dob']
                )->format('d/m/Y');

            } catch (\Exception $e) {
                //
            }
        }

        return view(

            'retailer.pan.preview',

            [

                'data' => $data,

                'files' =>
                    $preview['files']
                    ??
                    [],

                'panCharge' =>
                    $preview['data']['pan_charge']
                    ??
                    $this->getPanCharge()

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

            $panCharge = $this->getPanCharge();

            if ($panCharge <= 0) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' => 'PAN charge is not configured.'

                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | USERS LOCK
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
            | ADMIN CHECK
            |--------------------------------------------------------------------------
            */

            if (!$admin) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' => 'Admin account not found.'

                ], 500);
            }

            /*
            |--------------------------------------------------------------------------
            | SESSION CHECK
            |--------------------------------------------------------------------------
            */

            $session = get_pan_session();

            if (!$session) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' => 'Preview session expired. Please apply again.'

                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | FILE CHECK
            |--------------------------------------------------------------------------
            */

            $requiredFiles = [

                'photo',

                'signature',

                'aadhaar_card',

            ];

            foreach ($requiredFiles as $key) {

                $file = $session['files'][$key] ?? null;

                if (empty($file)) {

                    DB::rollBack();

                    return response()->json([

                        'status' => false,

                        'message' => ucfirst(
                            str_replace('_', ' ', $key)
                        ) . ' is missing. Please upload again.'

                    ], 422);
                }

                if (
                    function_exists('file_exists_custom')
                    &&
                    !file_exists_custom($file)
                ) {

                    DB::rollBack();

                    return response()->json([

                        'status' => false,

                        'message' => ucfirst(
                            str_replace('_', ' ', $key)
                        ) . ' file not found. Please upload again.'

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

                    'status' => false,

                    'message' => 'Insufficient wallet balance.'

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
            | BALANCE BEFORE
            |--------------------------------------------------------------------------
            */

            $retailerBefore = $user->wallet_balance;

            $adminBefore = $admin->wallet_balance;

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

                'amount' => $panCharge,

                'wallet_deducted' => true,

                'wallet_deducted_at' => now(),

                'payment_status' => 'Paid',

                'status' => 'Processing'

            ]);

            /*
            |--------------------------------------------------------------------------
            | RETAILER TRANSACTION
            |--------------------------------------------------------------------------
            */

            WalletTransaction::create([

                'user_id' => $user->id,

                'amount' => $panCharge,

                'before_balance' => $retailerBefore,

                'after_balance' => $user->wallet_balance,

                'type' => 'debit',

                'status' => 'success',

                'transaction_no' =>

                    'TXN'

                    . now()->format('YmdHis')

                    . rand(1000, 9999),

                'remark' => 'New PAN Application Charge'

            ]);

            /*
            |--------------------------------------------------------------------------
            | ADMIN TRANSACTION
            |--------------------------------------------------------------------------
            */

            WalletTransaction::create([

                'user_id' => $admin->id,

                'amount' => $panCharge,

                'before_balance' => $adminBefore,

                'after_balance' => $admin->wallet_balance,

                'type' => 'credit',

                'status' => 'success',

                'transaction_no' =>

                    'ADM'

                    . now()->format('YmdHis')

                    . rand(1000, 9999),

                'remark' => 'PAN Application Received Amount'

            ]);

            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */

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
        $application = $this->panService->find(
            $id,
            auth()->id()
        );

        if (!$application) {
            abort(404);
        }

        $pdf = Pdf::loadView(
            'retailer.pan.print',
            compact('application')
        );

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream(
            'PAN-RECEIPT-'.$application->application_no.'.pdf'
        );

        // To download instead of opening:
        // return $pdf->download('PAN-RECEIPT-'.$application->application_no.'.pdf');
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