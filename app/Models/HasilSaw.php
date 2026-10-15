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

    protected $appends = [
        'is_linier',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function getIsLinierAttribute(): bool
    {
        if (isset($this->detail_kalkulasi['is_linier'])) {
            return (bool) $this->detail_kalkulasi['is_linier'];
        }
        if ($this->siswa && $this->jurusan && $this->jurusan->rumpun) {
            return $this->siswa->peminatan_id == $this->jurusan->rumpun->peminatan_id;
        }
        return true;
    }
}
