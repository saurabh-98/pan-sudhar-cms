<?php

namespace App\Services;

use App\DTO\LoginDTO;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use App\DTO\RegisterDTO;
use App\DTO\ForgotPasswordDTO;
use App\DTO\ResetPasswordDTO;
use Illuminate\Support\Facades\Password;

class AuthService
{
    protected $authRepo;

    public function __construct(AuthRepository $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    public function login(LoginDTO $dto, Request $request): array
    {
        $credentials = [
            'email' => $dto->email,
            'password' => $dto->password
        ];

        if (!$this->authRepo->attemptLogin($credentials, $dto->remember)) {
            return [
                'status' => 'error',
                'message' => 'Invalid email or password'
            ];
        }

        // session security
        $request->session()->regenerate();

        $user = $this->authRepo->user();

        return [
            'status' => 'success',
            'message' => "Welcome {$user->name}",
            'redirect' => $this->redirectByRole($user->role)
        ];
    }

    public function logout(Request $request): void
    {
        $this->authRepo->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

     private function redirectByRole(?string $role): string
    {
        return match ($role ?? 'customer') {

            'customer' => route('home'), 

            'admin'    => route('admin.dashboard'),

            'staff'    => route('staff.dashboard'),
        };
    }
        

    public function register(RegisterDTO $dto): array
    {
        $this->authRepo->createUser([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
        ]);

        return [
            'status' => 'success',
            'message' => 'Account created successfully',
            'redirect' => '/login'
        ];
    }

    public function forgotPassword(ForgotPasswordDTO $dto): array
    {
        $status = $this->authRepo->sendResetLink($dto->email);

        return $status === Password::RESET_LINK_SENT
            ? ['status' => 'success', 'message' => 'Reset link sent']
            : ['status' => 'error', 'message' => 'Email not found'];
    }

    public function resetPassword(ResetPasswordDTO $dto): array
    {
        $status = $this->authRepo->resetPassword([
            'email' => $dto->email,
            'password' => $dto->password,
            'password_confirmation' => $dto->password,
            'token' => $dto->token,
        ]);

        return $status === Password::PASSWORD_RESET
            ? [
                'status' => 'success',
                'message' => 'Password reset successful',
                'redirect' => '/login'
            ]
            : [
                'status' => 'error',
                'message' => 'Invalid or expired token'
            ];
    }
}