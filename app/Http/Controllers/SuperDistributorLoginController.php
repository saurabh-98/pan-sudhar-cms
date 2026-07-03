<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SuperDistributorLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN FORM
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        /*
        |--------------------------------------------------------------------------
        | ALREADY LOGGED IN
        |--------------------------------------------------------------------------
        */

        if (
            Auth::check()
            &&
            Auth::user()->hasRole('Super Distributor')
        ) {

            return redirect()->route(
                'admin.dashboard'
            );
        }

        return view(
            'auth.super-distributor-login'
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

                'g-recaptcha-response' => [

                    'required'

                ]

            ],

            [

                'email.required' =>

                    'Email or mobile number is required.',

                'password.required' =>

                    'Password is required.',

                'g-recaptcha-response.required' =>

                    'Captcha verification is required.'

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

            trim($request->email),

            FILTER_VALIDATE_EMAIL

        )

        ? 'email'

        : 'mobile';

        /*
        |--------------------------------------------------------------------------
        | CREDENTIALS
        |--------------------------------------------------------------------------
        */

        $credentials = [

            $loginField => trim(
                $request->email
            ),

            'password' => trim(
                $request->password
            ),

            'status' => 1

        ];

        /*
        |--------------------------------------------------------------------------
        | ATTEMPT LOGIN
        |--------------------------------------------------------------------------
        */

        if (

            !Auth::attempt(

                $credentials,

                $request->boolean(
                    'remember'
                )

            )

        ) {

            return response()->json([

                'success' => false,

                'message' =>

                    'Invalid login credentials.'

            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | ROLE CHECK
        |--------------------------------------------------------------------------
        */

        if (

            !$user->hasRole(
                'Super Distributor'
            )

        ) {

            Auth::logout();

            $request->session()
                    ->invalidate();

            $request->session()
                    ->regenerateToken();

            return response()->json([

                'success' => false,

                'message' =>

                    'Only Super Distributor can login here.'

            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | ACCOUNT STATUS CHECK
        |--------------------------------------------------------------------------
        */

        if (

            isset($user->status)

            &&

            !$user->status

        ) {

            Auth::logout();

            $request->session()
                    ->invalidate();

            $request->session()
                    ->regenerateToken();

            return response()->json([

                'success' => false,

                'message' =>

                    'Your account has been disabled.'

            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | SESSION SECURITY
        |--------------------------------------------------------------------------
        */

        $request->session()
                ->regenerate();

        /*
        |--------------------------------------------------------------------------
        | SUCCESS RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'message' =>

                'Super Distributor login successful.',

            'redirect' =>

                route('admin.dashboard')

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    public function forgotPassword()
    {
        return view(
            'executive.auth.forgot-password'
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
            ->route(
                'super-distributor.login'
            )
            ->with(

                'success',

                'Logged out successfully.'

            );
    }
}