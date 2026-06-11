<?php

namespace App\Http\Controllers\Retailer\Aadhaar;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use App\DTO\AadhaarServiceDTO;

use App\Http\Requests\StoreAadhaarServiceRequest;

use App\Services\AadhaarServiceService;
use App\Models\Charge;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AadhaarService;


class AadhaarServiceController extends Controller
{
    public function __construct(
        protected AadhaarServiceService $aadhaarServiceService
    ) {}


       private function getAadhaarCharge(
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

        $session = get_aadhaar_session();

        return view(
            'retailer.aadhaar.create',
            [
                'serviceSlug'   => $service,

                'serviceName'   => $serviceName,

                'fields'        => aadhaar_service_fields($service),

                'aadhaarCharge' => $this->getAadhaarCharge($service),

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
        StoreAadhaarServiceRequest $request
    ): JsonResponse {

        try {

            $user = auth()->user();

           $aadhaarCharge =
                $this->getAadhaarCharge(
                    $request->service_slug
                );

            /*
            |--------------------------------------------------------------------------
            | CHARGE CHECK
            |--------------------------------------------------------------------------
            */

            if ($aadhaarCharge <= 0) {

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Aadhaar service charge is not configured.'

                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | WALLET CHECK
            |--------------------------------------------------------------------------
            */

            if ($user->wallet_balance < $aadhaarCharge) {

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

            $dto = AadhaarServiceDTO::fromRequest(
                $request
            );

            /*
            |--------------------------------------------------------------------------
            | GENERATE PREVIEW
            |--------------------------------------------------------------------------
            */

            $preview =

                $this->aadhaarServiceService
                    ->preview($dto);

            /*
            |--------------------------------------------------------------------------
            | ADD CHARGE
            |--------------------------------------------------------------------------
            */

            $preview['data']['aadhaar_charge'] =
                $aadhaarCharge;

            /*
            |--------------------------------------------------------------------------
            | SAVE SESSION
            |--------------------------------------------------------------------------
            */

            save_aadhaar_session(
                $preview
            );

            return response()->json([

                'status' => true,

                'message' =>
                    'Preview generated successfully.',

                'redirect_url' =>

                    route(
                        'retailer.aadhaar.preview-page'
                    )

            ]);

        } catch (\Throwable $e) {

            Log::error(

                'AADHAAR PREVIEW ERROR',

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
        $preview = get_aadhaar_session();

        if (
            empty($preview)
            ||
            !isset($preview['data'])
        ) {

            return redirect()
                ->route('retailer.aadhaar.history')
                ->with(
                    'error',
                    'Preview session expired.'
                );
        }

        return view(
            'retailer.aadhaar.preview',
            [

                'data' => $preview['data'],

                'files' => $preview['files'] ?? [],

                'aadhaarCharge' =>
                    $preview['data']['aadhaar_charge']
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

            /*
            |--------------------------------------------------------------------------
            | GET SESSION
            |--------------------------------------------------------------------------
            */

            $session = get_aadhaar_session();

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

            /*
            |--------------------------------------------------------------------------
            | SESSION DATA
            |--------------------------------------------------------------------------
            */

            $data = $session['data'];

            /*
            |--------------------------------------------------------------------------
            | SERVICE SLUG CHECK
            |--------------------------------------------------------------------------
            */

            if (
                empty($data['service_slug'])
            ) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' => 'Service information missing.'

                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | CHARGE
            |--------------------------------------------------------------------------
            */

            $aadhaarCharge = (float) (

                $data['aadhaar_charge']
                ??
                $this->getAadhaarCharge(
                    $data['service_slug']
                )

            );

            if ($aadhaarCharge <= 0) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Aadhaar service charge is not configured.'

                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | WALLET CHECK
            |--------------------------------------------------------------------------
            */

            if (
                $user->wallet_balance <
                $aadhaarCharge
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

                $this->aadhaarServiceService
                    ->storeFromSession();

            if (!$application) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Unable to create application.'

                ], 500);
            }

            /*
            |--------------------------------------------------------------------------
            | BALANCE BEFORE
            |--------------------------------------------------------------------------
            */

            $retailerBefore =
                $user->wallet_balance;

            $adminBefore =
                $admin->wallet_balance;

            /*
            |--------------------------------------------------------------------------
            | DEDUCT RETAILER
            |--------------------------------------------------------------------------
            */

            $user->decrement(

                'wallet_balance',

                $aadhaarCharge

            );

            /*
            |--------------------------------------------------------------------------
            | CREDIT ADMIN
            |--------------------------------------------------------------------------
            */

            $admin->increment(

                'wallet_balance',

                $aadhaarCharge

            );

            $user->refresh();

            $admin->refresh();

            /*
            |--------------------------------------------------------------------------
            | UPDATE APPLICATION
            |--------------------------------------------------------------------------
            */

            $application->update([

                'amount' => $aadhaarCharge,

                'wallet_deducted' => true,

                'wallet_deducted_at' => now(),

                'payment_status' => 'Paid',

                'status' => 'Processing',

            ]);

            /*
            |--------------------------------------------------------------------------
            | RETAILER TRANSACTION
            |--------------------------------------------------------------------------
            */

            WalletTransaction::create([

                'user_id' => $user->id,

                'amount' => $aadhaarCharge,

                'before_balance' => $retailerBefore,

                'after_balance' => $user->wallet_balance,

                'type' => 'debit',

                'status' => 'success',

                'transaction_no' =>
                    'AAD'
                    . now()->format('YmdHis')
                    . rand(1000, 9999),

                'remark' =>
                    'Aadhaar Service Charge'

            ]);

            /*
            |--------------------------------------------------------------------------
            | ADMIN TRANSACTION
            |--------------------------------------------------------------------------
            */

            WalletTransaction::create([

                'user_id' => $admin->id,

                'amount' => $aadhaarCharge,

                'before_balance' => $adminBefore,

                'after_balance' => $admin->wallet_balance,

                'type' => 'credit',

                'status' => 'success',

                'transaction_no' =>
                    'AADADM'
                    . now()->format('YmdHis')
                    . rand(1000, 9999),

                'remark' =>
                    'Aadhaar Service Received Amount'

            ]);

            /*
            |--------------------------------------------------------------------------
            | CLEAR SESSION
            |--------------------------------------------------------------------------
            */

            clear_aadhaar_session();

            DB::commit();

            return response()->json([

                'status' => true,

                'message' =>
                    'Aadhaar Service Submitted Successfully.',

                'redirect_url' => route(

                    'retailer.aadhaar.receiving',

                    $application->id

                )

            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(

                'AADHAAR FINAL SUBMIT ERROR',

                [

                    'message' => $e->getMessage(),

                    'file' => $e->getFile(),

                    'line' => $e->getLine(),

                    'user_id' => auth()->id(),

                    'session' => get_aadhaar_session()

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

            $applications = AadhaarService::query()

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
                                'child_name',
                                'N/A'
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
                                    'retailer.aadhaar.show',
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
            'retailer.aadhaar.history'
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

            'retailer.aadhaar.show',

            [

                'application' =>

                    $this->aadhaarServiceService
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

            'retailer.aadhaar.acknowledgement',

            [

                'application' =>

                    $this->aadhaarServiceService
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

            'retailer.aadhaar.print',

            [

                'application' =>

                    $this->aadhaarServiceService
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

        $this->aadhaarServiceService
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

    /*
    |--------------------------------------------------------------------------
    | SERVICES LIST
    |--------------------------------------------------------------------------
    */

    protected function getServices(): array
    {
        return [

            'Mobile Number Update',

            'Name Correction',

            'DOB Correction',

            'Address Update',

            'Father Name Update',

            'Husband Name Update',

            'Gender Update',

            'Email Update',

            'Biometric Update',

            'Child Aadhaar Enrollment',

            'New Aadhaar Apply',

            'Aadhaar PVC Card',

            'Aadhaar Download',

            'Aadhaar Status Check',

            'Aadhaar Verification'

        ];
    }
}
