<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

use App\DTO\LoginDTO;
use App\DTO\RegisterDTO;
use App\DTO\ForgotPasswordDTO;
use App\DTO\ResetPasswordDTO;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(
        AuthService $authService
    ) {
        $this->authService = $authService;
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN PAGE
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        


        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(
        LoginRequest $request
    ) {

        /*
        |--------------------------------------------------------------------------
        | DTO
        |--------------------------------------------------------------------------
        */

        $dto = LoginDTO::fromRequest(
            $request
        );

        /*
        |--------------------------------------------------------------------------
        | LOGIN SERVICE
        |--------------------------------------------------------------------------
        */

        $response = $this->authService
            ->login($dto, $request);

        /*
        |--------------------------------------------------------------------------
        | AJAX RESPONSE
        |--------------------------------------------------------------------------
        */

        if ($request->expectsJson()) {

            return response()->json(
                $response
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        if (
            $response['status']
            === 'success'
        ) {

            return redirect()
                ->intended(
                    $response['redirect']
                )
                ->with(
                    'success',
                    $response['message']
                );
        }

        /*
        |--------------------------------------------------------------------------
        | FAILED
        |--------------------------------------------------------------------------
        */

        return back()
            ->withInput()
            ->with(
                'error',
                $response['message']
            );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
{
    $user = Auth::user();

    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    if ($user) {

        if ($user->hasRole('Executive')) {

            return redirect()
                ->route('executive.login');
        }

        if (
            $user->hasRole('Admin')
            ||
            $user->hasRole('Super Admin')
        ) {

            return redirect()
                ->route('login');
        }
    }

    return redirect()
        ->route('login');
}
    /*
    |--------------------------------------------------------------------------
    | REGISTER PAGE
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view(
            'auth.register'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(
        RegisterRequest $request
    ) {

        /*
        |--------------------------------------------------------------------------
        | DTO
        |--------------------------------------------------------------------------
        */

        $dto = RegisterDTO::fromRequest(
            $request
        );

        /*
        |--------------------------------------------------------------------------
        | REGISTER SERVICE
        |--------------------------------------------------------------------------
        */

        $response = $this->authService
            ->register($dto);

        /*
        |--------------------------------------------------------------------------
        | JSON RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json(
            $response
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD PAGE
    |--------------------------------------------------------------------------
    */

    public function showForgotPassword()
    {
        return view(
            'auth.forgot-password'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    public function forgotPassword(
        ForgotPasswordRequest $request
    ) {

        /*
        |--------------------------------------------------------------------------
        | DTO
        |--------------------------------------------------------------------------
        */

        $dto = ForgotPasswordDTO
            ::fromRequest($request);

        /*
        |--------------------------------------------------------------------------
        | SERVICE
        |--------------------------------------------------------------------------
        */

        $response = $this->authService
            ->forgotPassword($dto);

        return response()->json(
            $response
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    public function resetPassword(
        ResetPasswordRequest $request
    ) {

        /*
        |--------------------------------------------------------------------------
        | DTO
        |--------------------------------------------------------------------------
        */

        $dto = ResetPasswordDTO
            ::fromRequest($request);

        /*
        |--------------------------------------------------------------------------
        | SERVICE
        |--------------------------------------------------------------------------
        */

        $response = $this->authService
            ->resetPassword($dto);

        return response()->json(
            $response
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CURRENT USER ROLE
    |--------------------------------------------------------------------------
    */

    public function currentRole()
    {
        $user = Auth::user();

        if (!$user) {

            return response()->json([

                'status' => false,

                'message' => 'Unauthenticated'

            ], 401);
        }

        return response()->json([

            'status' => true,

            'user' => [

                'id' => $user->id,

                'name' => $user->name,

                'email' => $user->email,

                'roles' => $user
                    ->getRoleNames(),

                'permissions' => $user
                    ->getAllPermissions()
                    ->pluck('name')
            ]
        ]);
    }
}