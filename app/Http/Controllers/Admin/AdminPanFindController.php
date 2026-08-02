<?php

namespace App\Http\Controllers\Admin;

use ZipArchive;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PanFindHistory;
use App\Models\ServiceDocument;
use App\Models\Charge;
use App\Models\WalletTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Services\FinancialSettlementService;


class AdminPanFindController extends Controller
{


    protected FinancialSettlementService $financialSettlement;

        public function __construct(

            FinancialSettlementService $financialSettlement

        ){

            $this->financialSettlement = $financialSettlement;

        }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $applications = PanFindHistory::query()

                ->with([
                    'user',
                    'assignedUser'
                ]);

            /*
            |--------------------------------------------------------------------------
            | EXECUTIVE CAN SEE ONLY ASSIGNED APPLICATIONS
            |--------------------------------------------------------------------------
            */

            if (auth()->user()->hasRole('Executive')) {

                $applications

                    ->whereNotNull('assigned_to')

                    ->where(
                        'assigned_to',
                        auth()->id()
                    );
            }

            $applications->latest();

            return datatables()

                ->of($applications)

                ->addIndexColumn()

                /*
                |--------------------------------------------------------------------------
                | RETAILER
                |--------------------------------------------------------------------------
                */

                ->addColumn('retailer', function ($row) {

                    return '

                        <div class="retailer-box">

                            <div class="retailer-avatar">

                                ' .

                                strtoupper(
                                    substr(
                                        $row->user->name ?? 'N',
                                        0,
                                        1
                                    )
                                )

                                . '

                            </div>

                            <div>

                                <div class="retailer-name">

                                    ' .

                                    ($row->user->name ?? 'N/A')

                                    . '

                                </div>

                            </div>

                        </div>

                    ';
                })

                /*
                |--------------------------------------------------------------------------
                | APPLICATION NUMBER
                |--------------------------------------------------------------------------
                */

                ->addColumn('application_no', function ($row) {

                    return '

                        <div class="application-box">

                            <span class="application-id">

                                ' .

                                $row->application_no .

                                '

                            </span>

                        </div>

                    ';
                })

                /*
                |--------------------------------------------------------------------------
                | CUSTOMER
                |--------------------------------------------------------------------------
                */

                
                /*
                |--------------------------------------------------------------------------
                | MOBILE
                |--------------------------------------------------------------------------
                */

               ->addColumn('aadhaar_number', function ($row) {
                    return $row->aadhaar_number ?? '-';
                })

                /*
                |--------------------------------------------------------------------------
                | SERVICE
                |--------------------------------------------------------------------------
                */

                ->addColumn('service', function ($row) {

                    return '

                        <span class="badge bg-info">

                            '

                            . e($row->service_name) .

                            '

                        </span>

                    ';
                })

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                ->addColumn('status', function ($row) {

                    return $row->status_badge;
                })

                /*
                |--------------------------------------------------------------------------
                | PAYMENT
                |--------------------------------------------------------------------------
                */

                ->addColumn('payment', function ($row) {

                    return $row->payment_badge;
                })

                /*
                |--------------------------------------------------------------------------
                | ASSIGNED USER
                |--------------------------------------------------------------------------
                */

                ->addColumn('assigned_to', function ($row) {

                    if ($row->assignedUser) {

                        return '

                            <span class="assigned-badge">

                                '

                                . $row->assignedUser->name .

                                '

                            </span>

                        ';
                    }

                    return '

                        <span class="not-assigned-badge">

                            Not Assigned

                        </span>

                    ';
                })

                /*
                |--------------------------------------------------------------------------
                | DATE
                |--------------------------------------------------------------------------
                */

                ->addColumn('created_at', function ($row) {

                    return '

                        <div>

                            <div class="fw-semibold">

                                '

                                . $row->created_at->format('d M Y')

                                . '

                            </div>

                            <small class="text-muted">

                                '

                                . $row->created_at->format('h:i A')

                                . '

                            </small>

                        </div>

                    ';
                })

                /*
                |--------------------------------------------------------------------------
                | ACTION
                |--------------------------------------------------------------------------
                */

