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

        // 2. Fetch all candidate majors (Opsi A: Membuka seluruh jurusan lintas peminatan)
        $candidateMajors = Jurusan::with(['rumpun.peminatan', 'kecerdasanUtama', 'mapelUtama'])->get();

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
                $target = trim($jm->pertanyaan->kategori_target);
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

        // Pemetaan nama rumpun jurusan ke kategori target kuesioner minat bakat
        $rumpunToTargetMap = [
            'Rumpun Ekonomi & Bisnis' => 'Ekonomi & Bisnis',
            'Rumpun Sosial & Humaniora' => 'Sosial & Humaniora',
            'Rumpun Terapan' => 'Ekonomi & Bisnis',
            'Kesehatan & Ilmu Hayati' => 'Kesehatan & Ilmu Hayati',
            'Sains, Teknik & Hitungan' => 'Sains & Teknik',
            'Kesehatan' => 'Kesehatan & Ilmu Hayati',
            'Bahasa' => 'Bahasa & Sastra',
            'Sains dan Teknologi' => 'Sains & Teknik',
            'Teknik & Rekayasa' => 'Sains & Teknik',
            'Komputasi & Sistem' => 'Komputasi & Sistem',
            'Geografi & Pemetaan' => 'Geografi & Pemetaan',
            'Sosial & Humaniora (F5)' => 'Sosial & Humaniora',
            'Ekonomi & Terapan (F5)' => 'Ekonomi & Bisnis',
        ];

        // 4. Build Decision Matrix (X)
        // Row: Major (Alternative), Col: Criteria C1, C2, C3
        $matrixX = [];
        foreach ($candidateMajors as $j) {
            // C1: Nilai Rapor
            // Jika jurusan memiliki mapel utama dan siswa mengambil mapel tersebut:
            // Bobot: 60% mapel utama + 40% rata-rata rapor keseluruhan.
            // Jika lintas jurusan (siswa tidak memiliki nilai mapel pilihan prodi tersebut),
            // gunakan rata-rata rapor keseluruhan (sesuai regulasi SNBP Kemendikbudristek).
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
                $c2 = !empty($skorKecerdasanPerKategori) ? (array_sum($skorKecerdasanPerKategori) / count($skorKecerdasanPerKategori)) : 60.0;
            }

            // C3: Minat Bakat
            $rumpunNama = $j->rumpun ? trim($j->rumpun->nama) : '';
            $matchedMinat = null;

            // 1. Cek dari mapping eksplisit rumpun ke target minat
            if (isset($rumpunToTargetMap[$rumpunNama]) && isset($avgMinatPerTarget[$rumpunToTargetMap[$rumpunNama]])) {
                $matchedMinat = $avgMinatPerTarget[$rumpunToTargetMap[$rumpunNama]];
            }

            // 2. Jika belum cocok, cari berdasarkan kesamaan kata/frasa
            if ($matchedMinat === null) {
                foreach ($avgMinatPerTarget as $targetName => $val) {
                    $cleanTarget = strtolower(preg_replace('/[^a-zA-Z]/', '', $targetName));
                    $cleanRumpun = strtolower(preg_replace('/[^a-zA-Z]/', '', $rumpunNama));
                    $cleanJurusan = strtolower(preg_replace('/[^a-zA-Z]/', '', $j->nama));

                    if (
                        str_contains($cleanRumpun, $cleanTarget) ||
                        str_contains($cleanTarget, $cleanRumpun) ||
                        str_contains($cleanTarget, $cleanJurusan) ||
                        str_contains($cleanJurusan, $cleanTarget)
                    ) {
                        $matchedMinat = max($matchedMinat ?? 0, $val);
                    }
                }
            }

            // 3. Fallback jika tidak ada kesesuaian: rata-rata minat atau baseline 60.0
            if ($matchedMinat === null) {
                $matchedMinat = !empty($avgMinatPerTarget) ? (array_sum($avgMinatPerTarget) / count($avgMinatPerTarget)) : 60.0;
            }

            $c3 = $matchedMinat;

            // Tentukan status linieritas peminatan
            $isLinier = $siswa->peminatan_id && $j->rumpun && ($j->rumpun->peminatan_id == $siswa->peminatan_id);

            $matrixX[$j->id] = [
                'jurusan' => $j,
                'c1' => round($c1, 4),
                'c2' => round($c2, 4),
                'c3' => round($c3, 4),
                'is_linier' => $isLinier,
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
                'is_linier' => $data['is_linier'],
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
                    'is_linier' => $item['is_linier'],
                    'peminatan_asal' => $siswa->peminatan->kode ?? null,
                    'peminatan_jurusan' => $item['jurusan']->rumpun->peminatan->kode ?? null,
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
