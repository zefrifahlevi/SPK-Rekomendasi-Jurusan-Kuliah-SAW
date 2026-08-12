@extends('layouts.app')

@section('title', 'Hasil Rekomendasi Jurusan Kuliah SAW')
@section('page_heading', 'Hasil Akhir Perhitungan Rekomendasi Jurusan SAW')

@section('content')
<div class="space-y-6">
    @if(isset($kalkulasi['status']) && $kalkulasi['status'] === 'success' && count($kalkulasi['hasil']) > 0)
        @php
            $top1 = $kalkulasi['hasil'][0];
        @endphp

        <!-- Top 1 Recommendation Hero Card -->
        <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-sky-950 p-8 rounded-3xl text-white shadow-2xl relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="space-y-3">
                    <span class="inline-flex items-center space-x-2 px-3 py-1 bg-amber-500/20 border border-amber-400/40 rounded-full text-xs font-bold text-amber-300">
                        <i class="fa-solid fa-crown text-amber-400"></i>
                        <span>REKOMENDASI UTAMA TERATAS (RANK #1)</span>
                    </span>
                    <h2 class="font-outfit text-3xl sm:text-4xl font-extrabold tracking-tight text-white">{{ $top1['jurusan']['nama'] }}</h2>
                    <p class="text-sky-300 font-semibold text-sm">{{ $top1['jurusan']['rumpun']['nama'] ?? 'Rumpun Keahlian' }} • Peminatan {{ $siswa->peminatan->kode ?? 'SMAN 11' }}</p>
                    <p class="text-slate-300 text-xs max-w-2xl leading-relaxed">{{ $top1['jurusan']['deskripsi'] }}</p>

                    <div class="pt-2 flex flex-wrap gap-2 text-xs">
                        <span class="px-3 py-1 bg-slate-800/80 border border-slate-700 rounded-xl text-slate-300 font-medium">
                            <i class="fa-solid fa-briefcase text-sky-400 mr-1.5"></i> Prospek: {{ $top1['jurusan']['prospek_kerja'] ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/20 text-center flex-shrink-0 w-full md:w-56 space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-300">Skor Preferensi V</p>
                    <div class="font-outfit font-extrabold text-4xl text-amber-400 tracking-tight">
                        {{ round($top1['nilai_v'] * 100, 1) }}%
                    </div>
                    <span class="inline-block text-[11px] font-mono text-sky-300 bg-sky-950/60 px-2.5 py-0.5 rounded-full border border-sky-400/30">
                        V = {{ $top1['nilai_v'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Two Column Grid: Profile Radar Chart & Ranking List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Radar Chart Kecerdasan Majemuk -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-outfit text-lg font-bold text-slate-800 flex items-center space-x-2">
                    <i class="fa-solid fa-brain text-purple-600"></i>
                    <span>Profil Kecerdasan Majemuk Anda</span>
                </h3>
                <p class="text-xs text-slate-500">Visualisasi 8 domain kecerdasan (Multiple Intelligences) berdasarkan tes Anda.</p>

                <div class="relative h-72 flex items-center justify-center pt-2">
                    <canvas id="radarChart"></canvas>
                </div>
            </div>

            <!-- Full Ranking List -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-outfit text-lg font-bold text-slate-800 flex items-center space-x-2">
                    <i class="fa-solid fa-list-ol text-sky-600"></i>
                    <span>Daftar Perangkingan Jurusan SAW</span>
                </h3>
                <p class="text-xs text-slate-500">Perhitungan urutan jurusan dalam Peminatan {{ $siswa->peminatan->kode ?? 'F' }}.</p>

                <div class="space-y-3 max-h-[320px] overflow-y-auto pr-1">
                    @foreach($kalkulasi['hasil'] as $h)
                        <div class="p-3.5 rounded-2xl border border-slate-100 bg-slate-50/80 flex items-center justify-between hover:bg-slate-100/80 transition">
                            <div class="flex items-center space-x-3.5">
                                <span class="w-8 h-8 rounded-xl font-outfit font-extrabold text-xs text-white flex items-center justify-center flex-shrink-0 {{ $h['ranking'] == 1 ? 'bg-amber-500 shadow' : ($h['ranking'] == 2 ? 'bg-slate-400' : ($h['ranking'] == 3 ? 'bg-amber-700' : 'bg-slate-700')) }}">
                                    #{{ $h['ranking'] }}
                                </span>
                                <div>
                                    <h4 class="font-bold text-xs text-slate-800 leading-tight">{{ $h['jurusan']['nama'] }}</h4>
                                    <p class="text-[10px] text-slate-500">{{ $h['jurusan']['rumpun']['nama'] ?? '' }}</p>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="font-mono font-extrabold text-xs text-sky-700 bg-sky-100 px-2.5 py-1 rounded-lg">
                                    V: {{ $h['nilai_v'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SAW Step-by-Step Transparency Table -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4" x-data="{ openTransparansi: false }">
            <div class="flex items-center justify-between cursor-pointer" @click="openTransparansi = !openTransparansi">
                <h3 class="font-outfit text-base font-bold text-slate-800 flex items-center space-x-2">
                    <i class="fa-solid fa-calculator text-amber-500"></i>
                    <span>Transparansi Perhitungan SAW (Matriks X, R, dan Bobot W)</span>
                </h3>
                <button class="text-xs text-sky-600 font-semibold flex items-center space-x-1">
                    <span x-text="openTransparansi ? 'Sembunyikan' : 'Tampilkan Matriks SAW'"></span>
                    <i class="fa-solid" :class="openTransparansi ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
            </div>

            <div x-show="openTransparansi" x-cloak class="space-y-4 pt-3 border-t border-slate-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <strong class="text-slate-700 block">Bobot C1 (Rapor):</strong> {{ $kalkulasi['weights']['w1'] * 100 }}% (W1 = {{ $kalkulasi['weights']['w1'] }})
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <strong class="text-slate-700 block">Bobot C2 (Kecerdasan):</strong> {{ $kalkulasi['weights']['w2'] * 100 }}% (W2 = {{ $kalkulasi['weights']['w2'] }})
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <strong class="text-slate-700 block">Bobot C3 (Minat):</strong> {{ $kalkulasi['weights']['w3'] * 100 }}% (W3 = {{ $kalkulasi['weights']['w3'] }})
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-800 text-white font-bold">
                                <th class="p-3">Jurusan</th>
                                <th class="p-3 text-center">X1 (Rapor)</th>
                                <th class="p-3 text-center">X2 (Kecerdasan)</th>
                                <th class="p-3 text-center">X3 (Minat)</th>
                                <th class="p-3 text-center">R1 (Norm)</th>
                                <th class="p-3 text-center">R2 (Norm)</th>
                                <th class="p-3 text-center">R3 (Norm)</th>
                                <th class="p-3 text-center">Skor V</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($kalkulasi['hasil'] as $h)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-3 font-semibold text-slate-800">{{ $h['jurusan']['nama'] }}</td>
                                    <td class="p-3 text-center font-mono">{{ $h['skor_rapor'] }}</td>
                                    <td class="p-3 text-center font-mono">{{ $h['skor_kecerdasan'] }}</td>
                                    <td class="p-3 text-center font-mono">{{ $h['skor_minat'] }}</td>
                                    <td class="p-3 text-center font-mono text-slate-600">{{ $h['r1'] }}</td>
                                    <td class="p-3 text-center font-mono text-slate-600">{{ $h['r2'] }}</td>
                                    <td class="p-3 text-center font-mono text-slate-600">{{ $h['r3'] }}</td>
                                    <td class="p-3 text-center font-mono font-bold text-sky-700">{{ $h['nilai_v'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Radar Chart Script Initialization -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('radarChart').getContext('2d');
                const profileData = @json($profileKecerdasan);
                
                const labels = Object.keys(profileData);
                const values = Object.values(profileData);

                new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Skor Kecerdasan (%)',
                            data: values,
                            backgroundColor: 'rgba(147, 51, 234, 0.2)',
                            borderColor: 'rgba(147, 51, 234, 0.8)',
                            pointBackgroundColor: 'rgba(147, 51, 234, 1)',
                            pointBorderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                angleLines: { color: 'rgba(226, 232, 240, 0.8)' },
                                grid: { color: 'rgba(226, 232, 240, 0.8)' },
                                suggestMin: 0,
                                suggestMax: 100,
                                ticks: { display: false }
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            });
        </script>
    @else
        <div class="bg-white p-8 rounded-3xl border border-slate-200 text-center space-y-3">
            <i class="fa-solid fa-circle-info text-amber-500 text-3xl"></i>
            <h3 class="font-outfit text-lg font-bold text-slate-800">Belum Ada Hasil Perhitungan</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">
                Silakan isi terlebih dahulu Tes Kecerdasan Majemuk dan Tes Minat Bakat agar sistem SAW dapat memproses rekomendasi jurusan untuk Anda.
            </p>
            <div class="pt-2">
                <a href="{{ route('siswa.kecerdasan.form') }}" class="px-5 py-2.5 bg-sky-600 text-white font-bold text-xs rounded-xl hover:bg-sky-500 inline-block">
                    Mulai Isi Tes Sekarang
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
