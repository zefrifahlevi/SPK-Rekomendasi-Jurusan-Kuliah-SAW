<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KriteriaSaw extends Model
{
    protected $table = 'kriteria_saw';

    protected $fillable = [
        'kode',
        'nama',
        'bobot',
        'jenis',
        'deskripsi',
    ];
}
