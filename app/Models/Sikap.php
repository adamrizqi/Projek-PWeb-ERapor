<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sikap extends Model
{
    use HasFactory;

    protected $table = 'sikap';

    protected $fillable = [
        'siswa_id',
        'semester',
        'tahun_ajaran',
        'sikap_spiritual',
        'sikap_sosial',
    ];

    protected $casts = [
        'semester' => 'integer',
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

    public function scopeLengkap($query)
    {
        return $query->whereNotNull('sikap_spiritual')
                    ->whereNotNull('sikap_sosial');
    }

    public function getIsLengkapAttribute()
    {
        return !empty($this->sikap_spiritual) && !empty($this->sikap_sosial);
    }

    public function getProgressAttribute()
    {
        $progress = 0;
        if (!empty($this->sikap_spiritual)) $progress += 50;
        if (!empty($this->sikap_sosial)) $progress += 50;
        return $progress;
    }

    public static function getTemplateSikapSpiritual()
    {
        return [
            'sangat_baik' => [
                'Sangat taat menjalankan ibadah sesuai agamanya',
                'Selalu berdoa sebelum dan sesudah belajar',
                'Menghormati teman yang berbeda agama',
                'Aktif dalam kegiatan keagamaan di sekolah',
                'Memiliki akhlak yang terpuji dan menjadi teladan',
            ],
            'baik' => [
                'Taat menjalankan ibadah sesuai agamanya',
                'Berdoa sebelum dan sesudah belajar',
                'Menghormati teman yang berbeda agama',
                'Mengikuti kegiatan keagamaan di sekolah',
                'Memiliki akhlak yang baik',
            ],
            'cukup' => [
                'Cukup taat menjalankan ibadah',
                'Kadang-kadang berdoa sebelum belajar',
                'Mulai belajar menghormati perbedaan agama',
                'Perlu dorongan untuk mengikuti kegiatan keagamaan',
                'Perlu bimbingan dalam pembentukan akhlak',
            ],
            'perlu_bimbingan' => [
                'Perlu bimbingan dalam menjalankan ibadah',
                'Perlu diingatkan untuk berdoa',
                'Perlu pemahaman tentang toleransi beragama',
                'Kurang aktif dalam kegiatan keagamaan',
                'Perlu pembinaan akhlak secara intensif',
            ],
        ];
    }

    public static function getTemplateSikapSosial()
    {
        return [
            'sangat_baik' => [
                'Sangat santun dan hormat kepada guru dan teman',
                'Selalu membantu teman yang kesulitan',
                'Aktif bekerjasama dalam kegiatan kelompok',
                'Jujur dan bertanggung jawab atas tugas',
                'Memiliki kepedulian tinggi terhadap lingkungan',
                'Menunjukkan sikap disiplin yang sangat baik',
            ],
            'baik' => [
                'Santun dan hormat kepada guru dan teman',
                'Membantu teman yang kesulitan',
                'Bekerjasama dengan baik dalam kelompok',
                'Jujur dan bertanggung jawab',
                'Peduli terhadap lingkungan sekitar',
                'Menunjukkan sikap disiplin yang baik',
            ],
            'cukup' => [
                'Cukup santun kepada guru dan teman',
                'Kadang membantu teman yang kesulitan',
                'Mulai belajar bekerjasama dalam kelompok',
                'Perlu diingatkan untuk jujur dan bertanggung jawab',
                'Mulai peduli terhadap lingkungan',
                'Perlu peningkatan kedisiplinan',
            ],
            'perlu_bimbingan' => [
                'Perlu bimbingan dalam kesantunan',
                'Perlu didorong untuk membantu teman',
                'Perlu bimbingan dalam kerjasama kelompok',
                'Perlu pembinaan tentang kejujuran dan tanggung jawab',
                'Perlu ditingkatkan kepeduliannya terhadap lingkungan',
                'Perlu pembinaan kedisiplinan',
            ],
        ];
    }

    public function generateSikapSpiritual($kategori = 'baik')
    {
        $template = self::getTemplateSikapSpiritual();

        if (!isset($template[$kategori])) {
            $kategori = 'baik';
        }

        $deskripsi = $template[$kategori];

        $pilihan = array_rand($deskripsi, min(3, count($deskripsi)));

        if (!is_array($pilihan)) {
            $pilihan = [$pilihan];
        }

        $hasil = [];
        foreach ($pilihan as $index) {
            $hasil[] = $deskripsi[$index];
        }

        return implode('. ', $hasil) . '.';
    }

    public function generateSikapSosial($kategori = 'baik')
    {
        $template = self::getTemplateSikapSosial();

        if (!isset($template[$kategori])) {
            $kategori = 'baik';
        }

        $deskripsi = $template[$kategori];

        $pilihan = array_rand($deskripsi, min(3, count($deskripsi)));

        if (!is_array($pilihan)) {
            $pilihan = [$pilihan];
        }

        $hasil = [];
        foreach ($pilihan as $index) {
            $hasil[] = $deskripsi[$index];
        }

        return implode('. ', $hasil) . '.';
    }

    public function hasSikapNegatif()
    {
        $kataNegative = ['perlu bimbingan', 'kurang', 'belum', 'tidak', 'kadang'];

        $spiritual = strtolower($this->sikap_spiritual ?? '');
        $sosial = strtolower($this->sikap_sosial ?? '');

        foreach ($kataNegative as $kata) {
            if (str_contains($spiritual, $kata) || str_contains($sosial, $kata)) {
                return true;
            }
        }

        return false;
    }

    public function getRingkasan()
    {
        $spiritual = strlen($this->sikap_spiritual ?? '') > 100
            ? substr($this->sikap_spiritual, 0, 100) . '...'
            : $this->sikap_spiritual;

        $sosial = strlen($this->sikap_sosial ?? '') > 100
            ? substr($this->sikap_sosial, 0, 100) . '...'
            : $this->sikap_sosial;

        return [
            'spiritual' => $spiritual,
            'sosial' => $sosial,
            'lengkap' => $this->is_lengkap,
            'progress' => $this->progress,
        ];
    }
}
