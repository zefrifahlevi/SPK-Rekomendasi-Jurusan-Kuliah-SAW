<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKecerdasan extends Model
{
    protected $table = 'kategori_kecerdasan';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
    ];

    public function pertanyaan()
    {
        return $this->hasMany(PertanyaanKecerdasan::class, 'kategori_kecerdasan_id');
    }
}
