<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            Log::warning('Unauthorized admin access attempt', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'role' => $user->role,
                'attempted_url' => $request->fullUrl(),
                'ip' => $request->ip(),
            ]);

            if ($user->role === 'guru') {
                return redirect()->route('guru.dashboard')
                    ->with('error', 'Anda tidak memiliki akses admin.');
            }

            return redirect()->route('home')
                ->with('error', 'Akses ditolak. Anda bukan admin.');
        }

        config(['app.timezone' => 'Asia/Jakarta']);

        return $next($request);
    }
}
