<?php

namespace App\Services;

use App\Models\User;
use App\Models\Jurusan;
use App\Models\KriteriaSaw;
use App\Models\HasilSaw;
use App\Models\NilaiRapor;
use App\Models\JawabanKecerdasan;
use App\Models\JawabanMinat;
use App\Models\MataPelajaran;
use App\Models\KategoriKecerdasan;

class SawService
{
    /**
     * Calculate SAW recommendation for a given student.
     *
     * @param User $siswa
     * @return array
     */
    public function hitungRekomendasi(User $siswa): array
    {
        // 1. Fetch criteria and weights
        $kriteriaList = KriteriaSaw::all();
        if ($kriteriaList->isEmpty()) {
            return ['status' => 'error', 'message' => 'Kriteria SAW belum dikonfigurasi.'];
        }

        // Get weights
        $wRapor = (float) ($kriteriaList->firstWhere('kode', 'C1')->bobot ?? 0.40);
        $wKecerdasan = (float) ($kriteriaList->firstWhere('kode', 'C2')->bobot ?? 0.30);
        $wMinat = (float) ($kriteriaList->firstWhere('kode', 'C3')->bobot ?? 0.30);

        // 2. Fetch candidate majors suitable for student's Peminatan
        $peminatanId = $siswa->peminatan_id;
        $jurusanQuery = Jurusan::with(['rumpun', 'kecerdasanUtama', 'mapelUtama']);

        if ($peminatanId) {
            $jurusanQuery->whereHas('rumpun', function ($q) use ($peminatanId) {
                $q->where('peminatan_id', $peminatanId);
            });
        }

        $candidateMajors = $jurusanQuery->get();

        if ($candidateMajors->isEmpty()) {
            // Fallback to all majors if no peminatan set
            $candidateMajors = Jurusan::with(['rumpun', 'kecerdasanUtama', 'mapelUtama'])->get();
        }

        if ($candidateMajors->isEmpty()) {
            return ['status' => 'error', 'message' => 'Tidak ada data jurusan yang tersedia.'];
        }

        // 3. Pre-calculate Student Raw Inputs
        // A. Nilai Rapor (Semester 1-5)
        $nilaiRaporItems = NilaiRapor::where('siswa_id', $siswa->id)->get();
        $avgRaporUtama = $nilaiRaporItems->avg('nilai') ?? 75.0;

        // Group avg by mapel_id
        $avgByMapel = $nilaiRaporItems->groupBy('mata_pelajaran_id')->map(function ($items) {
            return $items->avg('nilai');
        });

        // B. Skor Kecerdasan Majemuk per Kategori
        $jawabanKecerdasan = JawabanKecerdasan::with('pertanyaan.kategori')
            ->where('siswa_id', $siswa->id)
            ->get();

        $skorKecerdasanPerKategori = [];
        $kategoriList = KategoriKecerdasan::all();
        foreach ($kategoriList as $kat) {
            $katJawab = $jawabanKecerdasan->filter(function ($j) use ($kat) {
                return $j->pertanyaan && $j->pertanyaan->kategori_kecerdasan_id == $kat->id;
            });
            $avgSkor = $katJawab->avg('skor') ?? 3.0; // scale 1-5
            // Convert to 0-100 scale
            $skorKecerdasanPerKategori[$kat->id] = ($avgSkor / 5.0) * 100;
        }

        // C. Skor Minat per Kategori Target
        $jawabanMinat = JawabanMinat::with('pertanyaan')
            ->where('siswa_id', $siswa->id)
            ->get();

        $skorMinatPerKategoriTarget = [];
        foreach ($jawabanMinat as $jm) {
            if ($jm->pertanyaan) {
                $target = $jm->pertanyaan->kategori_target;
                if (!isset($skorMinatPerKategoriTarget[$target])) {
                    $skorMinatPerKategoriTarget[$target] = [];
                }
                $skorMinatPerKategoriTarget[$target][] = $jm->skor;
            }
        }
        $avgMinatPerTarget = [];
        foreach ($skorMinatPerKategoriTarget as $target => $skorArr) {
            $avg = array_sum($skorArr) / count($skorArr);
            $avgMinatPerTarget[$target] = ($avg / 5.0) * 100;
        }

        // 4. Build Decision Matrix (X)
        // Row: Major (Alternative), Col: Criteria C1, C2, C3
        $matrixX = [];
        foreach ($candidateMajors as $j) {
            // C1: Nilai Rapor
            // If major has mapelUtama, give 60% weight to mapelUtama score and 40% to overall avg
            if ($j->mapel_utama_id && isset($avgByMapel[$j->mapel_utama_id])) {
                $scoreMapel = $avgByMapel[$j->mapel_utama_id];
                $c1 = (0.60 * $scoreMapel) + (0.40 * $avgRaporUtama);
            } else {
                $c1 = $avgRaporUtama;
            }

            // C2: Kecerdasan Majemuk
            if ($j->kategori_kecerdasan_utama_id && isset($skorKecerdasanPerKategori[$j->kategori_kecerdasan_utama_id])) {
                $c2 = $skorKecerdasanPerKategori[$j->kategori_kecerdasan_utama_id];
            } else {
                $c2 = array_sum($skorKecerdasanPerKategori) / (count($skorKecerdasanPerKategori) ?: 1);
            }

            // C3: Minat Bakat
            $rumpunNama = $j->rumpun ? $j->rumpun->nama : '';
            $matchedMinat = 70.0; // default baseline
            foreach ($avgMinatPerTarget as $targetName => $val) {
                if (stripos($rumpunNama, $targetName) !== false || stripos($targetName, $j->nama) !== false) {
                    $matchedMinat = max($matchedMinat, $val);
                }
            }
            $c3 = $matchedMinat;

            $matrixX[$j->id] = [
                'jurusan' => $j,
                'c1' => round($c1, 4),
                'c2' => round($c2, 4),
                'c3' => round($c3, 4),
            ];
        }

        // 5. Normalization (R Matrix)
        // Benefit Criteria: r_ij = x_ij / max(x_ij)
        $maxC1 = max(array_column($matrixX, 'c1')) ?: 1;
        $maxC2 = max(array_column($matrixX, 'c2')) ?: 1;
        $maxC3 = max(array_column($matrixX, 'c3')) ?: 1;

        $matrixR = [];
        $hasilAkhir = [];

        foreach ($matrixX as $jId => $data) {
            $r1 = round($data['c1'] / $maxC1, 4);
            $r2 = round($data['c2'] / $maxC2, 4);
            $r3 = round($data['c3'] / $maxC3, 4);

            $matrixR[$jId] = [
                'r1' => $r1,
                'r2' => $r2,
                'r3' => $r3,
            ];

            // 6. Calculate Preference Score (V)
            // V_i = w1*r1 + w2*r2 + w3*r3
            $v = round(($wRapor * $r1) + ($wKecerdasan * $r2) + ($wMinat * $r3), 4);

            $hasilAkhir[] = [
                'jurusan_id' => $jId,
                'jurusan' => $data['jurusan'],
                'skor_rapor' => $data['c1'],
                'skor_kecerdasan' => $data['c2'],
                'skor_minat' => $data['c3'],
                'r1' => $r1,
                'r2' => $r2,
                'r3' => $r3,
                'nilai_v' => $v,
            ];
        }

        // Sort descending by V score
        usort($hasilAkhir, function ($a, $b) {
            return $b['nilai_v'] <=> $a['nilai_v'];
        });

        // Add ranking
        foreach ($hasilAkhir as $index => &$item) {
            $item['ranking'] = $index + 1;
        }

        // Save results to DB
        HasilSaw::where('siswa_id', $siswa->id)->delete();

        foreach ($hasilAkhir as $item) {
            HasilSaw::create([
                'siswa_id' => $siswa->id,
                'jurusan_id' => $item['jurusan_id'],
                'skor_rapor' => $item['skor_rapor'],
                'skor_kecerdasan' => $item['skor_kecerdasan'],
                'skor_minat' => $item['skor_minat'],
                'nilai_v' => $item['nilai_v'],
                'ranking' => $item['ranking'],
                'detail_kalkulasi' => [
                    'c1' => $item['skor_rapor'],
                    'c2' => $item['skor_kecerdasan'],
                    'c3' => $item['skor_minat'],
                    'max_c1' => $maxC1,
                    'max_c2' => $maxC2,
                    'max_c3' => $maxC3,
                    'r1' => $item['r1'],
                    'r2' => $item['r2'],
                    'r3' => $item['r3'],
                    'w1' => $wRapor,
                    'w2' => $wKecerdasan,
                    'w3' => $wMinat,
                    'nilai_v' => $item['nilai_v'],
                ],
            ]);
        }

        return [
            'status' => 'success',
            'siswa' => $siswa,
            'hasil' => $hasilAkhir,
            'matrix_x' => $matrixX,
            'matrix_r' => $matrixR,
            'max_values' => ['c1' => $maxC1, 'c2' => $maxC2, 'c3' => $maxC3],
            'weights' => ['w1' => $wRapor, 'w2' => $wKecerdasan, 'w3' => $wMinat],
        ];
    }
}
