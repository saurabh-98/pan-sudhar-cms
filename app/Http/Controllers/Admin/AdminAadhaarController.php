<?php

namespace App\Http\Controllers\Admin;

use ZipArchive;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AadhaarService;
use App\Models\ServiceDocument;
use App\Models\WalletTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;

class AdminAadhaarController extends Controller
{

    

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $applications = AadhaarService::query()

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

                    return '

                        <a href="'

                        . route(
                            'admin.aadhaar.show',
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

        return view('admin.aadhaar.index');
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

        $application = AadhaarService::query()

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

            'admin.aadhaar.show',

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

        $application = AadhaarService::findOrFail($id);

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
            'aadhaar'

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

            'service-documents/aadhaar'

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

            'service_type'  => 'aadhaar',

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

                'Aadhaar Service Commission #' .
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

                    'Executive Aadhaar Commission #' .
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

                'Aadhaar receipt uploaded successfully.',

            'file_url' => file_url($path)

        ]);
    }


    public function downloadDocuments(int $id)
    {
        $application = AadhaarService::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | EXECUTIVE SECURITY
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
        | ZIP NAME
        |--------------------------------------------------------------------------
        */

        $zipFileName =
            'aadhaar-documents-' .
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
        | CUSTOMER DOCUMENTS
        |--------------------------------------------------------------------------
        */

        foreach (
            ($application->documents ?? [])
            as $name => $file
        ) {

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

                    $response = Http::timeout(30)
                        ->get($file);

                    if (
                        $response->successful()
                    ) {

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

                            $response->body()

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

                        $name .
                        '.' .
                        $extension

                    );
                }

            } catch (\Throwable $e) {

                logger()->error(

                    'Aadhaar Document ZIP Error',

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

        $serviceDocuments = ServiceDocument::query()

            ->where(
                'service_type',
                'aadhaar'
            )

            ->where(
                'service_id',
                $application->id
            )

            ->get();

        foreach ($serviceDocuments as $doc) {

            $file = $doc->file_path;

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

                    $response =
                        Http::timeout(30)
                        ->get($file);

                    if (
                        $response->successful()
                    ) {

                        $extension =
                            pathinfo(
                                parse_url(
                                    $file,
                                    PHP_URL_PATH
                                ),
                                PATHINFO_EXTENSION
                            );

                        $zip->addFromString(

                            'Executive_Document_' .
                            $doc->id .
                            '.' .
                            ($extension ?: 'pdf'),

                            $response->body()

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

                        'Executive_Document_' .
                        $doc->id .
                        '.' .
                        $extension

                    );
                }

            } catch (\Throwable $e) {

                logger()->error(

                    'Executive Aadhaar ZIP Error',

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
        | CLOSE ZIP
        |--------------------------------------------------------------------------
        */

        $zip->close();

        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        return response()
            ->download(
                $zipPath,
                $zipFileName
            )
            ->deleteFileAfterSend(true);
    }
}