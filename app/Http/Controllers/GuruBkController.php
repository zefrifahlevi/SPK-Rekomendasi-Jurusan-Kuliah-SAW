<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peminatan;
use App\Models\MataPelajaran;
use App\Models\NilaiRapor;
use App\Models\KategoriKecerdasan;
use App\Models\PertanyaanKecerdasan;
use App\Models\PertanyaanMinat;
use App\Models\RumpunJurusan;
use App\Models\Jurusan;
use App\Models\KriteriaSaw;
use App\Models\HasilSaw;
use App\Services\SawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruBkController extends Controller
{
    protected $sawService;

    public function __construct(SawService $sawService)
    {
        $this->sawService = $sawService;
    }

    public function dashboard()
    {
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalJurusan = Jurusan::count();
        $totalPertanyaanKecerdasan = PertanyaanKecerdasan::count();
        $totalPertanyaanMinat = PertanyaanMinat::count();
        $peminatanList = Peminatan::withCount('siswa')->get();

        $siswaCalculated = HasilSaw::select('siswa_id')->distinct()->count();

        // Top recommended major distribution
        $topMajors = HasilSaw::with('jurusan')
            ->where('ranking', 1)
            ->get()
            ->groupBy('jurusan_id')
            ->map(function ($items) {
                return [
                    'nama' => $items->first()->jurusan->nama ?? 'N/A',
                    'count' => $items->count(),
                ];
            });

        return view('guru.dashboard', compact(
            'totalSiswa',
            'totalJurusan',
            'totalPertanyaanKecerdasan',
            'totalPertanyaanMinat',
            'peminatanList',
            'siswaCalculated',
            'topMajors'
        ));
    }

    // --- SISWA MANAGEMENT ---
    public function indexSiswa(Request $request)
    {
        $query = User::where('role', 'siswa')->with(['peminatan', 'hasilSaw']);

        if ($request->filled('peminatan_id')) {
            $query->where('peminatan_id', $request->peminatan_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%')
                  ->orWhere('kelas', 'like', '%' . $request->search . '%');
            });
        }

        $siswaList = $query->paginate(10);
        $peminatanList = Peminatan::all();

        return view('guru.siswa.index', compact('siswaList', 'peminatanList'));
    }

    public function storeSiswa(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nisn' => 'required|numeric|unique:users,nisn',
            'kelas' => 'required|string',
            'peminatan_id' => 'required|exists:peminatan,id',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nisn' => $request->nisn,
            'kelas' => $request->kelas,
            'peminatan_id' => $request->peminatan_id,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        return redirect()->route('guru.siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function updateSiswa(Request $request, $id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'nisn' => 'required|numeric|unique:users,nisn,' . $id,
            'kelas' => 'required|string',
            'peminatan_id' => 'required|exists:peminatan,id',
        ]);

        $siswa->update([
            'name' => $request->name,
            'email' => $request->email,
            'nisn' => $request->nisn,
            'kelas' => $request->kelas,
            'peminatan_id' => $request->peminatan_id,
        ]);

        if ($request->filled('password')) {
            $siswa->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('guru.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroySiswa($id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);
        $siswa->delete();

        return redirect()->route('guru.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    // --- NILAI RAPOR MANAGEMENT ---
    public function inputNilaiRapor($siswaId)
    {
        $siswa = User::where('role', 'siswa')->with('peminatan')->findOrFail($siswaId);

        // Fetch subjects: General subjects + specific choice subjects for student's peminatan
        $mataPelajaran = MataPelajaran::whereNull('peminatan_id')
            ->orWhere('peminatan_id', $siswa->peminatan_id)
            ->get();

        $existingNilai = NilaiRapor::where('siswa_id', $siswa->id)
            ->get()
            ->groupBy('mata_pelajaran_id')
            ->map(function ($items) {
                return $items->keyBy('semester');
            });

        return view('guru.rapor.input', compact('siswa', 'mataPelajaran', 'existingNilai'));
    }

    public function storeNilaiRapor(Request $request, $siswaId)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($siswaId);
        $nilaiData = $request->input('nilai', []); // Array [mapel_id][semester] = nilai

        foreach ($nilaiData as $mapelId => $semesters) {
            foreach ($semesters as $sem => $val) {
                if ($val !== null && $val !== '') {
                    NilaiRapor::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'mata_pelajaran_id' => $mapelId,
                            'semester' => $sem,
                        ],
                        [
                            'nilai' => (float) $val,
                        ]
                    );
                }
            }
        }

        // Recalculate SAW for this student
        $this->sawService->hitungRekomendasi($siswa);

        return redirect()->route('guru.siswa.index')->with('success', "Data Rapor Semester 1-5 untuk {$siswa->name} berhasil disimpan & hasil SAW telah diperbarui.");
    }

    // --- PERTANYAAN KECERDASAN & MINAT MANAGEMENT ---
    public function indexPertanyaan()
    {
        $kategoriKecerdasan = KategoriKecerdasan::all();
        $pertanyaanKecerdasan = PertanyaanKecerdasan::with('kategori')->paginate(10, ['*'], 'page_k');
        $pertanyaanMinat = PertanyaanMinat::paginate(10, ['*'], 'page_m');

        return view('guru.pertanyaan.index', compact('kategoriKecerdasan', 'pertanyaanKecerdasan', 'pertanyaanMinat'));
    }

    public function storePertanyaanKecerdasan(Request $request)
    {
        $request->validate([
            'kategori_kecerdasan_id' => 'required|exists:kategori_kecerdasan,id',
            'pertanyaan' => 'required|string',
        ]);

        PertanyaanKecerdasan::create([
            'kategori_kecerdasan_id' => $request->kategori_kecerdasan_id,
            'pertanyaan' => $request->pertanyaan,
        ]);

        return redirect()->route('guru.pertanyaan.index')->with('success', 'Pertanyaan kecerdasan majemuk berhasil ditambahkan.');
    }

    public function destroyPertanyaanKecerdasan($id)
    {
        PertanyaanKecerdasan::findOrFail($id)->delete();
        return redirect()->route('guru.pertanyaan.index')->with('success', 'Pertanyaan kecerdasan majemuk berhasil dihapus.');
    }

    public function storePertanyaanMinat(Request $request)
    {
        $request->validate([
            'kategori_target' => 'required|string',
            'pertanyaan' => 'required|string',
        ]);

        PertanyaanMinat::create([
            'kategori_target' => $request->kategori_target,
            'pertanyaan' => $request->pertanyaan,
        ]);

        return redirect()->route('guru.pertanyaan.index')->with('success', 'Pertanyaan minat bakat berhasil ditambahkan.');
    }

    public function destroyPertanyaanMinat($id)
    {
        PertanyaanMinat::findOrFail($id)->delete();
        return redirect()->route('guru.pertanyaan.index')->with('success', 'Pertanyaan minat bakat berhasil dihapus.');
    }

    // --- KRITERIA & BOBOT SAW MANAGEMENT ---
    public function indexKriteria()
    {
        $kriteriaList = KriteriaSaw::all();
        $totalBobot = $kriteriaList->sum('bobot');

        return view('guru.kriteria.index', compact('kriteriaList', 'totalBobot'));
    }

    public function updateKriteria(Request $request)
    {
        $bobotInput = $request->input('bobot', []);

        $sum = array_sum($bobotInput);
        // Allow sum of 1.0 or 100
        if (abs($sum - 1.0) > 0.01 && abs($sum - 100) > 0.1) {
            return back()->withErrors(['bobot' => 'Total bobot kriteria harus bernilai 1.0 (atau 100%). Total saat ini: ' . $sum]);
        }

        foreach ($bobotInput as $id => $b) {
            $bobotVal = $sum > 5 ? ($b / 100.0) : $b;
            KriteriaSaw::where('id', $id)->update(['bobot' => $bobotVal]);
        }

        // Recalculate SAW for all students with responses
        $siswaList = User::where('role', 'siswa')->get();
        foreach ($siswaList as $siswa) {
            $this->sawService->hitungRekomendasi($siswa);
        }

        return redirect()->route('guru.kriteria.index')->with('success', 'Bobot kriteria SAW berhasil diperbarui & kalkulasi semua siswa telah disesuaikan.');
    }

    // --- JURUSAN MANAGEMENT ---
    public function indexJurusan(Request $request)
    {
        $query = Jurusan::with(['rumpun.peminatan', 'kecerdasanUtama', 'mapelUtama']);

        if ($request->filled('peminatan_id')) {
            $query->whereHas('rumpun', function ($q) use ($request) {
                $q->where('peminatan_id', $request->peminatan_id);
            });
        }

        $jurusanList = $query->paginate(12);
        $peminatanList = Peminatan::all();
        $rumpunList = RumpunJurusan::with('peminatan')->get();
        $kategoriKecerdasan = KategoriKecerdasan::all();
        $mataPelajaran = MataPelajaran::all();

        return view('guru.jurusan.index', compact('jurusanList', 'peminatanList', 'rumpunList', 'kategoriKecerdasan', 'mataPelajaran'));
    }

    public function storeJurusan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'rumpun_id' => 'required|exists:rumpun_jurusan,id',
            'deskripsi' => 'nullable|string',
            'prospek_kerja' => 'nullable|string',
            'kategori_kecerdasan_utama_id' => 'nullable|exists:kategori_kecerdasan,id',
            'mapel_utama_id' => 'nullable|exists:mata_pelajaran,id',
        ]);

        Jurusan::create($request->all());

        return redirect()->route('guru.jurusan.index')->with('success', 'Data jurusan berhasil ditambahkan.');
    }

    public function updateJurusan(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'rumpun_id' => 'required|exists:rumpun_jurusan,id',
            'deskripsi' => 'nullable|string',
            'prospek_kerja' => 'nullable|string',
            'kategori_kecerdasan_utama_id' => 'nullable|exists:kategori_kecerdasan,id',
            'mapel_utama_id' => 'nullable|exists:mata_pelajaran,id',
        ]);

        $jurusan->update($request->all());

        return redirect()->route('guru.jurusan.index')->with('success', 'Data jurusan berhasil diperbarui.');
    }

    public function destroyJurusan($id)
    {
        Jurusan::findOrFail($id)->delete();
        return redirect()->route('guru.jurusan.index')->with('success', 'Data jurusan berhasil dihapus.');
    }

    // --- AUDIT & PROSES PERHITUNGAN SAW ---
    public function indexProsesSaw()
    {
        $siswaList = User::where('role', 'siswa')
            ->with(['peminatan', 'hasilSaw' => function ($q) {
                $q->orderBy('ranking', 'asc');
            }])
            ->get();

        $kriteriaList = KriteriaSaw::all();

        return view('guru.saw.index', compact('siswaList', 'kriteriaList'));
    }

    public function hitungUlangSawAll()
    {
        $siswaList = User::where('role', 'siswa')->get();
        $count = 0;
        foreach ($siswaList as $siswa) {
            $this->sawService->hitungRekomendasi($siswa);
            $count++;
        }

        return redirect()->route('guru.saw.index')->with('success', "Kalkulasi SAW berhasil diperbarui untuk {$count} siswa.");
    }

    public function detailSawSiswa($siswaId)
    {
        $siswa = User::where('role', 'siswa')->with('peminatan')->findOrFail($siswaId);
        $kalkulasi = $this->sawService->hitungRekomendasi($siswa);

        return view('guru.saw.detail', compact('siswa', 'kalkulasi'));
    }
}
