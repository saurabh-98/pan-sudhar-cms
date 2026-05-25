<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\PanApplication;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\ServiceDocument;

use ZipArchive;
use Illuminate\Support\Facades\File;

class AdminNewPanController extends Controller
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

            $applications = PanApplication::query()

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
                            'admin.pan.show',
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

        return view('admin.pan.index');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW APPLICATION
    |--------------------------------------------------------------------------
    */

    public function show(int $id): View
    {
        $application = PanApplication::query()

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

            'admin.pan.show',

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

        $application = PanApplication::findOrFail(
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

            'message' => 'PAN Application Assigned Successfully.',

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

    if(!auth()->user()->hasRole('Executive'))
    {
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

    $application = PanApplication::findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | EXECUTIVE SECURITY
    |--------------------------------------------------------------------------
    */

    if($application->assigned_to != auth()->id())
    {
        abort(403);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK ALREADY COMPLETED
    |--------------------------------------------------------------------------
    */

    if($application->status === 'Approved')
    {
        return response()->json([

            'status' => false,

            'message' => 'This application is already Approved.'

        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | FILE UPLOAD
    |--------------------------------------------------------------------------
    */

    $path = $request->file('support_file')

        ->store(

            'service-documents/pan',

            'public'

        );

    /*
    |--------------------------------------------------------------------------
    | SAVE DOCUMENT
    |--------------------------------------------------------------------------
    */

    ServiceDocument::create([

        'service_type' => 'pan',

        'service_id'   => $application->id,

        'user_id'      => auth()->id(),

        'file_path'    => $path,

        'remarks'      => $request->upload_remarks,

        'document_type'=> 'receipt'

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

    if($admin)
    {

        $admin->decrement(

            'wallet_balance',

            $commissionAmount

        );

    }

    /*
    |--------------------------------------------------------------------------
    | EXECUTIVE WALLET TRANSACTION
    |--------------------------------------------------------------------------
    */

    WalletTransaction::create([

        'user_id' => $executive->id,

        'amount' => $commissionAmount,

        'type' => 'credit',

        'remark' =>

            'PAN Service Commission #' .

            $application->application_no

    ]);

    /*
    |--------------------------------------------------------------------------
    | ADMIN WALLET TRANSACTION
    |--------------------------------------------------------------------------
    */

    if($admin)
    {

        WalletTransaction::create([

            'user_id' => $admin->id,

            'amount' => $commissionAmount,

            'type' => 'debit',

            'remark' =>

                'Executive PAN Commission #' .

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

            'Receipt uploaded and commission added successfully.'

    ]);
}
    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD ALL DOCUMENTS
    |--------------------------------------------------------------------------
    */

    public function downloadDocuments(
        int $id
    )
    {

        /*
        |--------------------------------------------------------------------------
        | APPLICATION
        |--------------------------------------------------------------------------
        */

        $application = PanApplication::findOrFail($id);

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
        | ZIP FILE NAME
        |--------------------------------------------------------------------------
        */

        $zipFileName =
            'pan-application-documents-' .
            $application->application_no .
            '.zip';

        /*
        |--------------------------------------------------------------------------
        | TEMP DIRECTORY
        |--------------------------------------------------------------------------
        */

        $tempPath =
            storage_path('app/temp');

        if(!File::exists($tempPath))
        {
            File::makeDirectory(

                $tempPath,

                0755,

                true

            );
        }

        /*
        |--------------------------------------------------------------------------
        | ZIP PATH
        |--------------------------------------------------------------------------
        */

        $zipPath =
            $tempPath . '/' . $zipFileName;

        /*
        |--------------------------------------------------------------------------
        | CREATE ZIP
        |--------------------------------------------------------------------------
        */

        $zip = new ZipArchive;

        if(
            $zip->open(

                $zipPath,

                ZipArchive::CREATE |
                ZipArchive::OVERWRITE

            ) === TRUE
        )
        {

            /*
            |--------------------------------------------------------------------------
            | DEFAULT DOCUMENTS
            |--------------------------------------------------------------------------
            */

            $documents = [

                'Photo' => $application->photo,

                'Signature' => $application->signature,

                'Aadhaar_Card' => $application->aadhaar_card,

                'Identity_Proof' => $application->identity_proof_file,

                'Address_Proof' => $application->address_proof_file,

                'DOB_Proof' => $application->dob_proof_file,

                'Supporting_Document' => $application->supporting_document

            ];

            /*
            |--------------------------------------------------------------------------
            | ADD DEFAULT DOCUMENTS
            |--------------------------------------------------------------------------
            */

            foreach($documents as $label => $file)
            {

                if($file)
                {

                    $filePath =
                        storage_path(
                            'app/public/' . $file
                        );

                    if(file_exists($filePath))
                    {

                        $extension =
                            pathinfo(
                                $filePath,
                                PATHINFO_EXTENSION
                            );

                        $zipFileDocumentName =

                            $label .
                            '.' .
                            $extension;

                        $zip->addFile(

                            $filePath,

                            $zipFileDocumentName

                        );

                    }

                }

            }

            /*
            |--------------------------------------------------------------------------
            | EXECUTIVE UPLOADED DOCUMENTS
            |--------------------------------------------------------------------------
            */

            foreach($application->documents as $doc)
            {

                $docPath = storage_path(

                    'app/public/' . $doc->file_path

                );

                if(file_exists($docPath))
                {

                    $extension = pathinfo(

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

            }

            /*
            |--------------------------------------------------------------------------
            | CLOSE ZIP
            |--------------------------------------------------------------------------
            */

            $zip->close();

        }

        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD RESPONSE
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