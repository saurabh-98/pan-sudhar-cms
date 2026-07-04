<?php

namespace App\Http\Controllers\Retailer\VoterId;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\DTO\VoterIdServiceDTO;
use App\Http\Requests\StoreVoterIdServiceRequest;
use App\Services\VoterIdServiceService;
use App\Services\ServiceGuidelineService;

use App\Models\VoterIdService;
use App\Models\Charge;
use App\Models\User;
use App\Models\WalletTransaction;

use Yajra\DataTables\Facades\DataTables;


class VoterIdServiceController extends Controller
{
    public function __construct(
        protected VoterIdServiceService $voterIdService,
        protected ServiceGuidelineService $serviceGuidelineService
    ) {}


       private function getVoterIdCharge(
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

        $session = get_voter_id_session();

        return view(
            'retailer.voter-id.create',
            [
                'serviceSlug' => $service,

                'serviceName' => $serviceName,

                'fields' => voter_id_fields($service),

                'voterCharge' => $this->getVoterIdCharge($service),

                'walletBalance' => auth()->user()->wallet_balance,

                'data' => $session['data'] ?? [],

                'files' => $session['files'] ?? [],
                
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
    StoreVoterIdServiceRequest $request
    ): JsonResponse {

        try {

            $user = auth()->user();

            $voterIdCharge =
                $this->getVoterIdCharge(
                    $request->service_slug
                );

            /*
            |--------------------------------------------------------------------------
            | CHARGE CHECK
            |--------------------------------------------------------------------------
            */

            if ($voterIdCharge <= 0) {

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Voter ID service charge is not configured.'

                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | WALLET CHECK
            |--------------------------------------------------------------------------
            */

            if ($user->wallet_balance < $voterIdCharge) {

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

            $dto = VoterIdServiceDTO::fromRequest(
                $request
            );

            /*
            |--------------------------------------------------------------------------
            | GENERATE PREVIEW
            |--------------------------------------------------------------------------
            */

            $preview =

                $this->voterIdService
                    ->preview($dto);

            /*
            |--------------------------------------------------------------------------
            | ADD CHARGE
            |--------------------------------------------------------------------------
            */

            $preview['data']['voter_id_charge'] =
                $voterIdCharge;

            /*
            |--------------------------------------------------------------------------
            | SAVE SESSION
            |--------------------------------------------------------------------------
            */

            save_voter_id_session(
                $preview
            );

            return response()->json([

                'status' => true,

                'message' =>
                    'Preview generated successfully.',

                'redirect_url' =>

                    route(
                        'retailer.voter-id.preview-page'
                    )

            ]);

        } catch (\Throwable $e) {

            Log::error(

                'VOTER ID PREVIEW ERROR',

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
        $preview = get_voter_id_session();

        if (
            empty($preview)
            ||
            ! isset($preview['data'])
        ) {

            return redirect()
                ->route('retailer.voter-id.history')
                ->with(
                    'error',
                    'Preview session expired.'
                );
        }

        return view(
            'retailer.voter-id.preview',
            [

                'data' => $preview['data'],

                'files' => $preview['files'] ?? [],

                'voterCharge' =>
                    $preview['data']['voter_id_charge']
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

            $session = get_voter_id_session();

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

            $voterIdCharge = (float) (

                $data['voter_id_charge']
                ??
                $this->getVoterIdCharge(
                    $data['service_slug']
                )

            );

            if ($voterIdCharge <= 0) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Voter ID service charge is not configured.'

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
                $voterIdCharge
            ) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Insufficient wallet balance.'

                ], 422);
            }

            $application =

                $this->voterIdService
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

                $voterIdCharge

            );

            $admin->increment(

                'wallet_balance',

                $voterIdCharge

            );

            $user->refresh();

            $admin->refresh();

            $application->update([

                'amount' => $voterIdCharge,

                'wallet_deducted' => true,

                'wallet_deducted_at' => now(),

                'payment_status' => 'Paid',

                'status' => 'Processing',

            ]);

            WalletTransaction::create([

                'user_id' => $user->id,

                'amount' => $voterIdCharge,

                'before_balance' => $retailerBefore,

                'after_balance' => $user->wallet_balance,

                'type' => 'debit',

                'status' => 'success',

                'transaction_no' =>
                    'VID'
                    . now()->format('YmdHis')
                    . rand(1000, 9999),

                'remark' =>
                    'Voter ID Service Charge'

            ]);

            WalletTransaction::create([

                'user_id' => $admin->id,

                'amount' => $voterIdCharge,

                'before_balance' => $adminBefore,

                'after_balance' => $admin->wallet_balance,

                'type' => 'credit',

                'status' => 'success',

                'transaction_no' =>
                    'VIDADM'
                    . now()->format('YmdHis')
                    . rand(1000, 9999),

                'remark' =>
                    'Voter ID Service Received Amount'

            ]);

            clear_voter_id_session();

            DB::commit();

            return response()->json([

                'status' => true,

                'message' =>
                    'Voter ID Service Submitted Successfully.',

                'redirect_url' => route(

                    'retailer.voter-id.receiving',

                    $application->id

                )

            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(

                'VOTER ID FINAL SUBMIT ERROR',

                [

                    'message' => $e->getMessage(),

                    'file' => $e->getFile(),

                    'line' => $e->getLine(),

                    'user_id' => auth()->id(),

                    'session' => get_voter_id_session()

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

            $applications = VoterIdService::query()

                ->with([
                    'user.retailer',
                    'serviceDocuments',
                ])

                ->where('user_id', auth()->id())

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


                ->addColumn('document_status', function ($row) {

                    if ($row->serviceDocuments->isNotEmpty()) {

                        return '
                            <span class="badge bg-success">
                                <i class="fa fa-check-circle"></i>
                                Available
                            </span>
                        ';
                    }

                    return '
                        <span class="badge bg-warning text-dark">
                            <i class="fa fa-clock"></i>
                            Pending
                        </span>
                    ';
                })

                ->addColumn('action', function ($row) {

                    return '
                        <div class="d-flex gap-2">

                            <a
                                href="'.route(
                                    'retailer.voter-id.show',
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

                    'document_status',

                    'action',

                ])

                ->make(true);
        }

        return view(
            'retailer.voter-id.history'
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

            'retailer.voter-id.show',

            [

                'application' =>

                    $this->voterIdService
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

            'retailer.voter-id.acknowledgement',

            [

                'application' =>

                    $this->voterIdService
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

            'retailer.voter-id.print',

            [

                'application' =>

                    $this->voterIdService
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

        $this->voterIdService
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
