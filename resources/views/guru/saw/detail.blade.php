@extends('layouts.app')

@section('title', 'Detail Transparansi SAW - ' . $siswa->name)
@section('page_heading', 'Detail Transparansi & Langkah Matriks SAW')

@section('content')
<div class="space-y-6">
    <!-- Student Information Banner -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-1 rounded-full bg-slate-900 text-white font-bold text-xs">
                    Peminatan {{ $siswa->peminatan->kode ?? 'N/A' }} ({{ $siswa->kelas }})
                </span>
                <span class="px-2.5 py-1 rounded-full bg-sky-100 text-sky-800 font-bold text-xs">
                    NISN: {{ $siswa->nisn }}
                </span>
            </div>
            <h2 class="font-outfit text-2xl font-bold text-slate-800 mt-2">{{ $siswa->name }}</h2>
            <p class="text-xs text-slate-500">{{ $siswa->peminatan->nama ?? '' }}</p>
        </div>

        <a href="{{ route('guru.saw.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Ringkasan SAW</span>
        </a>
    </div>

    @if(isset($kalkulasi['status']) && $kalkulasi['status'] === 'success')
        <!-- Formula Explanation Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
                <h4 class="font-outfit font-bold text-xs text-slate-400 uppercase tracking-wider">C1: Nilai Rapor (Bobot W1 = {{ $kalkulasi['weights']['w1'] }})</h4>
                <p class="text-xl font-extrabold text-slate-800 mt-2">Max X1 = {{ $kalkulasi['max_values']['c1'] }}</p>
                <p class="text-[11px] text-slate-500 mt-1">Formula Normalisasi: $R_{i1} = \frac{X_{i1}}{\max X_1}$</p>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
                <h4 class="font-outfit font-bold text-xs text-slate-400 uppercase tracking-wider">C2: Kecerdasan Majemuk (Bobot W2 = {{ $kalkulasi['weights']['w2'] }})</h4>
                <p class="text-xl font-extrabold text-slate-800 mt-2">Max X2 = {{ $kalkulasi['max_values']['c2'] }}</p>
                <p class="text-[11px] text-slate-500 mt-1">Formula Normalisasi: $R_{i2} = \frac{X_{i2}}{\max X_2}$</p>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
                <h4 class="font-outfit font-bold text-xs text-slate-400 uppercase tracking-wider">C3: Minat Bakat (Bobot W3 = {{ $kalkulasi['weights']['w3'] }})</h4>
                <p class="text-xl font-extrabold text-slate-800 mt-2">Max X3 = {{ $kalkulasi['max_values']['c3'] }}</p>
                <p class="text-[11px] text-slate-500 mt-1">Formula Normalisasi: $R_{i3} = \frac{X_{i3}}{\max X_3}$</p>
            </div>
        </div>

        <!-- Matrix X, Matrix R, and Preference V Table -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-6 space-y-4">
            <h3 class="font-outfit text-lg font-bold text-slate-800">Tabel Lengkap Perhitungan SAW (Alternatif Jurusan Peminatan {{ $siswa->peminatan->kode ?? 'Siswa' }})</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white font-bold">
                            <th class="p-3" rowspan="2">Rank</th>
                            <th class="p-3" rowspan="2">Jurusan (Alternatif)</th>
                            <th class="p-3 text-center" colspan="3">Matriks Keputusan (X)</th>
                            <th class="p-3 text-center" colspan="3">Matriks Normalisasi (R)</th>
                            <th class="p-3 text-center" rowspan="2">Nilai Preferensi (V)</th>
                        </tr>
                        <tr class="bg-slate-800 text-slate-200 text-[11px]">
                            <th class="p-2 text-center">C1 (Rapor)</th>
                            <th class="p-2 text-center">C2 (Kecerdasan)</th>
                            <th class="p-2 text-center">C3 (Minat)</th>
                            <th class="p-2 text-center">R1</th>
                            <th class="p-2 text-center">R2</th>
                            <th class="p-2 text-center">R3</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($kalkulasi['hasil'] as $h)
                            <tr class="hover:bg-slate-50 transition {{ $h['ranking'] == 1 ? 'bg-amber-50/70 font-bold' : '' }}">
                                <td class="p-3 text-center">
                                    @if($h['ranking'] == 1)
                                        <span class="w-6 h-6 rounded-full bg-amber-500 text-white inline-flex items-center justify-center font-bold text-xs">1</span>
                                    @elseif($h['ranking'] == 2)
                                        <span class="w-6 h-6 rounded-full bg-slate-400 text-white inline-flex items-center justify-center font-bold text-xs">2</span>
                                    @elseif($h['ranking'] == 3)
                                        <span class="w-6 h-6 rounded-full bg-amber-700 text-white inline-flex items-center justify-center font-bold text-xs">3</span>
                                    @else
                                        <span class="text-slate-500 font-semibold">{{ $h['ranking'] }}</span>
                                    @endif
                                </td>

                                <td class="p-3">
                                    <span class="font-bold text-slate-800 block text-xs">{{ $h['jurusan']['nama'] }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $h['jurusan']['rumpun']['nama'] ?? '' }}</span>
                                </td>

                                <td class="p-3 text-center font-mono text-slate-700">{{ $h['skor_rapor'] }}</td>
                                <td class="p-3 text-center font-mono text-slate-700">{{ $h['skor_kecerdasan'] }}</td>
                                <td class="p-3 text-center font-mono text-slate-700">{{ $h['skor_minat'] }}</td>

                                <td class="p-3 text-center font-mono text-slate-600 bg-slate-50/80">{{ $h['r1'] }}</td>
                                <td class="p-3 text-center font-mono text-slate-600 bg-slate-50/80">{{ $h['r2'] }}</td>
                                <td class="p-3 text-center font-mono text-slate-600 bg-slate-50/80">{{ $h['r3'] }}</td>

                                <td class="p-3 text-center">
                                    <span class="font-mono font-extrabold text-sm text-sky-700 bg-sky-100/80 px-2.5 py-1 rounded-lg border border-sky-200">
                                        {{ $h['nilai_v'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-6 rounded-3xl text-center font-medium text-xs">
            Belum dapat melakukan kalkulasi SAW. Harap pastikan data kriteria dan jurusan telah tersedia.
        </div>
    @endif
</div>
@endsection
