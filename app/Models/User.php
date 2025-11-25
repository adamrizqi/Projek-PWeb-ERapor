<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kelas_id',
        'nip',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    public function scopeGuru($query)
    {
        return $query->where('role', 'guru');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeTanpaKelas($query)
    {
        return $query->where('role', 'guru')->whereNull('kelas_id');
    }

    public function getNamaPendekAttribute()
    {
        $nama = $this->name;
        // Hilangkan gelar depan
        $nama = preg_replace('/^(Bapak|Ibu|Pak|Bu)\s+/i', '', $nama);
        // Hilangkan gelar belakang
        $nama = preg_replace('/,?\s*(S\.Pd|M\.Pd|S\.Ag|M\.Ag).*$/i', '', $nama);
        return trim($nama);
    }

    public function getIsAdminAttribute()
    {
        return $this->role === 'admin';
    }

    public function getIsGuruAttribute()
    {
        return $this->role === 'guru';
    }

    public function getIsWaliKelasAttribute()
    {
        return $this->kelasWali()->exists();
    }

    public function getSiswaKelas()
    {
        if ($this->is_guru && $this->kelas_id) {
            return Siswa::where('kelas_id', $this->kelas_id)
                ->where('status', 'aktif')
                ->get();
        }
        return collect();
    }

    public function getTotalSiswaKelas()
    {
        return $this->getSiswaKelas()->count();
    }
}
