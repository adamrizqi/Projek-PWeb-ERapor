<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                Log::info('User Activity', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'role' => $user->role,
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now(),
                ]);
            }
        }

        return $next($request);
    }
}
