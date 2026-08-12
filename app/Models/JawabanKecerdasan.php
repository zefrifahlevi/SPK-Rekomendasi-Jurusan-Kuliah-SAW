<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanKecerdasan extends Model
{
    protected $table = 'jawaban_kecerdasan';

    protected $fillable = [
        'siswa_id',
        'pertanyaan_kecerdasan_id',
        'skor',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(PertanyaanKecerdasan::class, 'pertanyaan_kecerdasan_id');
    }
}
