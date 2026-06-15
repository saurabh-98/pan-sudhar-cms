<?php

namespace App\Http\Controllers\Retailer\Csc;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\DTO\CscServiceDTO;
use App\Http\Requests\StoreCscServiceRequest;
use App\Services\CscServiceService;

use App\Models\CscService;
use App\Models\Charge;
use App\Models\User;
use App\Models\WalletTransaction;

use Yajra\DataTables\Facades\DataTables;


class CscServiceController extends Controller
{
    public function __construct(
        protected CscServiceService $cscServiceService
    ) {}


       private function getCscCharge(
            string $serviceSlug
        ): float {

            $code = str_replace(
                '-',
                '_',
                $serviceSlug
            );

            return (float) Charge::query()

                ->where(
                    'code',
                    $code
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

        $session = get_csc_session();

        return view(
            'retailer.csc.create',
            [
                'serviceSlug'   => $service,

                'serviceName'   => $serviceName,

                'fields'        => csc_service_fields($service),

                'cscCharge'     => $this->getCscCharge($service),

                'walletBalance' => auth()->user()->wallet_balance,

                'data'          => $session['data'] ?? [],

                'files'         => $session['files'] ?? [],
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW
    |--------------------------------------------------------------------------
    */

    public function preview(
        StoreCscServiceRequest $request
    ): JsonResponse {

        try {

            $user = auth()->user();

            $cscCharge =
                $this->getCscCharge(
                    $request->service_slug
                );

            /*
            |--------------------------------------------------------------------------
            | CHARGE CHECK
            |--------------------------------------------------------------------------
            */

            if ($cscCharge <= 0) {

                return response()->json([

                    'status' => false,

                    'message' =>
                        'CSC service charge is not configured.'

                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | WALLET CHECK
            |--------------------------------------------------------------------------
            */

            if ($user->wallet_balance < $cscCharge) {

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

            $dto = CscServiceDTO::fromRequest(
                $request
            );

            /*
            |--------------------------------------------------------------------------
            | GENERATE PREVIEW
            |--------------------------------------------------------------------------
            */

            $preview =

                $this->cscServiceService
                    ->preview($dto);

            /*
            |--------------------------------------------------------------------------
            | ADD CHARGE
            |--------------------------------------------------------------------------
            */

            $preview['data']['csc_charge'] =
                $cscCharge;

            /*
            |--------------------------------------------------------------------------
            | SAVE SESSION
            |--------------------------------------------------------------------------
            */

            save_csc_session(
                $preview
            );

            return response()->json([

                'status' => true,

                'message' =>
                    'Preview generated successfully.',

                'redirect_url' =>

                    route(
                        'retailer.csc.preview-page'
                    )

            ]);

        } catch (\Throwable $e) {

            Log::error(

                'CSC PREVIEW ERROR',

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
        $preview = get_csc_session();

        if (
            empty($preview)
            ||
            !isset($preview['data'])
        ) {

            return redirect()
                ->route('retailer.csc.history')
                ->with(
                    'error',
                    'Preview session expired.'
                );
        }

        return view(
            'retailer.csc.preview',
            [

                'data' => $preview['data'],

                'files' => $preview['files'] ?? [],

                'cscCharge' =>
                    $preview['data']['csc_charge']
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

            $session = get_csc_session();

            if (
                empty($session)
                ||
                !is_array($session)
                ||
                !isset($session['data'])
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

            $cscCharge = (float) (

                $data['csc_charge']
                ??
                $this->getCscCharge(
                    $data['service_slug']
                )

            );

            if ($cscCharge <= 0) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' =>
                        'CSC service charge is not configured.'

                ], 422);
            }

            $user = User::query()

                ->lockForUpdate()

                ->find(auth()->id());

            if (!$user) {

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

            if (!$admin) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Admin account not found.'

                ], 500);
            }

            if (
                $user->wallet_balance <
                $cscCharge
            ) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Insufficient wallet balance.'

                ], 422);
            }

            $application =

                $this->cscServiceService
                    ->storeFromSession();

            if (!$application) {

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

                $cscCharge

            );

            $admin->increment(

                'wallet_balance',

                $cscCharge

            );

            $user->refresh();

            $admin->refresh();

            $application->update([

                'amount' => $cscCharge,

                'wallet_deducted' => true,

                'wallet_deducted_at' => now(),

                'payment_status' => 'Paid',

                'status' => 'Processing',

            ]);

            WalletTransaction::create([

                'user_id' => $user->id,

                'amount' => $cscCharge,

                'before_balance' => $retailerBefore,

                'after_balance' => $user->wallet_balance,

                'type' => 'debit',

                'status' => 'success',

                'transaction_no' =>
                    'CSC'
                    . now()->format('YmdHis')
                    . rand(1000, 9999),

                'remark' =>
                    'CSC Service Charge'

            ]);

            WalletTransaction::create([

                'user_id' => $admin->id,

                'amount' => $cscCharge,

                'before_balance' => $adminBefore,

                'after_balance' => $admin->wallet_balance,

                'type' => 'credit',

                'status' => 'success',

                'transaction_no' =>
                    'CSCADM'
                    . now()->format('YmdHis')
                    . rand(1000, 9999),

                'remark' =>
                    'CSC Service Received Amount'

            ]);

            clear_csc_session();

            DB::commit();

            return response()->json([

                'status' => true,

                'message' =>
                    'CSC Service Submitted Successfully.',

                'redirect_url' => route(

                    'retailer.csc.receiving',

                    $application->id

                )

            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(

                'CSC FINAL SUBMIT ERROR',

                [

                    'message' => $e->getMessage(),

                    'file' => $e->getFile(),

                    'line' => $e->getLine(),

                    'user_id' => auth()->id(),

                    'session' => get_csc_session()

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

            $applications = CscService::query()

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
                            $row->getField(
                                'farmer_name',
                                $row->getField(
                                    'child_name',
                                    'N/A'
                                )
                            )
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
                                    'retailer.csc.show',
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
            'retailer.csc.history'
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

            'retailer.csc.show',

            [

                'application' =>

                    $this->cscServiceService
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

            'retailer.csc.acknowledgement',

            [

                'application' =>

                    $this->cscServiceService
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

            'retailer.csc.print',

            [

                'application' =>

                    $this->cscServiceService
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

        $this->cscServiceService
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
