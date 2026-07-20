<?php

namespace App\Http\Controllers\Admin;

use ZipArchive;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\OtherService;
use App\Models\ServiceDocument;
use App\Models\WalletTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;


class AdminOtherServiceController extends Controller
{

    

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $applications = OtherService::query()

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

                ->addColumn('applicant', function ($row) {

                    $customerName =

                        $row->getField(
                            'customer_name',
                            $row->getField(
                                'child_name',
                                'N/A'
                            )
                        );

                    return '

                        <div class="applicant-box">

                            ' . e($customerName) . '

                        </div>

                    ';
                })

                /*
                |--------------------------------------------------------------------------
                | MOBILE
                |--------------------------------------------------------------------------
                */

                ->addColumn('mobile', function ($row) {

                    return $row->getField(
                        'mobile',
                        '-'
                    );
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
                                'admin.other-service.show',
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
                                    'admin.voter-id.reject',
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
                    'applicant',
                    'service',
                    'status',
                    'payment',
                    'assigned_to',
                    'created_at',
                    'action'

                ])

                ->make(true);
        }

        return view('admin.other-service.index');
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW ITR
    |--------------------------------------------------------------------------
    */

    

   


    public function show(int $id): View
    {
        /*
        |--------------------------------------------------------------------------
        | APPLICATION
        |--------------------------------------------------------------------------
        */

        $application = OtherService::query()

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

            'admin.other-service.show',

            compact(

                'application',

                'users'

            )

        );
    }


    /*
    |--------------------------------------------------------------------------
    | ASSIGN ITR
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

        $application = OtherService::findOrFail(
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

            'status' =>

                'approved'

        ]);

        return response()->json([

            'status' => true,

            'message' => 'Other Service Assigned Successfully.',

            'data' => [

                'application_id' => $application->id,

                'assigned_to' => $assignedUser->name,

                'remarks' => $application->remarks,

                'status' => $application->status

            ]

        ], 200);

    }



    /*
    |--------------------------------------------------------------------------
    | EXECUTIVE RECEIPT UPLOAD
    |--------------------------------------------------------------------------
    */

    public function uploadDocument(
        Request $request,
        int $id
    )
    {
        /*
        |--------------------------------------------------------------------------
        | ONLY EXECUTIVE
        |--------------------------------------------------------------------------
        */

        if (!auth()->user()->hasRole('Executive')) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'support_file' => [

                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
                'max:5120'

            ],

            'upload_remarks' => [

                'nullable',
                'string',
                'max:1000'

            ]

        ]);

        /*
        |--------------------------------------------------------------------------
        | APPLICATION
        |--------------------------------------------------------------------------
        */

        $application = OtherService::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        if ($application->assigned_to != auth()->id()) {
            abort(403);
        }

      

        /*
        |--------------------------------------------------------------------------
        | FILE UPLOAD
        |--------------------------------------------------------------------------
        */

        $path = store_uploaded_file(

            $request->file('support_file'),

            'service-documents/other-service'

        );

        if (!$path) {

            return response()->json([

                'status'  => false,

                'message' => 'File upload failed.'

            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE DOCUMENT
        |--------------------------------------------------------------------------
        */

        ServiceDocument::create([

            'service_type'  => 'other-sercice',

            'service_id'    => $application->id,

            'user_id'       => auth()->id(),

            'file_path'     => $path,

            'remarks'       => $request->upload_remarks,

            'document_type' => 'receipt'

        ]);

        /*
        |--------------------------------------------------------------------------
        | EXECUTIVE
        |--------------------------------------------------------------------------
        */

        $executive = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        $admin = User::role('admin')->first();

        /*
        |--------------------------------------------------------------------------
        | COMMISSION
        |--------------------------------------------------------------------------
        */

        $commissionAmount = 50;

        /*
        |--------------------------------------------------------------------------
        | CREDIT EXECUTIVE
        |--------------------------------------------------------------------------
        */

        $executive->increment(

            'wallet_balance',

            $commissionAmount

        );

        /*
        |--------------------------------------------------------------------------
        | DEBIT ADMIN
        |--------------------------------------------------------------------------
        */

        if ($admin) {

            $admin->decrement(

                'wallet_balance',

                $commissionAmount

            );
        }

        /*
        |--------------------------------------------------------------------------
        | EXECUTIVE TRANSACTION
        |--------------------------------------------------------------------------
        */

        WalletTransaction::create([

            'user_id' => $executive->id,

            'amount'  => $commissionAmount,

            'type'    => 'credit',

            'remark'  =>

                'Other Service Commission #' .
                $application->application_no

        ]);

        /*
        |--------------------------------------------------------------------------
        | ADMIN TRANSACTION
        |--------------------------------------------------------------------------
        */

        if ($admin) {

            WalletTransaction::create([

                'user_id' => $admin->id,

                'amount'  => $commissionAmount,

                'type'    => 'debit',

                'remark'  =>

                    'Executive Other Service Commission #' .
                    $application->application_no

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS
        |--------------------------------------------------------------------------
        */

        $application->update([

            'status' => 'Approved'

        ]);

        /*
        |--------------------------------------------------------------------------
        | SUCCESS RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => true,

            'message' =>

                'Other Service receipt uploaded successfully.',

            'file_url' => file_url($path)

        ]);
    }


   

    public function downloadDocuments(int $id)
    {
        $application = OtherService::with([
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
            'other-documents-' .
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
        | AADHAAR APPLICATION DOCUMENTS
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

                    'OTHER-SERVICE DOCUMENT ZIP ERROR',

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

                    'OTHER-SERVICE EXECUTIVE DOCUMENT ZIP ERROR',

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

    $application = OtherService::lockForUpdate()
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

            'transaction_type' => 'voter-id_refund',

            'remark'           =>
                'Refund amount debited for Voter-Id Service Application No. '
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

            'transaction_type' => 'bank_account_refund',

            'remark'           =>
                'Refund received for Other Service Application No. '
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
        'Other Service application rejected and amount refunded successfully.'
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