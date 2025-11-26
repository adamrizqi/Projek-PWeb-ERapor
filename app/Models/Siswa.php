<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'alamat',
        'nama_wali',
        'phone_wali',
        'foto',
        'kelas_id',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    protected $appends = [
        'foto_url',
        'umur',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class);
    }

    public function sikap()
    {
        return $this->hasMany(Sikap::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_lengkap', 'like', "%{$keyword}%")
              ->orWhere('nis', 'like', "%{$keyword}%")
              ->orWhere('nisn', 'like', "%{$keyword}%");
        });
    }

    /**
     * Get URL foto siswa (Cloudinary Support)
     */
    public function getFotoUrlAttribute()
    {
        if (empty($this->foto)) {
            return $this->defaultAvatar();
        }

        if (str_starts_with($this->foto, 'http')) {
            return $this->foto;
        }

        return 'https://res.cloudinary.com/dcucamoen/image/upload/' . $this->foto;
    }

    /**
     * Helper untuk avatar default
     */
    private function defaultAvatar()
    {
        return $this->jenis_kelamin === 'L'
             ? asset('images/avatar-male.png')
             : asset('images/avatar-female.png');
    }

    public function getUmurAttribute()
    {
        return $this->tanggal_lahir
             ? Carbon::parse($this->tanggal_lahir)->age
             : null;
    }

    public function getTempatTanggalLahirAttribute()
    {
        if (!$this->tanggal_lahir) {
            return '-';
        }
        $tanggal = Carbon::parse($this->tanggal_lahir)->isoFormat('D MMMM Y');
        return "{$this->tempat_lahir}, {$tanggal}";
    }

    public function getJenisKelaminLengkapAttribute()
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getNamaKelasAttribute()
    {
        return $this->kelas ? $this->kelas->nama_kelas : '-';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'aktif' => 'success',
            'lulus' => 'info',
            'pindah' => 'warning',
            'keluar' => 'danger',
        ];
        return $badges[$this->status] ?? 'secondary';
    }


    public function getNilai($semester, $tahunAjaran)
    {
        return $this->nilai()
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->with('mataPelajaran')
            ->get();
    }

    public function getRataRataNilai($semester, $tahunAjaran)
    {
        $rataRata = $this->nilai()
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->whereNotNull('nilai_akhir')
            ->avg('nilai_akhir');
        return $rataRata ? round($rataRata, 2) : 0;
    }

    public function getRankingKelas($semester, $tahunAjaran)
    {
        $siswaKelas = Siswa::where('kelas_id', $this->kelas_id)
            ->where('status', 'aktif')
            ->get();

        $rankings = [];
        foreach ($siswaKelas as $siswa) {
            $rankings[] = [
                'id' => $siswa->id,
                'nilai' => $siswa->getRataRataNilai($semester, $tahunAjaran)
            ];
        }

        usort($rankings, fn($a, $b) => $b['nilai'] <=> $a['nilai']);

        foreach ($rankings as $rank => $data) {
            if ($data['id'] == $this->id) {
                return $rank + 1;
            }
        }
        return '-';
    }

    public function getKehadiran($semester, $tahunAjaran)
    {
        return $this->kehadiran()
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->first();
    }

    public function getSikap($semester, $tahunAjaran)
    {
        return $this->sikap()
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->first();
    }

    public function getPersentaseKelengkapanRapor($semester, $tahunAjaran)
    {
        $komponen = 0;

        if ($this->nilai()->where('semester', $semester)->count() > 0) $komponen++;

        if ($this->getKehadiran($semester, $tahunAjaran)) $komponen++;

        if ($this->getSikap($semester, $tahunAjaran)) $komponen++;

        return round(($komponen / 3) * 100);
    }

    public function isRaporLengkap($semester, $tahunAjaran)
    {
        return $this->getPersentaseKelengkapanRapor($semester, $tahunAjaran) == 100;
    }

    public function setNamaLengkapAttribute($value)
    {
        $this->attributes['nama_lengkap'] = ucwords(strtolower($value));
    }

    public function setNisAttribute($value)
    {
        $this->attributes['nis'] = strtoupper($value);
    }
}
