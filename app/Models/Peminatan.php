<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminatan extends Model
{
    protected $table = 'peminatan';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'daftar_kelas',
    ];

    public function mataPelajaranPilihan()
    {
        return $this->hasMany(MataPelajaran::class, 'peminatan_id');
    }

    public function rumpunJurusan()
    {
        return $this->hasMany(RumpunJurusan::class, 'peminatan_id');
    }

    public function siswa()
    {
        return $this->hasMany(User::class, 'peminatan_id');
    }
}
