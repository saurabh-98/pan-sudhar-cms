<?php

namespace App\Http\Controllers\Retailer\BankAccount;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\DTO\BankAccountServiceDTO;
use App\Http\Requests\StoreBankAccountServiceRequest;
use App\Services\BankAccountServiceService;
use App\Services\ServiceGuidelineService;

use App\Models\BankAccountService;
use App\Models\Charge;
use App\Models\User;
use App\Models\WalletTransaction;

use Yajra\DataTables\Facades\DataTables;


class BankAccountServiceController extends Controller
{
    public function __construct(
        protected BankAccountServiceService $bankAccountService,
        protected ServiceGuidelineService $serviceGuidelineService,
    ) {}


       private function getBankAccountCharge(string $serviceSlug): float
        {
            return (float) Charge::where('code', $serviceSlug)
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
        

        $session = get_bank_account_session();
        
        return view(
            'retailer.bank-account.create',
            [
                'serviceSlug' => $service,

                'serviceName' => $serviceName,

                'fields' => bank_account_fields($service),

                'bankAccountCharge' => $this->getBankAccountCharge($service),

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
        StoreBankAccountServiceRequest $request
    ): JsonResponse {

        try {

            $user = auth()->user();

            $bankAccountCharge =
                $this->getBankAccountCharge(
                    $request->service_slug
                );

            /*
            |--------------------------------------------------------------------------
            | CHARGE CHECK
            |--------------------------------------------------------------------------
            */

            if ($bankAccountCharge <= 0) {

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Bank Account service charge is not configured.'

                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | WALLET CHECK
            |--------------------------------------------------------------------------
            */

            if ($user->wallet_balance < $bankAccountCharge) {

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

            $dto = BankAccountServiceDTO::fromRequest(
                $request
            );

            /*
            |--------------------------------------------------------------------------
            | GENERATE PREVIEW
            |--------------------------------------------------------------------------
            */

            $preview =

                $this->bankAccountService
                    ->preview($dto);

            /*
            |--------------------------------------------------------------------------
            | ADD CHARGE
            |--------------------------------------------------------------------------
            */

            $preview['data']['bank_account_charge'] =
                $bankAccountCharge;

            /*
            |--------------------------------------------------------------------------
            | SAVE SESSION
            |--------------------------------------------------------------------------
            */

            save_bank_account_session(
                $preview
            );

            return response()->json([

                'status' => true,

                'message' =>
                    'Preview generated successfully.',

                'redirect_url' =>

                    route(
                        'retailer.bank-account.preview-page'
                    )

            ]);

        } catch (\Throwable $e) {

            Log::error(

                'BANK ACCOUNT PREVIEW ERROR',

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
        $preview = get_bank_account_session();

        if (
            empty($preview)
            ||
            ! isset($preview['data'])
        ) {

            return redirect()
                ->route('retailer.bank-account.history')
                ->with(
                    'error',
                    'Preview session expired.'
                );
        }

        return view(
            'retailer.bank-account.preview',
            [

                'data' => $preview['data'],

                'files' => $preview['files'] ?? [],

                'bankAccountCharge' =>
                    $preview['data']['bank_account_charge']
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

            $session = get_bank_account_session();

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

            $bankAccountCharge = (float) (

                $data['bank_account_charge']
                ??
                $this->getBankAccountCharge(
                    $data['service_slug']
                )

            );

            if ($bankAccountCharge <= 0) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Bank Account service charge is not configured.'

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
                $bankAccountCharge
            ) {

                DB::rollBack();

                return response()->json([

                    'status' => false,

                    'message' =>
                        'Insufficient wallet balance.'

                ], 422);
            }

            $application =

                $this->bankAccountService
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

                $bankAccountCharge

            );

            $admin->increment(

                'wallet_balance',

                $bankAccountCharge

            );

            $user->refresh();

            $admin->refresh();

            $application->update([

                'amount' => $bankAccountCharge,

                'wallet_deducted' => true,

                'wallet_deducted_at' => now(),

                'payment_status' => 'Paid',

                'status' => 'Processing',

            ]);

            WalletTransaction::create([

                'user_id' => $user->id,

                'amount' => $bankAccountCharge,

                'before_balance' => $retailerBefore,

                'after_balance' => $user->wallet_balance,

                'type' => 'debit',

                'status' => 'success',

                'transaction_no' =>
                    'BANK'
                    . now()->format('YmdHis')
                    . rand(1000, 9999),

                'remark' =>
                    'Bank Account Service Charge'

            ]);

            WalletTransaction::create([

                'user_id' => $admin->id,

                'amount' => $bankAccountCharge,

                'before_balance' => $adminBefore,

                'after_balance' => $admin->wallet_balance,

                'type' => 'credit',

                'status' => 'success',

                'transaction_no' =>
                    'BANKADM'
                    . now()->format('YmdHis')
                    . rand(1000, 9999),

                'remark' =>
                    'Bank Account Service Received Amount'

            ]);

            clear_bank_account_session();

            DB::commit();

            return response()->json([

                'status' => true,

                'message' =>
                    'Bank Account Service Submitted Successfully.',

                'redirect_url' => route(

                    'retailer.bank-account.receiving',

                    $application->id

                )

            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(

                'BANK ACCOUNT FINAL SUBMIT ERROR',

                [

                    'message' => $e->getMessage(),

                    'file' => $e->getFile(),

                    'line' => $e->getLine(),

                    'user_id' => auth()->id(),

                    'session' => get_bank_account_session()

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

            $applications = BankAccountService::query()

                ->with('user.retailer',
                'serviceDocuments')

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
                                'account_holder_name',
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

                  ->addColumn('document_status', function ($row) {

                    $document = $row->serviceDocuments->first();

                    if (
                        $document &&
                        file_exists_custom($document->file_path) &&
                        in_array(
                            strtolower($row->status),
                            ['approved', 'completed']
                        )
                    ) {

                        return '
                            <div class="d-flex gap-2 flex-wrap">

                                <a
                                    href="' . file_url($document->file_path) . '"
                                    target="_blank"
                                    class="btn btn-sm btn-success"
                                >
                                    <i class="fa fa-eye me-1"></i>
                                    View
                                </a>

                                <a
                                    href="' . file_url($document->file_path) . '"
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
                        $document &&
                        file_exists_custom($document->file_path)
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
                                href="'.route(
                                    'retailer.bank-account.show',
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
            'retailer.bank-account.history'
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

            'retailer.bank-account.show',

            [

                'application' =>

                    $this->bankAccountService
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

            'retailer.bank-account.acknowledgement',

            [

                'application' =>

                    $this->bankAccountService
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

            'retailer.bank-account.print',

            [

                'application' =>

                    $this->bankAccountService
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

        $this->bankAccountService
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
