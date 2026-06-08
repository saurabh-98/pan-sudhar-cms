<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\View\View;

use Illuminate\Support\Facades\File;

use ZipArchive;

use App\Models\ItrFile;
use App\Models\User;
use App\Models\ServiceDocument;
use App\Models\WalletTransaction;

class AdminItrController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | ITR LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {

        if($request->ajax())
        {

            $applications = ItrFile::query()

                ->with([

                    'user',

                    'assignedEmployee'

                ]);

            /*
            |--------------------------------------------------------------------------
            | EXECUTIVE CAN SEE ONLY ASSIGNED ITR
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

                ->addColumn('itr_no', function($row){

                    return '

                        <div class="application-box">

                            <span class="application-id">

                                ITR-'.$row->id.'

                            </span>

                        </div>

                    ';

                })

                ->addColumn('applicant', function($row){

                    return '

                        <div class="applicant-box">

                            '.$row->name.'

                        </div>

                    ';

                })

                ->addColumn('status', function($row){

                    if($row->status == 'completed')
                    {

                        return '

                            <span class="badge bg-success">

                                Completed

                            </span>

                        ';

                    }
                    elseif($row->status == 'approved')
                    {

                        return '

                            <span class="badge bg-success">

                                Approved

                            </span>

                        ';

                    }
                    elseif($row->status == 'pending')
                    {

                        return '

                            <span class="badge bg-warning text-dark">

                                Pending

                            </span>

                        ';

                    }
                    elseif($row->status == 'Processing')
                    {

                        return '

                            <span class="badge bg-primary">

                                Processing

                            </span>

                        ';

                    }

                    return '

                        <span class="badge bg-danger">

                            Rejected

                        </span>

                    ';

                })

                ->addColumn('assigned_to', function($row){

                    if($row->assignedEmployee)
                    {

                        return '

                            <span class="assigned-badge">

                                '

                                .$row->assignedEmployee->name.

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

                        <div class="d-flex gap-2">

                            <a href="'

                            . route(
                                'admin.itr.show',
                                $row->id
                            )

                            . '"

                            class="view-btn">

                                <i class="fa fa-eye"></i>

                                View

                            </a>

                        </div>

                    ';

                })

                ->rawColumns([

                    'retailer',

                    'itr_no',

                    'status',

                    'assigned_to',

                    'action',

                    'applicant'

                ])

                ->make(true);

        }

        return view('admin.itr.index');

    }



    /*
    |--------------------------------------------------------------------------
    | SHOW ITR
    |--------------------------------------------------------------------------
    */

    public function show(
        int $id
    ): View {

        $application = ItrFile::query()

            ->with([

                'user',

                'assignedEmployee',

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

        $users = User::query()

            ->where(
                'status',
                1
            )

            ->orderBy('name')

            ->get()

            ->filter(function ($user) {

                return $user->hasRole('Executive');

            });

        return view(

            'admin.itr.show',

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

        $application = ItrFile::findOrFail(
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

            'message' => 'ITR Assigned Successfully.',

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

        $application = ItrFile::findOrFail($id);

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
        | CHECK EXISTING RECEIPT
        |--------------------------------------------------------------------------
        */

        $alreadyUploaded = ServiceDocument::where(

            'service_type',
            'itr'

        )
        ->where(

            'service_id',
            $application->id

        )
        ->exists();

        if ($alreadyUploaded) {

            return response()->json([

                'status'  => false,

                'message' => 'Receipt already uploaded.'

            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | FILE UPLOAD
        |--------------------------------------------------------------------------
        */

        $path = store_uploaded_file(

            $request->file('support_file'),

            'service-documents/itr'

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

            'service_type'  => 'itr',

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

                'ITR Service Commission #ITR-' .
                $application->id

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

                    'Executive ITR Commission #ITR-' .
                    $application->id

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

                'ITR receipt uploaded successfully.',

            'file_url' => file_url($path)

        ]);
        

        }



    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD ALL DOCUMENTS
    |--------------------------------------------------------------------------
    */

    public function downloadDocuments(int $id)
    {
    $application = ItrFile::with('documents')
    ->findOrFail($id);


    if (
        auth()->user()->hasRole('Executive') &&
        $application->assigned_to != auth()->id()
    ) {
        abort(
            403,
            'Unauthorized Access'
        );
    }

    $zipFileName =
        'itr-documents-' .
        $application->id .
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
    | MAIN DOCUMENTS
    |--------------------------------------------------------------------------
    */

    $documents = [

        'aadhaar_front' =>
            $application->aadhaar_front,

        'aadhaar_back' =>
            $application->aadhaar_back,

        'pan_card' =>
            $application->pan_card,

    ];

    foreach ($documents as $name => $file) {

        if (!$file) {
            continue;
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | CLOUDINARY FILE
            |--------------------------------------------------------------------------
            */

            if (
                str_starts_with(
                    $file,
                    'http://'
                ) ||
                str_starts_with(
                    $file,
                    'https://'
                )
            ) {

                $contents =
                    @file_get_contents(
                        $file
                    );

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
                file_exists(
                    $filePath
                )
            ) {

                $zip->addFile(

                    $filePath,

                    basename(
                        $filePath
                    )

                );
            }

        } catch (\Throwable $e) {

            logger()->error(

                'Document ZIP error',

                [

                    'file' =>
                        $file,

                    'error' =>
                        $e->getMessage()

                ]

            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EXECUTIVE DOCUMENTS
    |--------------------------------------------------------------------------
    */

    foreach ($application->documents as $doc) {

        $file =
            $doc->file_path;

        if (!$file) {
            continue;
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | CLOUDINARY FILE
            |--------------------------------------------------------------------------
            */

            if (
                str_starts_with(
                    $file,
                    'http://'
                ) ||
                str_starts_with(
                    $file,
                    'https://'
                )
            ) {

                $contents =
                    @file_get_contents(
                        $file
                    );

                if ($contents !== false) {

                    $zip->addFromString(

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

            /*
            |--------------------------------------------------------------------------
            | LOCAL FILE
            |--------------------------------------------------------------------------
            */

            $docPath =
                public_path(
                    $file
                );

            if (
                file_exists(
                    $docPath
                )
            ) {

                $zip->addFile(

                    $docPath,

                    basename(
                        $docPath
                    )

                );
            }

        } catch (\Throwable $e) {

            logger()->error(

                'Executive document ZIP error',

                [

                    'file' =>
                        $file,

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

}