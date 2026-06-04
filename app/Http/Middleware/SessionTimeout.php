<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    protected $timeout = 15; // minutes

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {

            $lastActivity =
                session('last_activity');

            if (
                $lastActivity &&
                now()->diffInMinutes(
                    $lastActivity
                ) >= $this->timeout
            ) {

                Auth::logout();

                session()->invalidate();

                session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Session expired due to inactivity.'
                    );
            }

            session([
                'last_activity' => now()
            ]);
        }

        return $next($request);
    }
}