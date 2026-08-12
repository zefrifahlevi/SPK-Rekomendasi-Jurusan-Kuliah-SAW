<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan';

    protected $fillable = [
        'rumpun_id',
        'nama',
        'deskripsi',
        'prospek_kerja',
        'kategori_kecerdasan_utama_id',
        'mapel_utama_id',
    ];

    public function rumpun()
    {
        return $this->belongsTo(RumpunJurusan::class, 'rumpun_id');
    }

    public function kecerdasanUtama()
    {
        return $this->belongsTo(KategoriKecerdasan::class, 'kategori_kecerdasan_utama_id');
    }

    public function mapelUtama()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_utama_id');
    }

    public function hasilSaw()
    {
        return $this->hasMany(HasilSaw::class, 'jurusan_id');
    }
}
