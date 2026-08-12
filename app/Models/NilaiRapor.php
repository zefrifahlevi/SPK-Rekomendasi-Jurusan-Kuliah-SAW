<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiRapor extends Model
{
    protected $table = 'nilai_rapor';

    protected $fillable = [
        'siswa_id',
        'mata_pelajaran_id',
        'semester',
        'nilai',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }
}
