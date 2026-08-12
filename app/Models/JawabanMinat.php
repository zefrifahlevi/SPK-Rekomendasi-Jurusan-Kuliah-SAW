<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanMinat extends Model
{
    protected $table = 'jawaban_minat';

    protected $fillable = [
        'siswa_id',
        'pertanyaan_minat_id',
        'skor',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(PertanyaanMinat::class, 'pertanyaan_minat_id');
    }
}
