<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GuruMiddleware
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
                ->with('error', 'Silakan login sebagai guru terlebih dahulu.');
        }

        $user = Auth::user();

        if ($user->role !== 'guru') {
            Log::warning('Unauthorized guru access attempt', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'role' => $user->role,
                'attempted_url' => $request->fullUrl(),
                'ip' => $request->ip(),
            ]);

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses guru.');
            }

            return redirect()->route('home')
                ->with('error', 'Akses ditolak. Anda bukan guru.');
        }

        if (!$user->kelas_id) {
            return redirect()->route('guru.dashboard')
                ->with('warning', 'Anda belum ditugaskan ke kelas manapun. Hubungi admin untuk penugasan kelas.');
        }
        config(['app.timezone' => 'Asia/Jakarta']);

        return $next($request);
    }
}
