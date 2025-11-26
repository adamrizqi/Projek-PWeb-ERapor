<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CheckKelasAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $next($request);
        }

        if ($user->role === 'guru') {
            $kelasParam = $request->route('kelas') ?? $request->route('kelas_id') ?? $request->input('kelas_id');
            $kelasId = null;

            if ($kelasParam instanceof \App\Models\Kelas) {
                $kelasId = $kelasParam->id;
            } elseif (is_numeric($kelasParam)) {
                $kelasId = $kelasParam;
            }

            $siswaParam = $request->route('siswa') ?? $request->route('siswa_id') ?? $request->input('siswa_id');

            if ($siswaParam) {
                if ($siswaParam instanceof Siswa) {
                    $kelasId = $siswaParam->kelas_id;
                } else {
                    $siswa = Siswa::find($siswaParam);
                    if ($siswa) {
                        $kelasId = $siswa->kelas_id;
                    }
                }
            }

            if ($kelasId) {
                if ($user->kelas_id != $kelasId) {
                    Log::warning('Unauthorized kelas access attempt', [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_kelas_id' => $user->kelas_id,
                        'attempted_kelas_id' => $kelasId,
                        'url' => $request->fullUrl(),
                    ]);

                    return redirect()->route('guru.dashboard')
                        ->with('error', 'Anda tidak memiliki akses ke kelas tersebut.');
                }
            }
        }

        return $next($request);
    }
}
