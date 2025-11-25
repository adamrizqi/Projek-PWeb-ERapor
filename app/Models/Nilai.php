<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'siswa_id',
        'mata_pelajaran_id',
        'semester',
        'tahun_ajaran',
        'nilai_pengetahuan',
        'nilai_keterampilan',
        'nilai_akhir',
        'predikat',
        'deskripsi',
    ];

    protected $casts = [
        'semester' => 'integer',
        'nilai_pengetahuan' => 'integer',
        'nilai_keterampilan' => 'integer',
        'nilai_akhir' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto calculate nilai_akhir dan predikat sebelum save
        static::saving(function ($nilai) {
            if ($nilai->nilai_pengetahuan !== null && $nilai->nilai_keterampilan !== null) {
                $nilai->nilai_akhir = round(($nilai->nilai_pengetahuan + $nilai->nilai_keterampilan) / 2);
                $nilai->predikat = MataPelajaran::konversiPredikat($nilai->nilai_akhir);
            }
        });
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function scopeSemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeTahunAjaran($query, $tahunAjaran)
    {
        return $query->where('tahun_ajaran', $tahunAjaran);
    }

    public function scopeLengkap($query)
    {
        return $query->whereNotNull('nilai_pengetahuan')
                    ->whereNotNull('nilai_keterampilan')
                    ->whereNotNull('nilai_akhir');
    }

    public function scopeTuntas($query)
    {
        return $query->whereRaw('nilai_akhir >= (SELECT kkm FROM mata_pelajaran WHERE id = nilai.mata_pelajaran_id)');
    }

    public function scopeBelumTuntas($query)
    {
        return $query->whereRaw('nilai_akhir < (SELECT kkm FROM mata_pelajaran WHERE id = nilai.mata_pelajaran_id)');
    }

    public function getDeskripsiPredikatAttribute()
    {
        return MataPelajaran::getDeskripsiPredikat($this->predikat);
    }

    public function getIsTuntasAttribute()
    {
        if (!$this->nilai_akhir || !$this->mataPelajaran) {
            return false;
        }
        return $this->nilai_akhir >= $this->mataPelajaran->kkm;
    }

    public function getSelisihKkmAttribute()
    {
        if (!$this->nilai_akhir || !$this->mataPelajaran) {
            return 0;
        }
        return $this->nilai_akhir - $this->mataPelajaran->kkm;
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->nilai_akhir) {
            return 'secondary';
        }
        return $this->is_tuntas ? 'success' : 'danger';
    }

    public function getStatusTextAttribute()
    {
        if (!$this->nilai_akhir) {
            return 'Belum Dinilai';
        }
        return $this->is_tuntas ? 'Tuntas' : 'Belum Tuntas';
    }

    public function generateDeskripsi()
    {
        if (!$this->mataPelajaran || !$this->nilai_akhir) {
            return null;
        }

        $namaMapel = $this->mataPelajaran->nama_mapel;
        $nilai = $this->nilai_akhir;

        if ($nilai >= 90) {
            $deskripsi = "Sangat menguasai kompetensi {$namaMapel} dengan sangat baik. Mampu menerapkan konsep dengan tepat dan mandiri.";
        } elseif ($nilai >= 80) {
            $deskripsi = "Menguasai kompetensi {$namaMapel} dengan baik. Mampu menerapkan konsep dengan cukup baik.";
        } elseif ($nilai >= 70) {
            $deskripsi = "Cukup menguasai kompetensi {$namaMapel}. Perlu latihan lebih untuk meningkatkan pemahaman.";
        } else {
            $deskripsi = "Perlu bimbingan lebih dalam memahami kompetensi {$namaMapel}. Disarankan untuk lebih sering berlatih.";
        }

        return $deskripsi;
    }

    public function updateDeskripsi()
    {
        if (!$this->deskripsi) {
            $this->deskripsi = $this->generateDeskripsi();
            $this->save();
        }
    }
}
