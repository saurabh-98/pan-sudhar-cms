<?php

namespace App\Http\Controllers\Retailer\Pan;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Yajra\DataTables\Facades\DataTables;

use App\Models\State;
use App\Models\District;
use App\Models\Retailer;
use App\Models\ServiceDocument;
use App\Models\PanCorrectionApplication;

class PanCorrectionController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | APPLY PAGE
    |--------------------------------------------------------------------------
    */

    public function create(Request $request): View
    {
        
        return view(

            'retailer.pan-correction.apply',

            [

                'states' => State::latest()->get(),

                'files'  => session(

                    'uploaded_files',

                    []

                ),

                'data'   => $request->all()

            ]

        );

    }


    /*
    |--------------------------------------------------------------------------
    | PREVIEW
    |--------------------------------------------------------------------------
    */

    public function preview(
    Request $request
    ): JsonResponse
    {

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([

            /*
            |--------------------------------------------------------------------------
            | APPLICANT
            |--------------------------------------------------------------------------
            */

            'first_name' =>

                'required|string|max:255',

            'middle_name' =>

                'nullable|string|max:255',

            'last_name' =>

                'required|string|max:255',

            'old_pan_number' => [

                'required',

                'string',

                'size:10',

                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'

            ],

            'gender' =>

                'required|string',

            /*
            |--------------------------------------------------------------------------
            | PARENTS
            |--------------------------------------------------------------------------
            */

            'father_first_name' =>

                'required|string|max:255',

            'father_middle_name' =>

                'nullable|string|max:255',

            'father_last_name' =>

                'required|string|max:255',

            'mother_first_name' =>

                'required|string|max:255',

            'mother_middle_name' =>

                'nullable|string|max:255',

            'mother_last_name' =>

                'required|string|max:255',

            /*
            |--------------------------------------------------------------------------
            | CONTACT
            |--------------------------------------------------------------------------
            */

            'mobile_no' =>

                'required|digits:10',

            'email' =>

                'required|email',

            /*
            |--------------------------------------------------------------------------
            | ADDRESS
            |--------------------------------------------------------------------------
            */

            'house_no' =>

                'required|string|max:255',

            'village' =>

                'required|string|max:255',

            'post_office' =>

                'required|string|max:255',

            'area' =>

                'required|string|max:255',

            'state' =>

                'required|exists:states,id',

            'district' =>

                'required|exists:districts,id',

            'pincode' =>

                'required|digits:6',

            /*
            |--------------------------------------------------------------------------
            | DOB
            |--------------------------------------------------------------------------
            */

            'dob' =>

                'required|date',

            'confirm_dob' =>

                'required|same:dob',

            /*
            |--------------------------------------------------------------------------
            | AADHAAR
            |--------------------------------------------------------------------------
            */

            'aadhaar_no' =>

                'required|digits:12',

            'aadhaar_name' =>

                'required|string|max:255',

            /*
            |--------------------------------------------------------------------------
            | FILES
            |--------------------------------------------------------------------------
            */

            'photo' =>

                'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',

            'signature' =>

                'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',

            'aadhaar_card' =>

                'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',

            'identity_proof_file' =>

                'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',

            'address_proof_file' =>

                'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',

            'dob_proof_file' =>

                'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',

            'supporting_document' =>

                'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',

        ]);

        /*
        |--------------------------------------------------------------------------
        | STORE FILES
        |--------------------------------------------------------------------------
        */

        $uploadedFiles = [];

        $fileFields = [

            'photo',

            'signature',

            'aadhaar_card',

            'identity_proof_file',

            'address_proof_file',

            'dob_proof_file',

            'supporting_document'

        ];

        foreach($fileFields as $field)
        {

            /*
            |--------------------------------------------------------------------------
            | NEW FILE
            |--------------------------------------------------------------------------
            */

            if($request->hasFile($field))
            {

                $uploadedFiles[$field] =

                    $request->file($field)

                    ->store(

                        'pan-correction-documents',

                        'public'

                    );

            }

            /*
            |--------------------------------------------------------------------------
            | EXISTING FILE
            |--------------------------------------------------------------------------
            */

            elseif(
                $request->existing_files &&
                isset(
                    $request->existing_files[$field]
                )
            )
            {

                $uploadedFiles[$field] =

                    $request->existing_files[$field];

            }

        }

        /*
        |--------------------------------------------------------------------------
        | REMOVE FILE OBJECTS FROM SESSION
        |--------------------------------------------------------------------------
        */

        $formData = $request->except([

            '_token',

            'photo',

            'signature',

            'aadhaar_card',

            'identity_proof_file',

            'address_proof_file',

            'dob_proof_file',

            'supporting_document'

        ]);

        /*
        |--------------------------------------------------------------------------
        | STORE SESSION
        |--------------------------------------------------------------------------
        */

        session([

            'pan_correction_preview' => $formData,

            'uploaded_files' => $uploadedFiles

        ]);

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => true,

            'redirect_url' =>

                route(

                    'retailer.pan-correction.preview-page'

                )

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW PAGE
    |--------------------------------------------------------------------------
    */

    public function previewPage(): View
    {

        if(
            !session()->has(
                'pan_correction_preview'
            )
        )
        {

            return redirect()->route(

                'retailer.pan-correction.apply'

            );

        }

        return view(

            'retailer.pan-correction.preview',

            [

                'data' => session(

                    'pan_correction_preview'

                ),

                'files' => session(

                    'uploaded_files',

                    []

                )

            ]

        );

    }


    /*
    |--------------------------------------------------------------------------
    | FINAL SUBMIT
    |--------------------------------------------------------------------------
    */

    public function store(
    Request $request
): JsonResponse
{

    /*
    |--------------------------------------------------------------------------
    | SESSION CHECK
    |--------------------------------------------------------------------------
    */

    if(
        !session()->has(
            'pan_correction_preview'
        )
    )
    {

        return response()->json([

            'status' => false,

            'message' =>

                'Session expired.'

        ], 422);

    }

    DB::beginTransaction();

    try{

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | ADMIN USER
        |--------------------------------------------------------------------------
        */

        $admin = \App\Models\User::where(

            'role',

            'Admin'

        )->first();

        /*
        |--------------------------------------------------------------------------
        | SERVICE CHARGE
        |--------------------------------------------------------------------------
        */

        $serviceCharge = 107;

        /*
        |--------------------------------------------------------------------------
        | WALLET CHECK
        |--------------------------------------------------------------------------
        */

        if(
            ($user->wallet_balance ?? 0)
            < $serviceCharge
        )
        {

            return response()->json([

                'status' => false,

                'message' =>

                    'Insufficient wallet balance.'

            ], 422);

        }

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $data = session(

            'pan_correction_preview'

        );

        $files = session(

            'uploaded_files',

            []

        );

        /*
        |--------------------------------------------------------------------------
        | CREATE APPLICATION
        |--------------------------------------------------------------------------
        */

        $application =

        PanCorrectionApplication::create([

            'user_id' => $user->id,

            'first_name' => $data['first_name'],

            'middle_name' =>
                $data['middle_name'] ?? null,

            'last_name' =>
                $data['last_name'],

            'old_pan_number' =>
                strtoupper(
                    $data['old_pan_number']
                ),

            'gender' =>
                $data['gender'],

            'father_first_name' =>
                $data['father_first_name'],

            'father_middle_name' =>
                $data['father_middle_name'] ?? null,

            'father_last_name' =>
                $data['father_last_name'],

            'mother_first_name' =>
                $data['mother_first_name'],

            'mother_middle_name' =>
                $data['mother_middle_name'] ?? null,

            'mother_last_name' =>
                $data['mother_last_name'],

            'pan_print_name' =>
                $data['pan_print_name'] ?? null,

            'mobile_no' =>
                $data['mobile_no'],

            'email' =>
                $data['email'],

            'house_no' =>
                $data['house_no'],

            'village' =>
                $data['village'],

            'post_office' =>
                $data['post_office'],

            'area' =>
                $data['area'],

            'state' =>
                $data['state'],

            'district' =>
                $data['district'],

            'pincode' =>
                $data['pincode'],

            'identity_proof' =>
                $data['identity_proof'] ?? null,

            'address_proof' =>
                $data['address_proof'] ?? null,

            'dob' =>
                $data['dob'],

            'dob_proof' =>
                $data['dob_proof'] ?? null,

            'aadhaar_no' =>
                $data['aadhaar_no'],

            'aadhaar_name' =>
                $data['aadhaar_name'],

            'signature_type' =>
                $data['signature_type'] ?? null,

            'photo' =>
                $files['photo'] ?? null,

            'signature' =>
                $files['signature'] ?? null,

            'aadhaar_card' =>
                $files['aadhaar_card'] ?? null,

            'identity_proof_file' =>
                $files['identity_proof_file'] ?? null,

            'address_proof_file' =>
                $files['address_proof_file'] ?? null,

            'dob_proof_file' =>
                $files['dob_proof_file'] ?? null,

            'supporting_document' =>
                $files['supporting_document'] ?? null,

            'status' => 'Pending',

            'payment_status' => 'Paid',

            'amount' => $serviceCharge

        ]);

        /*
        |--------------------------------------------------------------------------
        | DEDUCT RETAILER WALLET
        |--------------------------------------------------------------------------
        */

        $user->decrement(

            'wallet_balance',

            $serviceCharge

        );

        /*
        |--------------------------------------------------------------------------
        | CREDIT ADMIN WALLET
        |--------------------------------------------------------------------------
        */

        if($admin)
        {

            $admin->increment(

                'wallet_balance',

                $serviceCharge

            );

        }

        /*
        |--------------------------------------------------------------------------
        | RETAILER WALLET TRANSACTION
        |--------------------------------------------------------------------------
        */

        \App\Models\WalletTransaction::create([

            'user_id' => $user->id,

            'amount' => $serviceCharge,

            'type' => 'debit',

            'remark' =>

                'PAN Correction Application Charge Deducted'

        ]);

        /*
        |--------------------------------------------------------------------------
        | ADMIN WALLET TRANSACTION
        |--------------------------------------------------------------------------
        */

        if($admin)
        {

            \App\Models\WalletTransaction::create([

                'user_id' => $admin->id,

                'amount' => $serviceCharge,

                'type' => 'credit',

                'remark' =>

                    'PAN Correction Charge Received'

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | CLEAR SESSION
        |--------------------------------------------------------------------------
        */

        session()->forget([

            'pan_correction_preview',

            'uploaded_files'

        ]);

        DB::commit();

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => true,

            'message' =>

                'PAN correction application submitted successfully.',

            'redirect_url' =>

                route(

                    'retailer.pan-correction.history'

                )

        ]);

    }catch(\Exception $e){

        DB::rollBack();

        /*
        |--------------------------------------------------------------------------
        | DELETE FILES
        |--------------------------------------------------------------------------
        */

        foreach(

            session(

                'uploaded_files',

                []

            ) as $file
        ){

            if(
                Storage::disk('public')
                ->exists($file)
            ){

                Storage::disk('public')
                ->delete($file);

            }

        }

        return response()->json([

            'status' => false,

            'message' =>

                $e->getMessage()

        ], 500);

    }

}

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history()
    {

        if(request()->ajax())
        {

            $applications = PanCorrectionApplication::query()

                ->with([

                    'user.retailer',

                    'stateData',

                    'districtData',

                    'documents'

                ])

                ->where(

                    'user_id',

                    auth()->id()

                )

                ->latest();

            return DataTables::of($applications)

                ->addIndexColumn()

                /*
                |--------------------------------------------------------------------------
                | SHOP NAME
                |--------------------------------------------------------------------------
                */

                ->addColumn('shop_name', function($row){

                    return '

                        <div class="fw-semibold text-primary">

                            '

                            . (

                                $row->user?->retailer?->shop_name

                                ?? 'N/A'

                            )

                            . '

                        </div>

                    ';

                })

                /*
                |--------------------------------------------------------------------------
                | APPLICANT
                |--------------------------------------------------------------------------
                */

                ->addColumn('applicant_name', function($row){

                    return '

                        <div>

                            <div class="fw-semibold">

                                '

                                . e($row->full_name)

                                . '

                            </div>

                            <small class="text-muted">

                                OLD PAN:

                                '

                                . e($row->old_pan_number)

                                . '

                            </small>

                        </div>

                    ';

                })

                /*
                |--------------------------------------------------------------------------
                | STATE
                |--------------------------------------------------------------------------
                */

                ->addColumn('state_name', function($row){

                    return $row->stateData?->name

                        ?? 'N/A';

                })

                /*
                |--------------------------------------------------------------------------
                | DISTRICT
                |--------------------------------------------------------------------------
                */

                ->addColumn('district_name', function($row){

                    return $row->districtData?->name

                        ?? 'N/A';

                })

                /*
                |--------------------------------------------------------------------------
                | PAYMENT
                |--------------------------------------------------------------------------
                */

                ->addColumn('payment_status', function($row){

                    return $row->payment_badge;

                })

                /*
                |--------------------------------------------------------------------------
                | AMOUNT
                |--------------------------------------------------------------------------
                */

                ->addColumn('amount', function($row){

                    return '

                        <span class="fw-bold text-success">

                            ₹'

                            . number_format(

                                $row->amount,

                                2

                            )

                            . '

                        </span>

                    ';

                })

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                ->addColumn('status', function($row){

                    return $row->status_badge;

                })

                /*
                |--------------------------------------------------------------------------
                | RECEIPT
                |--------------------------------------------------------------------------
                */

                ->addColumn('document_status', function($row){

                    $document = $row->documents->first();

                    if(

                        $document

                        &&

                        in_array(

                            strtolower($row->status),

                            [

                                'Approved',

                                'completed'

                            ]

                        )

                    )
                    {

                        return '

                            <div class="d-flex gap-2">

                                <a
                                    href="'

                                    . asset(

                                        'storage/' .

                                        $document->file_path

                                    )

                                    . '"

                                    target="_blank"

                                    class="btn btn-sm btn-success"

                                >

                                    <i class="fa fa-eye"></i>

                                </a>

                                <a
                                    href="'

                                    . asset(

                                        'storage/' .

                                        $document->file_path

                                    )

                                    . '"

                                    download

                                    class="btn btn-sm btn-primary"

                                >

                                    <i class="fa fa-download"></i>

                                </a>

                            </div>

                        ';

                    }

                    if($document)
                    {

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

                /*
                |--------------------------------------------------------------------------
                | DATE
                |--------------------------------------------------------------------------
                */

                ->addColumn('created_at', function($row){

                    return '

                        <div>

                            '

                            . $row->created_at->format(

                                'd M Y'

                            )

                            . '

                            <br>

                            <small class="text-muted">

                                '

                                . $row->created_at->format(

                                    'h:i A'

                                )

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

                ->addColumn('action', function($row){

                    return '

                        <a
                            href="'

                            . route(

                                'retailer.pan-correction.show',

                                $row->id

                            )

                            . '"

                            class="btn btn-sm btn-primary"

                        >

                            <i class="fa fa-eye"></i>

                        </a>

                    ';

                })

                /*
                |--------------------------------------------------------------------------
                | RAW COLUMNS
                |--------------------------------------------------------------------------
                */

                ->rawColumns([

                    'shop_name',

                    'applicant_name',

                    'payment',

                    'amount',

                    'status',

                    'document_status',

                    'created_at',

                    'action'

                ])

                ->make(true);

        }

        return view(

            'retailer.pan-correction.history'

        );

    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        int $id
    ): View
    {

        $application =

        PanCorrectionApplication::query()

            ->with([

                'user.retailer',

                'stateData',

                'districtData',

                'documents.user',

                'assignedUser'

            ])

            ->where(

                'user_id',

                auth()->id()

            )

            ->findOrFail($id);

        return view(

            'retailer.pan-correction.show',

            compact(

                'application'

            )

        );

    }

}