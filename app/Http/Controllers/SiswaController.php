<?php

namespace App\Http\Controllers;

use App\Models\PertanyaanKecerdasan;
use App\Models\JawabanKecerdasan;
use App\Models\PertanyaanMinat;
use App\Models\JawabanMinat;
use App\Models\KategoriKecerdasan;
use App\Models\HasilSaw;
use App\Services\SawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    protected $sawService;

    public function __construct(SawService $sawService)
    {
        $this->sawService = $sawService;
    }

    public function dashboard()
    {
        $siswa = Auth::user();
        $siswa->load(['peminatan', 'nilaiRapor', 'jawabanKecerdasan', 'jawabanMinat']);

        $sudahIsiKecerdasan = $siswa->jawabanKecerdasan()->exists();
        $sudahIsiMinat = $siswa->jawabanMinat()->exists();
        $sudahAdaNilaiRapor = $siswa->nilaiRapor()->exists();

        // Top 3 Recommended Majors if SAW calculated
        $topHasil = HasilSaw::with(['jurusan.rumpun', 'jurusan.kecerdasanUtama'])
            ->where('siswa_id', $siswa->id)
            ->orderBy('ranking', 'asc')
            ->take(3)
            ->get();

        return view('siswa.dashboard', compact(
            'siswa',
            'sudahIsiKecerdasan',
            'sudahIsiMinat',
            'sudahAdaNilaiRapor',
            'topHasil'
        ));
    }

    public function formKecerdasan()
    {
        $siswa = Auth::user();
        $kategoriList = KategoriKecerdasan::with('pertanyaan')->get();
        $jawabanExisting = JawabanKecerdasan::where('siswa_id', $siswa->id)
            ->pluck('skor', 'pertanyaan_kecerdasan_id')
            ->toArray();

        return view('siswa.kecerdasan', compact('kategoriList', 'jawabanExisting'));
    }

    public function storeKecerdasan(Request $request)
    {
        $siswa = Auth::user();
        $jawaban = $request->input('jawaban', []);

        if (empty($jawaban)) {
            return back()->withErrors(['jawaban' => 'Harap isi semua pertanyaan kecerdasan majemuk.']);
        }

        foreach ($jawaban as $qId => $skor) {
            JawabanKecerdasan::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'pertanyaan_kecerdasan_id' => $qId,
                ],
                [
                    'skor' => (int) $skor,
                ]
            );
        }

        // Recalculate SAW
        $this->sawService->hitungRekomendasi($siswa);

        return redirect()->route('siswa.dashboard')->with('success', 'Jawaban kuesioner Kecerdasan Majemuk berhasil disimpan! Perhitungan rekomendasi jurusan telah diperbarui.');
    }

    public function formMinat()
    {
        $siswa = Auth::user();
        $pertanyaanList = PertanyaanMinat::all();
        $jawabanExisting = JawabanMinat::where('siswa_id', $siswa->id)
            ->pluck('skor', 'pertanyaan_minat_id')
            ->toArray();

        return view('siswa.minat', compact('pertanyaanList', 'jawabanExisting'));
    }

    public function storeMinat(Request $request)
    {
        $siswa = Auth::user();
        $jawaban = $request->input('jawaban', []);

        if (empty($jawaban)) {
            return back()->withErrors(['jawaban' => 'Harap isi semua pertanyaan minat bakat.']);
        }

        foreach ($jawaban as $qId => $skor) {
            JawabanMinat::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'pertanyaan_minat_id' => $qId,
                ],
                [
                    'skor' => (int) $skor,
                ]
            );
        }

        // Recalculate SAW
        $this->sawService->hitungRekomendasi($siswa);

        return redirect()->route('siswa.dashboard')->with('success', 'Jawaban kuesioner Minat Bakat berhasil disimpan! Perhitungan rekomendasi jurusan telah diperbarui.');
    }

    public function hasilRekomendasi()
    {
        $siswa = Auth::user();
        $kalkulasi = $this->sawService->hitungRekomendasi($siswa);

        // Fetch user intelligence profile breakdown
        $jawabanKecerdasan = JawabanKecerdasan::with('pertanyaan.kategori')
            ->where('siswa_id', $siswa->id)
            ->get();

        $kategoriList = KategoriKecerdasan::all();
        $profileKecerdasan = [];
        foreach ($kategoriList as $kat) {
            $katJawab = $jawabanKecerdasan->filter(function ($j) use ($kat) {
                return $j->pertanyaan && $j->pertanyaan->kategori_kecerdasan_id == $kat->id;
            });
            $avgSkor = $katJawab->avg('skor') ?? 0;
            $profileKecerdasan[$kat->nama] = round(($avgSkor / 5.0) * 100, 1);
        }

        return view('siswa.hasil', compact('siswa', 'kalkulasi', 'profileKecerdasan'));
    }
}
