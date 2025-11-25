<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'nama_mapel',
        'kode_mapel',
        'tingkat',
        'kkm',
    ];

    protected $casts = [
        'tingkat' => 'integer',
        'kkm' => 'integer',
    ];

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function scopeTingkat($query, $tingkat)
    {
        return $query->where('tingkat', $tingkat);
    }

    public function scopeKelasRendah($query)
    {
        return $query->whereIn('tingkat', [1, 2, 3]);
    }

    public function scopeKelasTinggi($query)
    {
        return $query->whereIn('tingkat', [4, 5, 6]);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_mapel', 'like', "%{$keyword}%")
              ->orWhere('kode_mapel', 'like', "%{$keyword}%");
        });
    }

    public function getNamaLengkapAttribute()
    {
        return "{$this->nama_mapel} (Tingkat {$this->tingkat})";
    }

    public function getKategoriAttribute()
    {
        $agama = ['PAI', 'PAKRISTEN', 'PAKATOLIK', 'PAHINDU', 'PABUDHA', 'PAKONGHUCU'];
        $kode = strtoupper(explode('-', $this->kode_mapel)[0]);

        if (in_array($kode, $agama)) {
            return 'Agama';
        }

        if (in_array($kode, ['PP', 'PPKN'])) {
            return 'Kewarganegaraan';
        }

        if (in_array($kode, ['BIND'])) {
            return 'Bahasa';
        }

        if (in_array($kode, ['MTK'])) {
            return 'Matematika';
        }

        if (in_array($kode, ['IPA', 'IPS'])) {
            return 'Ilmu Pengetahuan';
        }

        if (in_array($kode, ['PJOK'])) {
            return 'Jasmani & Kesehatan';
        }

        if (in_array($kode, ['SBD', 'SENIBUDAYA'])) {
            return 'Seni & Budaya';
        }

        return 'Lainnya';
    }

    public function getRataRataKelas($kelasId, $semester, $tahunAjaran)
    {
        return $this->nilai()
            ->whereHas('siswa', function ($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->where('status', 'aktif');
            })
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->whereNotNull('nilai_akhir')
            ->avg('nilai_akhir');
    }

    public function getJumlahSiswaTuntas($kelasId, $semester, $tahunAjaran)
    {
        return $this->nilai()
            ->whereHas('siswa', function ($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->where('status', 'aktif');
            })
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('nilai_akhir', '>=', $this->kkm)
            ->count();
    }

    public function getJumlahSiswaBelumTuntas($kelasId, $semester, $tahunAjaran)
    {
        return $this->nilai()
            ->whereHas('siswa', function ($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId)
                      ->where('status', 'aktif');
            })
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('nilai_akhir', '<', $this->kkm)
            ->count();
    }

    public static function konversiPredikat($nilai)
    {
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= 70) return 'C';
        return 'D';
    }

    public static function getDeskripsiPredikat($predikat)
    {
        $deskripsi = [
            'A' => 'Sangat Baik',
            'B' => 'Baik',
            'C' => 'Cukup',
            'D' => 'Perlu Bimbingan',
        ];

        return $deskripsi[$predikat] ?? '-';
    }
}
