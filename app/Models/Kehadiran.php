<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

    protected $table = 'kehadiran';

    protected $fillable = [
        'siswa_id',
        'semester',
        'tahun_ajaran',
        'hadir',
        'sakit',
        'izin',
        'alpa',
    ];

    protected $casts = [
        'semester' => 'integer',
        'hadir' => 'integer',
        'sakit' => 'integer',
        'izin' => 'integer',
        'alpa' => 'integer',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function scopeSemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeTahunAjaran($query, $tahunAjaran)
    {
        return $query->where('tahun_ajaran', $tahunAjaran);
    }

    public function scopeKelas($query, $kelasId)
    {
        return $query->whereHas('siswa', function ($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId);
        });
    }

    public function getTotalTidakHadirAttribute()
    {
        return $this->sakit + $this->izin + $this->alpa;
    }

    public function getTotalHariAttribute()
    {
        return $this->hadir + $this->sakit + $this->izin + $this->alpa;
    }

    public function getPersentaseKehadiranAttribute()
    {
        $total = $this->total_hari;
        if ($total == 0) {
            return 0;
        }
        return round(($this->hadir / $total) * 100, 2);
    }

    public function getStatusKehadiranAttribute()
    {
        $persentase = $this->persentase_kehadiran;

        if ($persentase >= 95) {
            return 'Sangat Baik';
        } elseif ($persentase >= 90) {
            return 'Baik';
        } elseif ($persentase >= 80) {
            return 'Cukup';
        } else {
            return 'Kurang';
        }
    }

    public function getStatusBadgeAttribute()
    {
        $persentase = $this->persentase_kehadiran;

        if ($persentase >= 95) {
            return 'success';
        } elseif ($persentase >= 90) {
            return 'info';
        } elseif ($persentase >= 80) {
            return 'warning';
        } else {
            return 'danger';
        }
    }

    public function getIsAlpaTinggiAttribute()
    {
        return $this->alpa > 5; // Lebih dari 5 hari alpa
    }

    public function getRekapan()
    {
        return [
            'hadir' => $this->hadir,
            'sakit' => $this->sakit,
            'izin' => $this->izin,
            'alpa' => $this->alpa,
            'total_tidak_hadir' => $this->total_tidak_hadir,
            'total_hari' => $this->total_hari,
            'persentase' => $this->persentase_kehadiran,
            'status' => $this->status_kehadiran,
        ];
    }

    public function tambahHadir($jumlah = 1)
    {
        $this->hadir += $jumlah;
        $this->save();
    }

    public function tambahSakit($jumlah = 1)
    {
        $this->sakit += $jumlah;
        $this->save();
    }

    public function tambahIzin($jumlah = 1)
    {
        $this->izin += $jumlah;
        $this->save();
    }

    public function tambahAlpa($jumlah = 1)
    {
        $this->alpa += $jumlah;
        $this->save();
    }

    public function reset()
    {
        $this->hadir = 0;
        $this->sakit = 0;
        $this->izin = 0;
        $this->alpa = 0;
        $this->save();
    }

    public function generateCatatanRapor()
    {
        $catatan = "Kehadiran: {$this->hadir} hari";

        if ($this->sakit > 0) {
            $catatan .= ", Sakit: {$this->sakit} hari";
        }

        if ($this->izin > 0) {
            $catatan .= ", Izin: {$this->izin} hari";
        }

        if ($this->alpa > 0) {
            $catatan .= ", Alpa: {$this->alpa} hari";
        }

        return $catatan;
    }
}
