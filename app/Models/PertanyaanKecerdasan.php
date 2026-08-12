<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PertanyaanKecerdasan extends Model
{
    protected $table = 'pertanyaan_kecerdasan';

    protected $fillable = [
        'kategori_kecerdasan_id',
        'pertanyaan',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriKecerdasan::class, 'kategori_kecerdasan_id');
    }

    public function jawaban()
    {
        return $this->hasMany(JawabanKecerdasan::class, 'pertanyaan_kecerdasan_id');
    }
}
