<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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

    public function scopeLulus($query)
    {
        return $query->where('status', 'lulus');
    }

    public function scopeKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    public function scopeJenisKelamin($query, $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_lengkap', 'like', "%{$keyword}%")
              ->orWhere('nis', 'like', "%{$keyword}%")
              ->orWhere('nisn', 'like', "%{$keyword}%");
        });
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return Storage::url($this->foto);
        }

        // Default avatar berdasarkan jenis kelamin
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
                'siswa_id' => $siswa->id,
                'rata_rata' => $siswa->getRataRataNilai($semester, $tahunAjaran),
            ];
        }

        // Sort descending
        usort($rankings, function ($a, $b) {
            return $b['rata_rata'] <=> $a['rata_rata'];
        });

        // Cari ranking siswa ini
        foreach ($rankings as $index => $rank) {
            if ($rank['siswa_id'] === $this->id) {
                return $index + 1;
            }
        }

        return null;
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

    public function isRaporLengkap($semester, $tahunAjaran)
    {
        $mataPelajaran = MataPelajaran::where('tingkat', $this->kelas->tingkat)->count();
        $nilaiAda = $this->nilai()
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->whereNotNull('nilai_akhir')
            ->count();

        $kehadiranAda = $this->kehadiran()
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->exists();

        $sikapAda = $this->sikap()
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->exists();

        return $nilaiAda === $mataPelajaran && $kehadiranAda && $sikapAda;
    }

    public function getPersentaseKelengkapanRapor($semester, $tahunAjaran)
    {
        $totalKomponen = 3; // Nilai, Kehadiran, Sikap
        $komponen = 0;

        // Cek nilai
        $mataPelajaran = MataPelajaran::where('tingkat', $this->kelas->tingkat)->count();
        $nilaiAda = $this->nilai()
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->whereNotNull('nilai_akhir')
            ->count();
        if ($nilaiAda === $mataPelajaran) {
            $komponen++;
        }

        // Cek kehadiran
        if ($this->getKehadiran($semester, $tahunAjaran)) {
            $komponen++;
        }

        // Cek sikap
        if ($this->getSikap($semester, $tahunAjaran)) {
            $komponen++;
        }

        return round(($komponen / $totalKomponen) * 100);
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
