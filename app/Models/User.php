<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nisn',
        'kelas',
        'peminatan_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isGuruBk(): bool
    {
        return $this->role === 'guru_bk';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function peminatan()
    {
        return $this->belongsTo(Peminatan::class, 'peminatan_id');
    }

    public function nilaiRapor()
    {
        return $this->hasMany(NilaiRapor::class, 'siswa_id');
    }

    public function jawabanKecerdasan()
    {
        return $this->hasMany(JawabanKecerdasan::class, 'siswa_id');
    }

    public function jawabanMinat()
    {
        return $this->hasMany(JawabanMinat::class, 'siswa_id');
    }

    public function hasilSaw()
    {
        return $this->hasMany(HasilSaw::class, 'siswa_id');
    }
}
