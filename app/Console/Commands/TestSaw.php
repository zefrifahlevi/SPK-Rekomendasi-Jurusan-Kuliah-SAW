<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\SawService;

class TestSaw extends Command
{
    protected $signature = 'test:saw';
    protected $description = 'Test SAW calculation';

    public function handle(SawService $sawService)
    {
        $siswa = User::where('role', 'siswa')->first();
        if (!$siswa) {
            $this->error('No student found');
            return;
        }

        $res = $sawService->hitungRekomendasi($siswa);
        $this->info("SAW Result for {$siswa->name} ({$siswa->kelas}): Status = {$res['status']}");
        $this->info("Top Recommended Major: " . $res['hasil'][0]['jurusan']['nama'] . " (Score V: " . $res['hasil'][0]['nilai_v'] . ")");
    }
}
