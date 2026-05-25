<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN PAGE
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('auth.student-login');
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

        $validator = Validator::make($request->all(), [

            'email' => 'required|string',

            'password' => 'required|string|min:6',

        ]);

        /*
        |--------------------------------------------------------------------------
        | VALIDATION FAILED
        |--------------------------------------------------------------------------
        */

        if ($validator->fails()) {

            return response()->json([

                'status' => false,

                'errors' => $validator->errors()

            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | EMAIL / REGISTRATION NUMBER
        |--------------------------------------------------------------------------
        */

        $email = strtolower(
            trim($request->email)
        );

        /*
        |--------------------------------------------------------------------------
        | ALLOW REGISTRATION NUMBER LOGIN
        |--------------------------------------------------------------------------
        */

        if (!str_contains($email, '@')) {

            $email = $email . '@school.com';
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN ATTEMPT
        |--------------------------------------------------------------------------
        */

        if (!Auth::attempt([

            'email' => $email,

            'password' => $request->password,

            'status' => 1

        ], $request->remember ?? false)) {

            return response()->json([

                'status' => false,

                'message' => 'Invalid email/registration number or password'

            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | SESSION SECURITY
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | ROLE CHECK
        |--------------------------------------------------------------------------
        */

        if (!$user->hasRole('student')) {

            Auth::logout();

            return response()->json([

                'status' => false,

                'message' => 'Unauthorized access'

            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | FIRST LOGIN REDIRECT
        |--------------------------------------------------------------------------
        */

        if ($user->first_login) {

            return response()->json([

                'status' => true,

                'message' => 'Login successful',

                'redirect' => route('student.dashboard')
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESS RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => true,

            'message' => 'Login successful',

            'redirect' => route('student.dashboard')

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }
}