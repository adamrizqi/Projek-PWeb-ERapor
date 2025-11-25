<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{

    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'tahun_ajaran',
        'wali_kelas_id',
    ];

    protected $casts = [
        'tingkat' => 'integer',
    ];

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function siswaAktif()
    {
        return $this->hasMany(Siswa::class)->where('status', 'aktif');
    }

    public function scopeTingkat($query, $tingkat)
    {
        return $query->where('tingkat', $tingkat);
    }

    public function scopeTahunAjaran($query, $tahunAjaran)
    {
        return $query->where('tahun_ajaran', $tahunAjaran);
    }

    public function scopeWithWaliKelas($query)
    {
        return $query->whereNotNull('wali_kelas_id');
    }

    public function scopeWithoutWaliKelas($query)
    {
        return $query->whereNull('wali_kelas_id');
    }

    public function getNamaLengkapAttribute()
    {
        return "Kelas {$this->nama_kelas} - Tingkat {$this->tingkat}";
    }

    public function getJumlahSiswaAttribute()
    {
        return $this->siswaAktif()->count();
    }

    public function getNamaWaliKelasAttribute()
    {
        return $this->waliKelas ? $this->waliKelas->nama_pendek : '-';
    }

    public function getIsKelasRendahAttribute()
    {
        return $this->tingkat >= 1 && $this->tingkat <= 3;
    }

    public function getIsKelasTinggiAttribute()
    {
        return $this->tingkat >= 4 && $this->tingkat <= 6;
    }

    public function getMataPelajaran()
    {
        return MataPelajaran::where('tingkat', $this->tingkat)->get();
    }

    public function getRataRataNilaiKelas()
    {
        $siswa = $this->siswaAktif;
        if ($siswa->isEmpty()) {
            return 0;
        }

        $totalNilai = 0;
        $totalMapel = 0;

        foreach ($siswa as $s) {
            $nilai = $s->nilai()->whereNotNull('nilai_akhir')->avg('nilai_akhir');
            if ($nilai) {
                $totalNilai += $nilai;
                $totalMapel++;
            }
        }

        return $totalMapel > 0 ? round($totalNilai / $totalMapel, 2) : 0;
    }

    public function getStatistikKehadiran($semester, $tahunAjaran)
    {
        $siswa = $this->siswaAktif;
        $totalHadir = 0;
        $totalSakit = 0;
        $totalIzin = 0;
        $totalAlpa = 0;

        foreach ($siswa as $s) {
            $kehadiran = $s->kehadiran()
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->first();

            if ($kehadiran) {
                $totalHadir += $kehadiran->hadir;
                $totalSakit += $kehadiran->sakit;
                $totalIzin += $kehadiran->izin;
                $totalAlpa += $kehadiran->alpa;
            }
        }

        return [
            'hadir' => $totalHadir,
            'sakit' => $totalSakit,
            'izin' => $totalIzin,
            'alpa' => $totalAlpa,
        ];
    }
}
