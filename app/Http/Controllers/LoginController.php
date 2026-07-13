<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN FORM
    |--------------------------------------------------------------------------
    */

    public function showLoginForm()
    {
        return view(
            'auth.retailer-login'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make(

            $request->all(),

            [

                'email' => [

                    'required',
                    'string'
                ],

                'password' => [

                    'required',
                    'string'
                ],

               
            ],

            [

                'email.required' =>

                    'Email or mobile number is required.',

                'password.required' =>

                    'Password is required.'

               
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

        /*
        |--------------------------------------------------------------------------
        | LOGIN FIELD
        |--------------------------------------------------------------------------
        */

        $loginField = filter_var(

            $request->email,
            FILTER_VALIDATE_EMAIL

        ) ? 'email' : 'mobile';

        /*
        |--------------------------------------------------------------------------
        | CREDENTIALS
        |--------------------------------------------------------------------------
        */

        $credentials = [

            $loginField => trim($request->email),

            'password' => trim($request->password),

            'status' => 1

        ];

        /*
        |--------------------------------------------------------------------------
        | ATTEMPT LOGIN
        |--------------------------------------------------------------------------
        */

        if (

            Auth::attempt(

                $credentials,

                $request->remember
            )

        ) {

            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */

            $user = Auth::user();

            /*
            |--------------------------------------------------------------------------
            | CHECK RETAILER ROLE
            |--------------------------------------------------------------------------
            */

            if (!$user->hasRole('retailer')) {

                Auth::logout();

                return response()->json([

                    'success' => false,

                    'message' =>

                        'Unauthorized access.'

                ], 403);
            }

            /*
            |--------------------------------------------------------------------------
            | REGENERATE SESSION
            |--------------------------------------------------------------------------
            */

            $request->session()
                    ->regenerate();

            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' => true,

                'message' =>

                    'Retailer login successful.',

                'redirect' =>

                    route('retailer.dashboard')

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | INVALID LOGIN
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => false,

            'message' =>

                'Invalid login credentials.'

        ], 401);
    }


    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    public function forgotPassword()
    {
        return view(
            'retailer.auth.forgot-password'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()
                ->invalidate();

        $request->session()
                ->regenerateToken();

        return redirect()
            ->route('retailer.login')
            ->with(

                'success',

                'Logged out successfully.'
            );
    }
}