                ->addColumn('action', function ($row) {

                    $buttons = '

                        <div class="d-flex gap-2">

                            <a href="'

                            . route(
                                'admin.pan-find.show',
                                $row->id
                            )

                            . '"

                            class="btn btn-primary btn-sm">

                                <i class="fa fa-eye"></i>

                                View

                            </a>

                    ';

                    if (
                        !in_array(
                            strtolower($row->status),
                            ['approved', 'completed', 'rejected']
                        )
                    ) {

                        $buttons .= '

                            <form
                                action="'

                                . route(
                                    'admin.pan-find.reject',
                                    $row->id
                                )

                                . '"
                                method="POST"
                                style="display:inline-block"
                                onsubmit="return confirm(\'Are you sure you want to reject this application?\')"
                            >

                                '

                                . csrf_field()

                                . '

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                >

                                    <i class="fa fa-times"></i>

                                    Reject

                                </button>

                            </form>

                        ';
                    }

                    $buttons .= '

                        </div>

                    ';

                    return $buttons;
                })

                ->rawColumns([

                    'retailer',
                    'application_no',
                    'aadhaar_number',
                    'service',
                    'status',
                    'payment',
                    'assigned_to',
                    'created_at',
                    'action'

                ])

                ->make(true);
        }

        return view('admin.pan-find.index');
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW APPLICATION
    |--------------------------------------------------------------------------
    */

    public function show(int $id): View
    {
        /*
        |--------------------------------------------------------------------------
        | APPLICATION
        |--------------------------------------------------------------------------
        */

        $application = PanFindHistory::query()

            ->with([

                'user',

                'assignedUser',

                'serviceDocuments.user'

            ])

            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | EXECUTIVE SECURITY
        |--------------------------------------------------------------------------
        */

        if (

            auth()->user()->hasRole('Executive')

            &&

            $application->assigned_to != auth()->id()

        ) {

            abort(

                403,

                'Unauthorized Access'

            );
        }

        /*
        |--------------------------------------------------------------------------
        | EXECUTIVE USERS
        |--------------------------------------------------------------------------
        */

        $users = User::query()

            ->where(
                'status',
                1
            )

            ->orderBy(
                'name'
            )

            ->get()

            ->filter(function ($user) {

                return $user->hasRole(
                    'Executive'
                );

            });

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(

            'admin.pan-find.show',

            compact(

                'application',

                'users'

            )

        );
    }


    /*
    |--------------------------------------------------------------------------
    | ASSIGN APPLICATION
    |--------------------------------------------------------------------------
    */

    public function assign(

        Request $request,

        int $id

    ) {

        $validator = validator(

            $request->all(),

            [

                'assigned_to' => [

                    'required',

                    'exists:users,id'

                ],

                'remarks' => [

                    'nullable',

                    'string',

                    'max:1000'

                ]

            ]

        );

        if($validator->fails())
        {
            return response()->json([

                'status' => false,

                'message' => 'Validation Error',

                'errors' => $validator->errors()

            ], 422);
        }

        $application = PanFindHistory::findOrFail(
            $id
        );

        $assignedUser = User::findOrFail(
            $request->assigned_to
        );

        if(!$assignedUser->hasRole('Executive'))
        {
            return response()->json([

                'status' => false,

                'message' => 'Only Executive users can be assigned.'

            ], 422);
        }

        $application->update([

            'assigned_to' =>

                $request->assigned_to,

            'remarks' =>

                $request->remarks,

            'assigned_at' =>

                now(),

           

        ]);

        return response()->json([

            'status' => true,

            'message' => 'PAN Find Service Assigned Successfully.',

            'data' => [

                'application_id' => $application->id,

                'assigned_to' => $assignedUser->name,

                'remarks' => $application->remarks

               

            ]

        ], 200);

    }



    /*
    |--------------------------------------------------------------------------
    | EXECUTIVE RECEIPT UPLOAD
    |--------------------------------------------------------------------------
    */

    public function uploadDocument(Request $request, int $id)
    {
        /*
        |--------------------------------------------------------------------------
        | ONLY EXECUTIVE
        |--------------------------------------------------------------------------
        */

        abort_unless(auth()->user()->hasRole('Executive'), 403);

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'father_name' => [
                'required',
                'string',
                'max:255',
            ],

            'pan_number' => [
                'required',
                'string',
                'size:10',
                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            ],

            'dob' => [
                'required',
                'date',
            ],

            'gender' => [
                'required',
                'in:Male,Female,Other',
            ],

        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | APPLICATION
            |--------------------------------------------------------------------------
            */

            $application = PanFindHistory::findOrFail($id);

            abort_if(
                $application->assigned_to != auth()->id(),
                403
            );

            /*
            |--------------------------------------------------------------------------
            | UPDATE APPLICATION
            |--------------------------------------------------------------------------
            */

            $application->update([

                'full_name'    => $request->full_name,

                'father_name'  => $request->father_name,

                'pan_number'   => strtoupper($request->pan_number),

                'dob'          => $request->dob,

                'gender'       => $request->gender,

                'status'       => 'Approved',

                'admin_remark' => 'PAN details verified and submitted by Executive.',

            ]);

            /*
            |--------------------------------------------------------------------------
            | FINANCIAL SETTLEMENT
            |--------------------------------------------------------------------------
            */

            $this->financialSettlementService->settle(

                serviceType: 'pan_find',

                serviceId: $application->id,

                referenceNo: $application->application_no,

                serviceAmount: (float) ($application->amount ?? $application->charge ?? 0),

                chargeCode: 'pan_find',

                retailer: $application->user,

                executive: auth()->user(),

                remarks: 'PAN Find Settlement'

            );

            DB::commit();

            return response()->json([

                'status' => true,

                'message' => 'PAN details submitted successfully.',

            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            \Log::error('PAN Find Submission Error', [

                'application_id' => $id,

                'executive_id' => auth()->id(),

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

                'trace' => $e->getTraceAsString(),

            ]);

            return response()->json([

                'status' => false,

                'message' => 'Something went wrong while processing the PAN details.',

            ], 500);

        }
    }

    public function downloadDocuments(int $id)
    {
        $application = PanFindHistory::with([
            'serviceDocuments.user'
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | EXECUTIVE ACCESS CHECK
        |--------------------------------------------------------------------------
        */

        if (
            auth()->user()->hasRole('Executive') &&
            $application->assigned_to != auth()->id()
        ) {
            abort(
                403,
                'Unauthorized Access'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ZIP SETUP
        |--------------------------------------------------------------------------
        */

        $zipFileName =
            'pan-find-documents-' .
            $application->application_no .
            '.zip';

        $tempDirectory =
            storage_path('app/temp');

        if (!File::exists($tempDirectory)) {

            File::makeDirectory(
                $tempDirectory,
                0755,
                true
            );
        }

        $zipPath =
            $tempDirectory .
            '/' .
            $zipFileName;

        $zip = new ZipArchive;

        if (
            $zip->open(
                $zipPath,
                ZipArchive::CREATE |
                ZipArchive::OVERWRITE
            ) !== true
        ) {
            abort(
                500,
                'Unable to create ZIP file.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAN FIND APPLICATION DOCUMENTS
        |--------------------------------------------------------------------------
        */

        foreach (
            ($application->documents ?? [])
            as $name => $file
        ) {

            if (empty($file)) {
                continue;
            }

            try {

                /*
                |--------------------------------------------------------------------------
                | CLOUDINARY / REMOTE FILE
                |--------------------------------------------------------------------------
                */

                if (
                    str_starts_with($file, 'http://')
                    ||
                    str_starts_with($file, 'https://')
                ) {

                    $contents =
                        @file_get_contents($file);

                    if ($contents !== false) {

                        $extension =
                            pathinfo(
                                parse_url(
                                    $file,
                                    PHP_URL_PATH
                                ),
                                PATHINFO_EXTENSION
                            );

                        $zip->addFromString(

                            $name .
                            '.' .
                            ($extension ?: 'jpg'),

                            $contents
                        );
                    }

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | LOCAL FILE
                |--------------------------------------------------------------------------
                */

                $filePath =
                    public_path($file);

                if (
                    file_exists($filePath)
                ) {

                    $zip->addFile(

                        $filePath,

                        basename($filePath)
                    );
                }

            } catch (\Throwable $e) {

                logger()->error(

                    'PAN-FIND DOCUMENT ZIP ERROR',

                    [

                        'file' => $file,

                        'error' =>
                            $e->getMessage()

                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | EXECUTIVE UPLOADED DOCUMENTS
        |--------------------------------------------------------------------------
        */

        foreach (
            $application->serviceDocuments ?? []
            as $doc
        ) {

            $file =
                $doc->file_path;

            if (!$file) {
                continue;
            }

            try {

                if (
                    str_starts_with($file, 'http://')
                    ||
                    str_starts_with($file, 'https://')
                ) {

                    $contents =
                        @file_get_contents($file);

                    if ($contents !== false) {

                        $zip->addFromString(

                            'executive_' .
                            basename(
                                parse_url(
                                    $file,
                                    PHP_URL_PATH
                                )
                            ),

                            $contents
                        );
                    }

                    continue;
                }

                $filePath =
                    public_path($file);

                if (
                    file_exists($filePath)
                ) {

                    $zip->addFile(

                        $filePath,

                        'executive_' .
                        basename($filePath)

                    );
                }

            } catch (\Throwable $e) {

                logger()->error(

                    'PAN-FIND EXECUTIVE DOCUMENT ZIP ERROR',

                    [

                        'file' => $file,

                        'error' =>
                            $e->getMessage()

                    ]
                );
            }
        }

        $zip->close();

        return response()

            ->download(

                $zipPath,

                $zipFileName

            )

            ->deleteFileAfterSend(true);
    }


    public function reject(int $id)
    {
        try {

            DB::beginTransaction();

            $application = PanFindHistory::lockForUpdate()
                ->findOrFail($id);

            if (
                in_array(
                    strtolower($application->status),
                    ['approved', 'completed']
                )
            ) {
                DB::rollBack();

                return back()->with(
                    'error',
                    'Approved or completed application cannot be rejected.'
                );
            }

            if (
                strtolower($application->status) === 'rejected'
            ) {
                DB::rollBack();

                return back()->with(
                    'error',
                    'Application already rejected.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | REFUND PROCESS
            |--------------------------------------------------------------------------
            */

            if (
                $application->wallet_deducted &&
                $application->amount > 0
            ) {

                $retailer = User::lockForUpdate()
                    ->findOrFail(
                        $application->user_id
                    );

                $admin = User::lockForUpdate()
                    ->role('admin')
                    ->first();

                if (!$admin) {
                    throw new \Exception(
                        'Admin account not found.'
                    );
                }

                if (
                    $admin->wallet_balance <
                    $application->amount
                ) {
                    throw new \Exception(
                        'Admin wallet balance is insufficient.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | DEBIT ADMIN
                |--------------------------------------------------------------------------
                */

                $admin->decrement(
                    'wallet_balance',
                    $application->amount
                );

                WalletTransaction::create([

                    'user_id'          => $admin->id,

                    'receiver_id'      => $retailer->id,

                    'amount'           => $application->amount,

                    'type'             => 'debit',

                    'transaction_type' => 'pan-find_refund',

                    'remark'           =>
                        'Refund amount debited for PAN Find Service Application No. '
                        . $application->application_no

                ]);

                /*
                |--------------------------------------------------------------------------
                | CREDIT RETAILER
                |--------------------------------------------------------------------------
                */

                $retailer->increment(
                    'wallet_balance',
                    $application->amount
                );

                WalletTransaction::create([

                    'user_id'          => $retailer->id,

                    'receiver_id'      => $admin->id,

                    'amount'           => $application->amount,

                    'type'             => 'credit',

                    'transaction_type' => 'pan-find_refund',

                    'remark'           =>
                        'Refund received for PAN Find Service Application No. '
                        . $application->application_no

                ]);

                $application->wallet_deducted = 0;

                $application->wallet_deducted_at = null;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE STATUS
            |--------------------------------------------------------------------------
            */

            $application->status = 'Rejected';

            $application->admin_remark =
                'Rejected by Admin';

            $application->save();

            DB::commit();

            return back()->with(
                'success',
                'PAN Find Service application rejected and amount refunded successfully.'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

}