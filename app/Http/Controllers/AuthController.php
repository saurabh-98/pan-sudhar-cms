<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\DTO\LoginDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\DTO\RegisterDTO;
use App\DTO\ForgotPasswordDTO;
use App\DTO\ResetPasswordDTO;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

   public function showLogin()
    {
        

        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $dto = LoginDTO::fromRequest($request);

        $response = $this->authService->login($dto, $request);

        // ✅ AJAX
        if ($request->expectsJson()) {
            return response()->json($response);
        }

        // ✅ NORMAL
        if ($response['status'] === 'success') {
            return redirect()->intended($response['redirect'])
                ->with('success', $response['message']);
        }

        return back()->withInput()->with('error', $response['message']);
    }


    public function logout(Request $request)
    {
        $this->authService->logout($request);

        return redirect('/login')->with('success', 'Logged out successfully');
    }

    public function showRegister(){
        
        return view('auth.register');
    }

     public function showForgotPassword(){
        
        return view('auth.forgot-password');
    }

    // REGISTER
    public function register(RegisterRequest $request)
    {
        $dto = RegisterDTO::fromRequest($request);
        return response()->json($this->authService->register($dto));
    }

    // FORGOT
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $dto = ForgotPasswordDTO::fromRequest($request);
        return response()->json($this->authService->forgotPassword($dto));
    }

    // RESET
    public function resetPassword(ResetPasswordRequest $request)
    {
        $dto = ResetPasswordDTO::fromRequest($request);
        return response()->json($this->authService->resetPassword($dto));
    }
}