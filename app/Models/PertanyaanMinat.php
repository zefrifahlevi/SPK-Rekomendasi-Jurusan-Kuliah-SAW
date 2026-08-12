<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PertanyaanMinat extends Model
{
    protected $table = 'pertanyaan_minat';

    protected $fillable = [
        'kategori_target',
        'pertanyaan',
    ];

    public function jawaban()
    {
        return $this->hasMany(JawabanMinat::class, 'pertanyaan_minat_id');
    }
}
