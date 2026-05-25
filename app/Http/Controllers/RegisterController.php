<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\State;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW REGISTRATION FORM
    |--------------------------------------------------------------------------
    */

    public function showRegistrationForm()
    {
        $states = State::orderBy('name')
                       ->get();

        return view(

            'auth.retailer.register',

            compact('states')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER RETAILER
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validator = Validator::make(

        $request->all(),

        [

            'shop_name' => [

                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[A-Za-z0-9\s&.,-]+$/'
            ],

            'name' => [

                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[A-Za-z\s]+$/'
            ],

            'mobile' => [

                'required',
                'digits:10',
                'regex:/^[6-9][0-9]{9}$/',
                'unique:users,mobile',
                'unique:retailers,mobile'
            ],

            'email' => [

                'required',
                'email:rfc,dns',
                'max:255',
                'unique:users,email',
                'unique:retailers,email'
            ],

            'state_id' => [

                'required',
                'exists:states,id'
            ],

            'district_id' => [

                'required',
                'exists:districts,id'
            ],

            'g-recaptcha-response' => [

                'required'
            ]

        ],

        [

            'shop_name.required' =>
                'Shop name is required',

            'name.required' =>
                'Name is required',

            'mobile.required' =>
                'Mobile number is required',

            'mobile.digits' =>
                'Mobile number must be 10 digits',

            'mobile.unique' =>
                'Mobile number already exists',

            'email.required' =>
                'Email is required',

            'email.email' =>
                'Enter valid email address',

            'email.unique' =>
                'Email already exists',

            'state_id.required' =>
                'Please select state',

            'district_id.required' =>
                'Please select district',

            'g-recaptcha-response.required' =>
                'Please verify captcha'

        ]

    );

    /*
    |--------------------------------------------------------------------------
    | VALIDATION FAILED
    |--------------------------------------------------------------------------
    */

    if ($validator->fails()) {

        return response()->json([

            'success' => false,

            'errors' =>

                $validator->errors()

        ], 422);

    }

    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | USER ID = EMAIL
        |--------------------------------------------------------------------------
        */

        $userId =
        strtolower(
            trim($request->email)
        );

        /*
        |--------------------------------------------------------------------------
        | PASSWORD = MOBILE
        |--------------------------------------------------------------------------
        */

        $plainPassword =
        trim($request->mobile);

        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        $user = User::create([

    
            'name' =>

                trim($request->name),

            'mobile' =>

                trim($request->mobile),

            'email' =>

                strtolower(
                    trim($request->email)
                ),


            'password' =>

                Hash::make(
                    $plainPassword
                ),

            'status' => 1,

        ]);

        /*
        |--------------------------------------------------------------------------
        | ASSIGN ROLE
        |--------------------------------------------------------------------------
        */

        $user->assignRole('retailer');

        /*
        |--------------------------------------------------------------------------
        | CREATE RETAILER
        |--------------------------------------------------------------------------
        */

        DB::table('retailers')->insert([


            'shop_name' =>

                trim($request->shop_name),

            'name' =>

                trim($request->name),

            'mobile' =>

                trim($request->mobile),

            'email' =>

                strtolower(
                    trim($request->email)
                ),

            'state_id' =>

                $request->state_id,

            'district_id' =>

                $request->district_id,

            'password' =>

                trim($request->mobile),

            'status' =>

                'approved',

            'is_verified' => 1,

            'registered_ip' =>

                $request->ip(),

            'created_at' => now(),

            'updated_at' => now(),

        ]);

        DB::commit();

        /*
        |--------------------------------------------------------------------------
        | SUCCESS RESPONSE
        |--------------------------------------------------------------------------
        */

       /*
        |--------------------------------------------------------------------------
        | SUCCESS RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'message' =>

                'Retailer registered successfully.',

            /*
            |--------------------------------------------------------------------------
            | LOGIN CREDENTIALS
            |--------------------------------------------------------------------------
            */

            'credentials' => [

                'email' =>

                    $userId,

                'password' =>

                    $plainPassword,

            ],

            'redirect' =>

                route('retailer.login')

        ]);

            } catch (\Exception $e) {

                DB::rollBack();

                return response()->json([

                    'success' => false,

                    'message' =>

                        'Something went wrong.',

                    'error' =>

                        $e->getMessage()

                ], 500);

            }
        }

    /*
    |--------------------------------------------------------------------------
    | GET DISTRICTS
    |--------------------------------------------------------------------------
    */

    public function getDistricts($stateId)
    {
        $districts = District::where(

                            'state_id',

                            $stateId

                        )

                        ->orderBy('name')

                        ->get();

        return response()->json(
            $districts
        );
    }
}