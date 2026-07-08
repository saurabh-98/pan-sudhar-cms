<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class ExecutiveLoginController extends Controller
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
            Auth::user()->hasRole('Executive')
        ) {

            return redirect()->route(
                'admin.dashboard'
            );
        }

        return view(
            'auth.executive-login'
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
    | VALIDATION RULES
    |--------------------------------------------------------------------------
    */

    $rules = [

        'email' => [

            'required',
            'string'

        ],

        'password' => [

            'required',
            'string'

        ]

    ];

    /*
    |--------------------------------------------------------------------------
    | TURNSTILE VALIDATION (PRODUCTION ONLY)
    |--------------------------------------------------------------------------
    */

    if (app()->environment('production')) {

        $rules['cf-turnstile-response'] = [

            'required'

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATOR
    |--------------------------------------------------------------------------
    */

    $validator = Validator::make(

        $request->all(),

        $rules,

        [

            'email.required' =>

                'Email or mobile number is required.',

            'password.required' =>

                'Password is required.',

            'cf-turnstile-response.required' =>

                'Please complete the security verification.'

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

            'errors' => $validator->errors()

        ], 422);

    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY CLOUDFLARE TURNSTILE
    |--------------------------------------------------------------------------
    */

    if (app()->environment('production')) {

        $response = Http::asForm()->post(

            'https://challenges.cloudflare.com/turnstile/v0/siteverify',

            [

                'secret'   => config('services.turnstile.secret_key'),

                'response' => $request->input('cf-turnstile-response'),

                'remoteip' => $request->ip(),

            ]

        );

        if (! $response->json('success')) {

            return response()->json([

                'success' => false,

                'errors' => [

                    'captcha' => [

                        'Verification failed.'

                    ]

                ]

            ], 422);

        }

    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN FIELD
    |--------------------------------------------------------------------------
    */

    $loginField = filter_var(

        trim($request->email),

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

        ! Auth::attempt(

            $credentials,

            $request->boolean('remember')

        )

    ) {

        return response()->json([

            'success' => false,

            'message' => 'Invalid login credentials.'

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

    if (! $user->hasRole('Executive')) {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([

            'success' => false,

            'message' => 'Only executives can login here.'

        ], 403);

    }

    /*
    |--------------------------------------------------------------------------
    | ACCOUNT STATUS CHECK
    |--------------------------------------------------------------------------
    */

    if (isset($user->status) && ! $user->status) {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([

            'success' => false,

            'message' => 'Your account has been disabled.'

        ], 403);

    }

    /*
    |--------------------------------------------------------------------------
    | SESSION SECURITY
    |--------------------------------------------------------------------------
    */

    $request->session()->regenerate();

    /*
    |--------------------------------------------------------------------------
    | SUCCESS RESPONSE
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'success' => true,

        'message' => 'Executive login successful.',

        'redirect' => route('admin.dashboard')

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
                'executive.login'
            )
            ->with(

                'success',

                'Logged out successfully.'

            );
    }
}