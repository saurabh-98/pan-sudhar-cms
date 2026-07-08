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
    | TURNSTILE ONLY IN PRODUCTION
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

                'secret' => config('services.turnstile.secret_key'),

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
    | LOGIN ATTEMPT
    |--------------------------------------------------------------------------
    */

    if (Auth::attempt(

        $credentials,

        $request->boolean('remember')

    )) {

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | ROLE CHECK
        |--------------------------------------------------------------------------
        */

        if (!$user->hasRole('retailer')) {

            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return response()->json([

                'success' => false,

                'message' => 'Unauthorized access.'

            ], 403);

        }

        /*
        |--------------------------------------------------------------------------
        | SESSION REGENERATE
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'message' => 'Retailer login successful.',

            'redirect' => route('retailer.dashboard')

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | INVALID LOGIN
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'success' => false,

        'message' => 'Invalid login credentials.'

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