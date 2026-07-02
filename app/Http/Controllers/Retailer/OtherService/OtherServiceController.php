<?php

namespace App\Http\Controllers\Retailer\OtherService;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\DTO\OtherServiceDTO;
use App\Http\Requests\StoreOtherServiceRequest;
use App\Services\OtherServiceService;
use App\Services\ServiceGuidelineService;

use App\Models\OtherService;
use App\Models\Charge;
use App\Models\User;
use App\Models\WalletTransaction;

use Yajra\DataTables\Facades\DataTables;

class OtherServiceController extends Controller
{
    public function __construct(
        protected OtherServiceService $otherServiceService,
        protected ServiceGuidelineService $serviceGuidelineService,
    ) {}

    private function getOtherServiceCharge(
        string $serviceSlug
    ): float {

        $code = str_replace(
            '-',
            '_',
            $serviceSlug
        );

        return (float) Charge::query()
            ->where('code', $code)
            ->where('is_active', 1)
            ->value('value');
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(string $service)
    {
        $serviceName = collect(
            explode('-', $service)
        )
        ->map(fn ($word) => ucfirst($word))
        ->implode(' ');

        $session = get_other_service_session();

        return view(
            'retailer.other-service.create',
            [

                'serviceSlug' => $service,

                'serviceName' => $serviceName,

                'fields' => other_service_fields($service),

                'otherServiceCharge' =>
                    $this->getOtherServiceCharge($service),

                'walletBalance' =>
                    auth()->user()->wallet_balance,

                'data' =>
                    $session['data'] ?? [],

                'files' =>
                    $session['files'] ?? [],

                  'guideline' =>

                    $this->serviceGuidelineService
                        ->getActiveGuideline($service),

            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PREVIEW
    |--------------------------------------------------------------------------
    */

     public function preview(
    StoreOtherServiceRequest $request
    ): JsonResponse {

        try {

            $user = auth()->user();

            $otherServiceCharge =
                $this->getOtherServiceCharge(
                    $request->service_slug
                );

            /*
            |--------------------------------------------------------------------------
            | CHARGE CHECK
            |--------------------------------------------------------------------------
            */

            if ($otherServiceCharge <= 0) {

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Other service charge is not configured.'

                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | WALLET CHECK
            |--------------------------------------------------------------------------
            */

            if ($user->wallet_balance < $otherServiceCharge) {

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

            $dto = OtherServiceDTO::fromRequest(
                $request
            );

            /*
            |--------------------------------------------------------------------------
            | GENERATE PREVIEW
            |--------------------------------------------------------------------------
            */

            $preview =

                $this->otherService
                    ->preview($dto);

            /*
            |--------------------------------------------------------------------------
            | ADD CHARGE
            |--------------------------------------------------------------------------
            */

            $preview['data']['other_service_charge'] =
                $otherServiceCharge;

            /*
            |--------------------------------------------------------------------------
            | SAVE SESSION
            |--------------------------------------------------------------------------
            */

            save_other_service_session(
                $preview
            );

            return response()->json([

                'status' => true,

                'message' =>
                    'Preview generated successfully.',

                'redirect_url' =>

                    route(
                        'retailer.other-service.preview-page'
                    )

            ]);

        } catch (\Throwable $e) {

            Log::error(

                'OTHER SERVICE PREVIEW ERROR',

                [

                    'message' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine()

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
        $preview = get_other_service_session();

        if (
            empty($preview)
            ||
            ! isset($preview['data'])
        ) {

            return redirect()
                ->route('retailer.other-service.history')
                ->with(
                    'error',
                    'Preview session expired.'
                );
        }

        return view(
            'retailer.other-service.preview',
            [

                'data' =>
                    $preview['data'],

                'files' =>
                    $preview['files'] ?? [],

                'otherServiceCharge' =>

                    $preview['data']['other_service_charge']

                    ?? 0,

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

        $session = get_other_service_session();

        if (
            empty($session)
            ||
            ! is_array($session)
            ||
            ! isset($session['data'])
        ) {

            DB::rollBack();

            return response()->json([

                'status' => false,

                'message' => 'Preview session expired.'

            ], 422);
        }

        $data = $session['data'];

        if (
            empty($data['service_slug'])
        ) {

            DB::rollBack();

            return response()->json([

                'status' => false,

                'message' => 'Service information missing.'

            ], 422);
        }

        $otherServiceCharge = (float) (

            $data['other_service_charge']
            ??
            $this->getOtherServiceCharge(
                $data['service_slug']
            )

        );

        if ($otherServiceCharge <= 0) {

            DB::rollBack();

            return response()->json([

                'status' => false,

                'message' =>
                    'Other service charge is not configured.'

            ], 422);
        }

        $user = User::query()

            ->lockForUpdate()

            ->find(auth()->id());

        if (! $user) {

            DB::rollBack();

            return response()->json([

                'status' => false,

                'message' => 'User not found.'

            ], 404);
        }

        $admin = User::query()

            ->role('Admin')

            ->lockForUpdate()

            ->first();

        if (! $admin) {

            DB::rollBack();

            return response()->json([

                'status' => false,

                'message' =>
                    'Admin account not found.'

            ], 500);
        }

        if (
            $user->wallet_balance <
            $otherServiceCharge
        ) {

            DB::rollBack();

            return response()->json([

                'status' => false,

                'message' =>
                    'Insufficient wallet balance.'

            ], 422);
        }

        $application =

            $this->otherService
                ->storeFromSession();

        if (! $application) {

            DB::rollBack();

            return response()->json([

                'status' => false,

                'message' =>
                    'Unable to create application.'

            ], 500);
        }

        $retailerBefore =
            $user->wallet_balance;

        $adminBefore =
            $admin->wallet_balance;

        $user->decrement(

            'wallet_balance',

            $otherServiceCharge

        );

        $admin->increment(

            'wallet_balance',

            $otherServiceCharge

        );

        $user->refresh();

        $admin->refresh();

        $application->update([

            'amount' => $otherServiceCharge,

            'wallet_deducted' => true,

            'wallet_deducted_at' => now(),

            'payment_status' => 'Paid',

            'status' => 'Processing',

        ]);

        WalletTransaction::create([

            'user_id' => $user->id,

            'amount' => $otherServiceCharge,

            'before_balance' => $retailerBefore,

            'after_balance' => $user->wallet_balance,

            'type' => 'debit',

            'status' => 'success',

            'transaction_no' =>
                'OTH'
                . now()->format('YmdHis')
                . rand(1000, 9999),

            'remark' =>
                'Other Service Charge'

        ]);

        WalletTransaction::create([

            'user_id' => $admin->id,

            'amount' => $otherServiceCharge,

            'before_balance' => $adminBefore,

            'after_balance' => $admin->wallet_balance,

            'type' => 'credit',

            'status' => 'success',

            'transaction_no' =>
                'OTHADM'
                . now()->format('YmdHis')
                . rand(1000, 9999),

            'remark' =>
                'Other Service Received Amount'

        ]);

        clear_other_service_session();

        DB::commit();

        return response()->json([

            'status' => true,

            'message' =>
                'Other Service Submitted Successfully.',

            'redirect_url' => route(

                'retailer.other-service.receiving',

                $application->id

            )

        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        Log::error(

            'OTHER SERVICE FINAL SUBMIT ERROR',

            [

                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),

                'user_id' => auth()->id(),

                'session' => get_other_service_session()

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
    | HISTORY
    |--------------------------------------------------------------------------
    */

    
    public function index()
    {
        if (request()->ajax()) {

            $applications = OtherService::query()

                ->with('user.retailer')

                ->where(
                    'user_id',
                    auth()->id()
                )

                ->latest();

            return DataTables::of($applications)

                ->addIndexColumn()

                ->addColumn('customer_name', function ($row) {

                    return e(
                        $row->getField(
                            'customer_name',
                            'N/A'
                        )
                    );
                })

                ->addColumn('mobile', function ($row) {

                    return e(
                        $row->getField(
                            'mobile',
                            '-'
                        )
                    );
                })

                ->addColumn('service', function ($row) {

                    return '
                        <span class="badge bg-info">
                            '.e($row->service_display).'
                        </span>
                    ';
                })

                ->addColumn('payment', function ($row) {

                    return $row->payment_badge;
                })

                ->addColumn('amount', function ($row) {

                    return '
                        <span class="fw-bold text-success">
                            ₹'.number_format(
                                $row->amount,
                                2
                            ).'
                        </span>
                    ';
                })

                ->addColumn('status', function ($row) {

                    return $row->status_badge;
                })

                ->editColumn('created_at', function ($row) {

                    return '
                        <div>
                            <div class="fw-semibold">
                                '.$row->created_at->format(
                                    'd M Y'
                                ).'
                            </div>

                            <small class="text-muted">
                                '.$row->created_at->format(
                                    'h:i A'
                                ).'
                            </small>
                        </div>
                    ';
                })

                ->addColumn('action', function ($row) {

                    return '
                        <div class="d-flex gap-2">

                            <a
                                href="'.route(
                                    'retailer.other-service.show',
                                    $row->id
                                ).'"
                                class="btn btn-sm btn-primary"
                            >
                                <i class="fa fa-eye"></i>
                            </a>

                        </div>
                    ';
                })

                ->rawColumns([

                    'service',

                    'payment',

                    'amount',

                    'status',

                    'created_at',

                    'action',

                ])

                ->make(true);
        }

        return view(
            'retailer.other-service.history'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        int $id
    ) {

        return view(

            'retailer.other-service.show',

            [

                'application' =>

                    $this->otherService
                        ->find(

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

   public function acknowledgement(
    int $id
    ) {

        return view(

            'retailer.other-service.acknowledgement',

            [

                'application' =>

                    $this->otherService
                        ->find(

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

   public function print(
        int $id
    ) {

        return view(

            'retailer.other-service.print',

            [

                'application' =>

                    $this->otherService
                        ->find(

                            $id,

                            auth()->id()

                        )

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        int $id
    ) {

        $this->otherService
            ->delete(

                $id,

                auth()->id()

            );

        return response()->json([

            'status' => true,

            'message' =>

                'Application deleted successfully.'

        ]);
    }
   
}
