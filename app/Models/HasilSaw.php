<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilSaw extends Model
{
    protected $table = 'hasil_saw';

    protected $fillable = [
        'siswa_id',
        'jurusan_id',
        'skor_rapor',
        'skor_kecerdasan',
        'skor_minat',
        'nilai_v',
        'ranking',
        'detail_kalkulasi',
    ];

    protected $casts = [
        'detail_kalkulasi' => 'array',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
}
