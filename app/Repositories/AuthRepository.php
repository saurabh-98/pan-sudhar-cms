<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;


class AuthRepository
{
    public function attemptLogin(array $credentials, bool $remember): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    public function user()
    {
        return Auth::user();
    }

    public function logout(): void
    {
        Auth::logout();
    }

   
public function createUser(array $data)
{
    return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);
}

public function sendResetLink(string $email): string
{
    return Password::sendResetLink(['email' => $email]);
}

public function resetPassword(array $data): string
{
    return Password::reset(
        $data,
        function ($user, $password) {
            $user->update(['password' => Hash::make($password)]);
        }
    );
}
}