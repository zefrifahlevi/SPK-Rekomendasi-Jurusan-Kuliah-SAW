<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RumpunJurusan extends Model
{
    protected $table = 'rumpun_jurusan';

    protected $fillable = [
        'peminatan_id',
        'nama',
    ];

    public function peminatan()
    {
        return $this->belongsTo(Peminatan::class, 'peminatan_id');
    }

    public function jurusan()
    {
        return $this->hasMany(Jurusan::class, 'rumpun_id');
    }
}
