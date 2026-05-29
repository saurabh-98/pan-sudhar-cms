<?php

namespace App\Http\Controllers\Retailer\Itr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\ItrFile;
use App\Models\User;

class FileItrController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        return view('retailer.itr.file');

    }



    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'aadhaar_front' => [

                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'

            ],

            'aadhaar_back' => [

                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'

            ],

            'pan_card' => [

                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'

            ],

            'name' => [

                'required',
                'string',
                'max:255'

            ],

            'email' => [

                'required',
                'email',
                'max:255'

            ],

            'remarks' => [

                'nullable',
                'string',
                'max:1000'

            ],

        ]);



        /*
        |--------------------------------------------------------------------------
        | VALIDATION FAILED
        |--------------------------------------------------------------------------
        */

        if($validator->fails()){

            return response()->json([

                'status' => false,

                'errors' => $validator->errors()

            ], 422);

        }



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

        $admin = User::role('admin')->first();



        /*
        |--------------------------------------------------------------------------
        | CHARGE
        |--------------------------------------------------------------------------
        */

        $itrCharge = 99;



        /*
        |--------------------------------------------------------------------------
        | CHECK WALLET BALANCE
        |--------------------------------------------------------------------------
        */

        if($user->wallet_balance < $itrCharge){

            return response()->json([

                'status' => false,

                'message' => 'Insufficient wallet balance.'

            ], 422);

        }



        DB::beginTransaction();

        try{



            /*
            |--------------------------------------------------------------------------
            | STORE AADHAAR FRONT
            |--------------------------------------------------------------------------
            */

            $aadhaarFront = null;

            if($request->hasFile('aadhaar_front')){

                $file = $request->file('aadhaar_front');

                $aadhaarFront = $file->storeAs(

                    'itr/aadhaar-front',

                    time().'_'.
                    uniqid().
                    '.'.
                    $file->getClientOriginalExtension(),

                    'public'

                );

            }



            /*
            |--------------------------------------------------------------------------
            | STORE AADHAAR BACK
            |--------------------------------------------------------------------------
            */

            $aadhaarBack = null;

            if($request->hasFile('aadhaar_back')){

                $file = $request->file('aadhaar_back');

                $aadhaarBack = $file->storeAs(

                    'itr/aadhaar-back',

                    time().'_'.
                    uniqid().
                    '.'.
                    $file->getClientOriginalExtension(),

                    'public'

                );

            }



            /*
            |--------------------------------------------------------------------------
            | STORE PAN CARD
            |--------------------------------------------------------------------------
            */

            $panCard = null;

            if($request->hasFile('pan_card')){

                $file = $request->file('pan_card');

                $panCard = $file->storeAs(

                    'itr/pan-card',

                    time().'_'.
                    uniqid().
                    '.'.
                    $file->getClientOriginalExtension(),

                    'public'

                );

            }



            /*
            |--------------------------------------------------------------------------
            | USER WALLET DEDUCTION
            |--------------------------------------------------------------------------
            */

            $beforeBalance = $user->wallet_balance;

            $afterBalance = $beforeBalance - $itrCharge;

            $user->wallet_balance = $afterBalance;

            $user->save();



            /*
            |--------------------------------------------------------------------------
            | ADMIN WALLET CREDIT
            |--------------------------------------------------------------------------
            */

            if($admin){

                $admin->wallet_balance =
                $admin->wallet_balance + $itrCharge;

                $admin->save();

            }



            /*
            |--------------------------------------------------------------------------
            | STORE ITR RECORD
            |--------------------------------------------------------------------------
            */

            $itrFile = ItrFile::create([

                'user_id' => $user->id,

                'aadhaar_front' => $aadhaarFront,

                'aadhaar_back' => $aadhaarBack,

                'pan_card' => $panCard,

                'name' => $request->name,

                'email' => $request->email,

                'remarks' => $request->remarks,

                'charge' => $itrCharge,

                'status' => 'pending',

            ]);



            /*
            |--------------------------------------------------------------------------
            | USER TRANSACTION ENTRY
            |--------------------------------------------------------------------------
            */

            DB::table('wallet_transactions')->insert([

                'user_id' => $user->id,

                'receiver_id' => $admin?->id,

                'amount' => $itrCharge,

                'type' => 'debit',

                'transaction_type' => 'itr_filing',

                'remarks' => 'ITR filing charge deducted',

                'created_at' => now(),

                'updated_at' => now(),

            ]);



            /*
            |--------------------------------------------------------------------------
            | ADMIN TRANSACTION ENTRY
            |--------------------------------------------------------------------------
            */

            if($admin){

                DB::table('wallet_transactions')->insert([

                    'user_id' => $admin->id,

                    'receiver_id' => $user->id,

                    'amount' => $itrCharge,

                    'type' => 'credit',

                    'transaction_type' => 'itr_filing_income',

                    'remarks' => 'ITR filing payment received',

                    'created_at' => now(),

                    'updated_at' => now(),

                ]);

            }



            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */

            DB::commit();



            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'status' => true,

                'message' => 'ITR documents uploaded successfully!',

                'data' => [

                    'id' => $itrFile->id,

                    'name' => $itrFile->name,

                    'email' => $itrFile->email,

                    'charge' => $itrCharge,

                    'wallet_before' => $beforeBalance,

                    'wallet_after' => $afterBalance,

                    'aadhaar_front' => asset(

                        'storage/'.$aadhaarFront

                    ),

                    'aadhaar_back' => asset(

                        'storage/'.$aadhaarBack

                    ),

                    'pan_card' => asset(

                        'storage/'.$panCard

                    ),

                    'status' => $itrFile->status,

                ]

            ]);

        }catch(\Exception $e){

            DB::rollBack();



            /*
            |--------------------------------------------------------------------------
            | DELETE FILES IF ERROR
            |--------------------------------------------------------------------------
            */

            if(

                isset($aadhaarFront) &&
                $aadhaarFront &&
                Storage::disk('public')->exists($aadhaarFront)

            ){

                Storage::disk('public')->delete($aadhaarFront);

            }



            if(

                isset($aadhaarBack) &&
                $aadhaarBack &&
                Storage::disk('public')->exists($aadhaarBack)

            ){

                Storage::disk('public')->delete($aadhaarBack);

            }



            if(

                isset($panCard) &&
                $panCard &&
                Storage::disk('public')->exists($panCard)

            ){

                Storage::disk('public')->delete($panCard);

            }



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

/*
|--------------------------------------------------------------------------
| HISTORY
|--------------------------------------------------------------------------
*/

public function history()
{
   

    if (request()->ajax()) {

        $itrFiles = ItrFile::where(
                'user_id',
                auth()->id()
            )
            ->latest()
            ->paginate(10);

            

        $data = $itrFiles->getCollection()->map(function ($itr) {

            return [

                'id'             => $itr->id,
                'name'           => $itr->name,
                'email'          => $itr->email,
                'charge'         => $itr->charge,
                'status'         => $itr->status,
                'remarks'        => $itr->remarks,
                'admin_remarks'  => $itr->admin_remarks,
                'aadhaar_front'  => $itr->aadhaar_front,
                'aadhaar_back'   => $itr->aadhaar_back,
                'pan_card'       => $itr->pan_card,
                'created_at'     => $itr->created_at
                                        ? $itr->created_at->format('d M Y')
                                        : 'N/A',

            ];

        });

        return response()->json([

            'status' => true,

           'data' => $data->values()->toArray(),

            'pagination' => [

                'current_page' => $itrFiles->currentPage(),
                'last_page'    => $itrFiles->lastPage(),
                'per_page'     => $itrFiles->perPage(),
                'total'        => $itrFiles->total(),

            ]

        ]);

        

    }

    return view('retailer.itr.history');

}

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {

        $itrFile = ItrFile::where(

            'user_id',
            auth()->id()

        )
        ->findOrFail($id);



        return response()->json([

            'status' => true,

            'data' => [

                'id' => $itrFile->id,

                'name' => $itrFile->name,

                'email' => $itrFile->email,

                'remarks' => $itrFile->remarks,

                'charge' => $itrFile->charge,

                'status' => $itrFile->status,

                'aadhaar_front' => asset(

                    'storage/'.$itrFile->aadhaar_front

                ),

                'aadhaar_back' => asset(

                    'storage/'.$itrFile->aadhaar_back

                ),

                'pan_card' => asset(

                    'storage/'.$itrFile->pan_card

                ),

                'created_at' => $itrFile->created_at
                ->format('d M Y h:i A')

            ]

        ]);

    }



    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {

        $itrFile = ItrFile::where(

            'user_id',
            auth()->id()

        )
        ->findOrFail($id);



        DB::beginTransaction();

        try{



            /*
            |--------------------------------------------------------------------------
            | DELETE AADHAAR FRONT
            |--------------------------------------------------------------------------
            */

            if(

                $itrFile->aadhaar_front &&
                Storage::disk('public')->exists(
                    $itrFile->aadhaar_front
                )

            ){

                Storage::disk('public')->delete(
                    $itrFile->aadhaar_front
                );

            }



            /*
            |--------------------------------------------------------------------------
            | DELETE AADHAAR BACK
            |--------------------------------------------------------------------------
            */

            if(

                $itrFile->aadhaar_back &&
                Storage::disk('public')->exists(
                    $itrFile->aadhaar_back
                )

            ){

                Storage::disk('public')->delete(
                    $itrFile->aadhaar_back
                );

            }



            /*
            |--------------------------------------------------------------------------
            | DELETE PAN CARD
            |--------------------------------------------------------------------------
            */

            if(

                $itrFile->pan_card &&
                Storage::disk('public')->exists(
                    $itrFile->pan_card
                )

            ){

                Storage::disk('public')->delete(
                    $itrFile->pan_card
                );

            }



            /*
            |--------------------------------------------------------------------------
            | DELETE RECORD
            |--------------------------------------------------------------------------
            */

            $itrFile->delete();



            DB::commit();



            return response()->json([

                'status' => true,

                'message' => 'ITR record deleted successfully!'

            ]);

        }catch(\Exception $e){

            DB::rollBack();

            return response()->json([

                'status' => false,

                'message' => $e->getMessage()

            ], 500);

        }

    }

}