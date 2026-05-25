<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | LOGIN CHECK
        |--------------------------------------------------------------------------
        */

        if (!auth()->check()) {

            abort(403, 'Unauthorized');

        }

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | ADMIN FULL ACCESS
        |--------------------------------------------------------------------------
        */

        if (
            $user->hasRole('Admin')
        ) {

            return $next($request);

        }


        /*
        |--------------------------------------------------------------------------
        | PERMISSION CHECK
        |--------------------------------------------------------------------------
        */

        if (
            !$user->hasPermissionTo($permission)
        ) {

            abort(
                403,
                'USER DOES NOT HAVE THE RIGHT PERMISSIONS.'
            );

        }

        return $next($request);
    }
}