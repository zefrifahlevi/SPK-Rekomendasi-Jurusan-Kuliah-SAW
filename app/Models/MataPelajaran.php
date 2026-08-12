<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'peminatan_id',
    ];

    public function peminatan()
    {
        return $this->belongsTo(Peminatan::class, 'peminatan_id');
    }

    public function nilaiRapor()
    {
        return $this->hasMany(NilaiRapor::class, 'mata_pelajaran_id');
    }
}
