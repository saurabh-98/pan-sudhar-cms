<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\PanCorrectionApplication;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\ServiceDocument;

use ZipArchive;
use Illuminate\Support\Facades\Http;

class AdminPanCorrectionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PAN APPLICATION LIST
    |--------------------------------------------------------------------------
    */

    

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $applications = PanCorrectionApplication::query()

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

            /*
            |--------------------------------------------------------------------------
            | STATUS TAB FILTER
            |--------------------------------------------------------------------------
            */

            if ($request->filled('status_tab')) {

                switch ($request->status_tab) {

                    case 'new':

                        $applications
                            ->whereNull('assigned_to')
                            ->where('status', 'processing');

                        break;

                    case 'assigned':

                        $applications
                            ->whereNotNull('assigned_to')
                            ->whereNotIn('status', ['approved', 'completed', 'rejected']);

                        break;

                    case 'approved':

                        $applications->whereIn('status', ['approved', 'completed']);

                        break;

                    case 'rejected':

                        $applications->where('status', 'rejected');

                        break;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | ORDER
            |--------------------------------------------------------------------------
            */

            $applications->latest();

            return datatables()

                ->of($applications)

                ->addIndexColumn()

                ->addColumn('retailer', function ($row) {

                    return '

                        <div class="retailer-box">

                            <div class="retailer-avatar">

                                '

                                . strtoupper(
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

                                    '

                                    . ($row->user->name ?? 'N/A')

                                    . '

                                </div>

                            </div>

                        </div>

                    ';
                })

                ->addColumn('application_no', function ($row) {

                    return '

                        <div class="application-box">

                            <span class="application-id">

                                '

                                . $row->application_no .

                                '

                            </span>

                        </div>

                    ';
                })

                ->addColumn('applicant', function ($row) {

                    return '

                        <div class="applicant-box">

                            '

                            . $row->applicant_name .

                            '

                        </div>

                    ';
                })

                ->addColumn('status', function ($row) {

                    return $row->status_badge;
                })

                ->addColumn('payment', function ($row) {

                    return $row->payment_badge;
                })

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

                ->addColumn('action', function ($row) {

                    $buttons = '

                        <div class="d-flex gap-1">

                            <a href="'

                            . route(
                                'admin.pan-correction.show',
                                $row->id
                            )

                            . '"

                            class="btn btn-primary btn-sm">

                                <i class="fa fa-eye"></i>

                                View

                            </a>

                    ';

                    if ($row->status !== 'rejected') {

                        $buttons .= '

                            <form
                                action="'

                                . route(
                                    'admin.pan-correction.reject',
                                    $row->id
                                )

                                . '"
                                method="POST"
                                style="display:inline-block"
                                onsubmit="return confirm(\'Are you sure you want to reject this PAN Correction application?\')"
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
                    'status',
                    'payment',
                    'assigned_to',
                    'action',
                    'applicant'
                ])

                ->make(true);
        }

        return view('admin.pan-correction.index');
    }
    /*
    |--------------------------------------------------------------------------
    | SHOW APPLICATION
    |--------------------------------------------------------------------------
    */

    public function show(int $id): View
    {
        $application = PanCorrectionApplication::query()

            ->with([

                'user',
                'assignedUser',
                'documents.user'

            ])

            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | EXECUTIVE SECURITY
        |--------------------------------------------------------------------------
        */

        if(
            auth()->user()->hasRole('Executive') &&
            $application->assigned_to != auth()->id()
        )
        {
            abort(
                403,
                'Unauthorized Access'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        $users = User::where(
            'status',
            1
        )

        ->get()

        ->filter->hasRole('Executive');

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(

            'admin.pan-correction.show',

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

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | VALIDATION FAILED
        |--------------------------------------------------------------------------
        */

        if($validator->fails())
        {
            return response()->json([

                'status' => false,

                'message' => 'Validation Error',

                'errors' => $validator->errors()

            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | APPLICATION
        |--------------------------------------------------------------------------
        */

        $application = PanCorrectionApplication::findOrFail(
            $id
        );

        /*
        |--------------------------------------------------------------------------
        | ASSIGNED USER
        |--------------------------------------------------------------------------
        */

        $assignedUser = User::findOrFail(
            $request->assigned_to
        );

        /*
        |--------------------------------------------------------------------------
        | CHECK USER IS EXECUTIVE
        |--------------------------------------------------------------------------
        */

        if(!$assignedUser->hasRole('Executive'))
        {
            return response()->json([

                'status' => false,

                'message' => 'Only Executive users can be assigned.'

            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE APPLICATION
        |--------------------------------------------------------------------------
        */

        $application->update([

            'assigned_to' =>

                $request->assigned_to,

            'admin_remark' =>

                $request->remarks,

            'status' =>

                'Processing'

        ]);

        /*
        |--------------------------------------------------------------------------
        | SUCCESS RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => true,

            'message' => 'PAN Correction Application Assigned Successfully.',

            'data' => [

                'application_id' => $application->id,

                'assigned_to' => $assignedUser->name,

                'admin_remark' => $application->remarks,

                'status_label' => $application->status

            ]

        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | EXECUTIVE DOCUMENT UPLOAD
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

    $application = PanCorrectionApplication::findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | EXECUTIVE SECURITY
    |--------------------------------------------------------------------------
    */

    if ($application->assigned_to != auth()->id()) {

        abort(403);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK ALREADY COMPLETED
    |--------------------------------------------------------------------------
    */

    if ($application->status === 'Approved') {

        return response()->json([

            'status' => false,

            'message' => 'This application is already Approved.'

        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | FILE UPLOAD (LOCAL / CLOUDINARY HELPER)
    |--------------------------------------------------------------------------
    */

    $path = store_uploaded_file(

        $request->file('support_file'),

        'service-documents/pan-correction'

    );

    /*
    |--------------------------------------------------------------------------
    | SAVE DOCUMENT
    |--------------------------------------------------------------------------
    */

    ServiceDocument::create([

        'service_type'  => 'pan_correction',

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
    | ADMIN USER
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
    | ADD EXECUTIVE WALLET
    |--------------------------------------------------------------------------
    */

    $executive->increment(

        'wallet_balance',

        $commissionAmount

    );

    /*
    |--------------------------------------------------------------------------
    | DEDUCT ADMIN WALLET
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

            'PAN Correction Commission #'

            . $application->application_no

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

                'Executive PAN Correction Commission #'

                . $application->application_no

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

            'Receipt uploaded and commission added successfully.'

    ]);
}
    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD ALL DOCUMENTS
    |--------------------------------------------------------------------------
    */

   public function downloadDocuments(int $id)
{
    /*
    |--------------------------------------------------------------------------
    | APPLICATION
    |--------------------------------------------------------------------------
    */

    $application = PanCorrectionApplication::with(
        'documents'
    )->findOrFail($id);

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
    | ZIP FILE NAME
    |--------------------------------------------------------------------------
    */

    $zipFileName =
        'new-pan-application-documents-'
        .
        $application->application_no
        .
        '.zip';

    /*
    |--------------------------------------------------------------------------
    | TEMP ZIP PATH
    |--------------------------------------------------------------------------
    */

    $zipPath =
        sys_get_temp_dir()
        .
        DIRECTORY_SEPARATOR
        .
        uniqid('pan_', true)
        .
        '_'
        .
        $zipFileName;

    /*
    |--------------------------------------------------------------------------
    | CREATE ZIP
    |--------------------------------------------------------------------------
    */

    $zip = new ZipArchive();

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
    | APPLICATION DOCUMENTS
    |--------------------------------------------------------------------------
    */

    $documents = [

        'Photo' =>
            $application->photo,

        'Signature' =>
            $application->signature,

        'Aadhaar_Card' =>
            $application->aadhaar_card,

        'DOB_Proof' =>
            $application->dob_proof_file,

        'Supporting_Document' =>
            $application->supporting_document,

    ];

    foreach ($documents as $label => $file) {

        if (empty($file)) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | CLOUDINARY FILE
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $file,
                'http'
            )
        ) {

            try {

                $response = Http::timeout(30)
                    ->get($file);

                if (
                    $response->successful()
                ) {

                    $path =
                        parse_url(
                            $file,
                            PHP_URL_PATH
                        );

                    $extension =
                        pathinfo(
                            $path,
                            PATHINFO_EXTENSION
                        );

                    if (
                        empty($extension)
                    ) {
                        $extension = 'pdf';
                    }

                    $zip->addFromString(

                        $label
                        .
                        '.'
                        .
                        $extension,

                        $response->body()

                    );
                }

            } catch (\Throwable $e) {

                logger()->error(

                    'Cloudinary ZIP Error',

                    [

                        'file' =>
                            $file,

                        'error' =>
                            $e->getMessage()

                    ]

                );
            }

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | LOCAL FILE
        |--------------------------------------------------------------------------
        */

        $normalizedFile =
            normalize_file_path(
                $file
            );

        if (
            file_exists_custom(
                $normalizedFile
            )
        ) {

            $filePath =
                public_path(
                    $normalizedFile
                );

            $extension =
                pathinfo(
                    $filePath,
                    PATHINFO_EXTENSION
                );

            $zip->addFile(

                $filePath,

                $label
                .
                '.'
                .
                $extension

            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EXECUTIVE DOCUMENTS
    |--------------------------------------------------------------------------
    */

    foreach (
        $application->documents
        as $doc
    ) {

        if (
            empty(
                $doc->file_path
            )
        ) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | CLOUDINARY FILE
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $doc->file_path,
                'http'
            )
        ) {

            try {

                $response = Http::timeout(30)
                    ->get(
                        $doc->file_path
                    );

                if (
                    $response->successful()
                ) {

                    $path =
                        parse_url(
                            $doc->file_path,
                            PHP_URL_PATH
                        );

                    $extension =
                        pathinfo(
                            $path,
                            PATHINFO_EXTENSION
                        );

                    if (
                        empty($extension)
                    ) {
                        $extension = 'pdf';
                    }

                    $zip->addFromString(

                        'Executive_Document_'
                        .
                        $doc->id
                        .
                        '.'
                        .
                        $extension,

                        $response->body()

                    );
                }

            } catch (\Throwable $e) {

                logger()->error(

                    'Executive Cloudinary ZIP Error',

                    [

                        'file' =>
                            $doc->file_path,

                        'error' =>
                            $e->getMessage()

                    ]

                );
            }

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | LOCAL FILE
        |--------------------------------------------------------------------------
        */

        $normalizedFile =
            normalize_file_path(
                $doc->file_path
            );

        if (
            file_exists_custom(
                $normalizedFile
            )
        ) {

            $docPath =
                public_path(
                    $normalizedFile
                );

            $extension =
                pathinfo(
                    $docPath,
                    PATHINFO_EXTENSION
                );

            $zip->addFile(

                $docPath,

                'Executive_Document_'
                .
                $doc->id
                .
                '.'
                .
                $extension

            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CLOSE ZIP
    |--------------------------------------------------------------------------
    */

    $zip->close();

    /*
    |--------------------------------------------------------------------------
    | VALIDATE ZIP
    |--------------------------------------------------------------------------
    */

    if (
        !file_exists(
            $zipPath
        )
    ) {

        abort(
            500,
            'ZIP file was not generated.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */

    return response()
        ->download(
            $zipPath,
            $zipFileName,
            [
                'Content-Type' =>
                    'application/zip',
            ]
        )
        ->deleteFileAfterSend(true);
}

    public function reject($id)
    {
        try {

            DB::beginTransaction();

            $application = PanCorrectionApplication::lockForUpdate()
                ->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | PREVENT REJECTING APPROVED APPLICATION
            |--------------------------------------------------------------------------
            */

            if (
                strtolower($application->status) === 'approved'
            ) {

                DB::rollBack();

                return back()->with(
                    'error',
                    'Approved application cannot be rejected.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | PREVENT DOUBLE REJECTION
            |--------------------------------------------------------------------------
            */

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
            | REFUND WALLET
            |--------------------------------------------------------------------------
            */

            if (
                $application->wallet_deducted
                &&
                $application->amount > 0
            ) {

                $retailer = User::lockForUpdate()
                    ->findOrFail(
                        $application->user_id
                    );

                $admin = User::lockForUpdate()
                    ->role('Admin')
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
                | DEBIT ADMIN WALLET
                |--------------------------------------------------------------------------
                */

                $admin->decrement(
                    'wallet_balance',
                    $application->amount
                );

                WalletTransaction::create([

                    'user_id' => $admin->id,

                    'receiver_id' => $retailer->id,

                    'amount' => $application->amount,

                    'type' => 'debit',

                    'transaction_type' => 'pan_correction_refund',

                    'remark' =>
                        'Refund debited for PAN Correction Application No. '
                        . $application->application_no

                ]);

                /*
                |--------------------------------------------------------------------------
                | CREDIT RETAILER WALLET
                |--------------------------------------------------------------------------
                */

                $retailer->increment(
                    'wallet_balance',
                    $application->amount
                );

                WalletTransaction::create([

                    'user_id' => $retailer->id,

                    'receiver_id' => $admin->id,

                    'amount' => $application->amount,

                    'type' => 'credit',

                    'transaction_type' => 'pan_correction_refund',

                    'remark' =>
                        'Refund received for PAN Correction Application No. '
                        . $application->application_no

                ]);

                /*
                |--------------------------------------------------------------------------
                | MARK REFUNDED
                |--------------------------------------------------------------------------
                */

                $application->wallet_deducted = 0;

                $application->wallet_deducted_at = null;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE STATUS
            |--------------------------------------------------------------------------
            */

            $application->status = 'rejected';

            if (
                isset($application->admin_remark)
            ) {
                $application->admin_remark =
                    'Rejected by Admin';
            }

            $application->save();

            DB::commit();

            return back()->with(
                'success',
                'PAN Correction application rejected and amount refunded successfully.'
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