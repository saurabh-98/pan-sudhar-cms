<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\RetailerSession;

class UpdateRetailerActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {

            $sessionId = session('retailer_session_id');

            if ($sessionId) {

                RetailerSession::where('id', $sessionId)
                    ->whereNull('logout_at')
                    ->update([
                        'last_activity_at' => now()
                    ]);
            }
        }

        return $next($request);
    }
}