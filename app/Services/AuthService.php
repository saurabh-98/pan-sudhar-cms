<?php

namespace App\Services;

use App\DTO\LoginDTO;
use App\DTO\RegisterDTO;
use App\DTO\ForgotPasswordDTO;
use App\DTO\ResetPasswordDTO;

use App\Repositories\AuthRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthService
{
    protected $authRepo;

    public function __construct(
        AuthRepository $authRepo
    ) {
        $this->authRepo = $authRepo;
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(
        LoginDTO $dto,
        Request $request
    ): array {

        /*
        |--------------------------------------------------------------------------
        | CREDENTIALS
        |--------------------------------------------------------------------------
        */

        $credentials = [

            'email' => $dto->email,

            'password' => $dto->password,

            /*
            |--------------------------------------------------------------------------
            | ACTIVE USERS ONLY
            |--------------------------------------------------------------------------
            */

            'status' => 1
        ];

        /*
        |--------------------------------------------------------------------------
        | LOGIN ATTEMPT
        |--------------------------------------------------------------------------
        */

        if (
            !$this->authRepo->attemptLogin(
                $credentials,
                $dto->remember
            )
        ) {

            return [

                'status' => 'error',

                'message' =>
                    'Invalid credentials or inactive account'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | SESSION SECURITY
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | AUTH USER
        |--------------------------------------------------------------------------
        */

        $user = $this->authRepo->user();

        /*
        |--------------------------------------------------------------------------
        | ROLE CHECK
        |--------------------------------------------------------------------------
        */

        if (!$user->roles->count()) {

            Auth::logout();

            return [

                'status' => 'error',

                'message' =>
                    'No role assigned to this user'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD ACCESS
        |--------------------------------------------------------------------------
        */

        if (
            !$user->can(
                'dashboard.view'
            )
        ) {

            Auth::logout();

            return [

                'status' => 'error',

                'message' =>
                    'Dashboard access denied'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return [

            'status' => 'success',

            'message' =>
                "Welcome {$user->name}",

            /*
            |--------------------------------------------------------------------------
            | SINGLE DASHBOARD
            |--------------------------------------------------------------------------
            */

            'redirect' => route(
                'admin.dashboard'
            ),

            /*
            |--------------------------------------------------------------------------
            | ROLE
            |--------------------------------------------------------------------------
            */

            'role' => $user
                ->getRoleNames()
                ->first(),

            /*
            |--------------------------------------------------------------------------
            | PERMISSIONS
            |--------------------------------------------------------------------------
            */

            'permissions' => $user
                ->getAllPermissions()
                ->pluck('name')
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(
        Request $request
    ): void {

        $this->authRepo->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(
        RegisterDTO $dto
    ): array {

        $user = $this->authRepo
            ->createUser([

                'name' => $dto->name,

                'email' => $dto->email,

                'password' => $dto->password,

                'status' => 1
            ]);

        /*
        |--------------------------------------------------------------------------
        | DEFAULT ROLE
        |--------------------------------------------------------------------------
        */

        if (!$user->hasRole('Customer')) {

            $user->assignRole(
                'Customer'
            );
        }

        return [

            'status' => 'success',

            'message' =>
                'Account created successfully',

            'redirect' => '/login'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    public function forgotPassword(
        ForgotPasswordDTO $dto
    ): array {

        $status = $this->authRepo
            ->sendResetLink(
                $dto->email
            );

        return
            $status === Password::RESET_LINK_SENT

            ? [

                'status' => 'success',

                'message' =>
                    'Reset link sent'
            ]

            : [

                'status' => 'error',

                'message' =>
                    'Email not found'
            ];
    }

    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    public function resetPassword(
        ResetPasswordDTO $dto
    ): array {

        $status = $this->authRepo
            ->resetPassword([

                'email' => $dto->email,

                'password' => $dto->password,

                'password_confirmation'
                    => $dto->password,

                'token' => $dto->token,
            ]);

        return
            $status === Password::PASSWORD_RESET

            ? [

                'status' => 'success',

                'message' =>
                    'Password reset successful',

                'redirect' => '/login'
            ]

            : [

                'status' => 'error',

                'message' =>
                    'Invalid or expired token'
            ];
    }
}