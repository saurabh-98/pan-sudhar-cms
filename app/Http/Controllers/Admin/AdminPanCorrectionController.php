<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\View\View;

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
        if($request->ajax())
        {
            
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

            if(auth()->user()->hasRole('Executive'))
            {
                $applications

                    ->whereNotNull('assigned_to')

                    ->where(
                        'assigned_to',
                        auth()->id()
                    );
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

                ->addColumn('retailer', function($row){

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

                ->addColumn('application_no', function($row){

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

                ->addColumn('applicant', function($row){

                    return '

                        <div class="applicant-box">

                            '

                            . $row->applicant_name .

                            '

                        </div>

                    ';
                })

                ->addColumn('status', function($row){

                    return $row->status_badge;
                })

                ->addColumn('payment', function($row){

                    return $row->payment_badge;
                })

                ->addColumn('assigned_to', function($row){

                    if($row->assignedUser)
                    {
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

                ->addColumn('action', function($row){

                    return '

                        <a href="'

                        . route(
                            'admin.pan-correction.show',
                            $row->id
                        )

                        . '"

                        class="view-btn">

                            <i class="fa fa-eye"></i>

                            View

                        </a>

                    ';
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

    $application = PanApplication::with(
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
}