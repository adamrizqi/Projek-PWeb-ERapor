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

        // Admin punya akses ke semua kelas
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Guru hanya bisa akses kelas yang diampu
        if ($user->role === 'guru') {
            // Cek parameter kelas_id di route
            // Menangani jika parameter kelas berupa Object (Model Binding) atau ID
            $kelasParam = $request->route('kelas') ?? $request->route('kelas_id') ?? $request->input('kelas_id');
            $kelasId = null;

            if ($kelasParam instanceof \App\Models\Kelas) {
                $kelasId = $kelasParam->id;
            } elseif (is_numeric($kelasParam)) {
                $kelasId = $kelasParam;
            }

            // Cek parameter siswa_id di route
            // Menangani jika parameter siswa berupa Object (Model Binding) atau ID
            $siswaParam = $request->route('siswa') ?? $request->route('siswa_id') ?? $request->input('siswa_id');

            // Jika ada siswa, ambil kelas dari siswa tersebut
            if ($siswaParam) {
                if ($siswaParam instanceof Siswa) {
                    // KASUS 1: Route Model Binding (Sudah jadi Object)
                    $kelasId = $siswaParam->kelas_id;
                } else {
                    // KASUS 2: Masih berupa ID (Angka/String)
                    $siswa = Siswa::find($siswaParam);
                    if ($siswa) {
                        $kelasId = $siswa->kelas_id;
                    }
                }
            }

            // Validasi akses kelas
            if ($kelasId) {
                // Cek apakah guru mengampu kelas ini
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
