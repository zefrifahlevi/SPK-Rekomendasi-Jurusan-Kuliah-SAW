@extends('layouts.app')

@section('title', 'Proses Perhitungan SAW')
@section('page_heading', 'Audit & Monitoring Perhitungan SAW Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header Banner & Batch Trigger Button -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h3 class="font-outfit text-xl font-bold text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-calculator text-amber-500"></i>
                <span>Hasil Perhitungan Simple Additive Weighting (SAW)</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">Audit matriks keputusan $X$, matriks normalisasi $R$, dan preferensi akhir $V$ untuk seluruh siswa.</p>
        </div>

        <form action="{{ route('guru.saw.recalculate') }}" method="POST">
            @csrf
            <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center space-x-2">
                <i class="fa-solid fa-rotate text-sky-400"></i>
                <span>Hitung Ulang SAW Semua Siswa</span>
            </button>
        </form>
    </div>

    <!-- Student SAW Ranking Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100/80 text-slate-700 font-bold border-b border-slate-200">
                        <th class="p-4">No</th>
                        <th class="p-4">Nama Siswa</th>
                        <th class="p-4">Peminatan / Kelas</th>
                        <th class="p-4 text-center">Rek. Jurusan Top #1</th>
                        <th class="p-4 text-center">Skor V #1</th>
                        <th class="p-4 text-center">Rek. Jurusan Top #2</th>
                        <th class="p-4 text-center">Rek. Jurusan Top #3</th>
                        <th class="p-4 text-center">Detail Audit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswaList as $index => $s)
                        @php
                            $r1 = $s->hasilSaw->where('ranking', 1)->first();
                            $r2 = $s->hasilSaw->where('ranking', 2)->first();
                            $r3 = $s->hasilSaw->where('ranking', 3)->first();
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 text-slate-400 font-medium">{{ $index + 1 }}</td>
                            <td class="p-4">
                                <span class="font-bold text-slate-800 block text-sm">{{ $s->name }}</span>
                                <span class="text-[11px] font-mono text-slate-400">NISN: {{ $s->nisn }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-white">
                                    {{ $s->peminatan->kode ?? 'F' }}
                                </span>
                                <span class="text-xs font-semibold text-slate-700 ml-1">{{ $s->kelas }}</span>
                            </td>

                            <td class="p-4 text-center">
                                @if($r1)
                                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-900 font-bold text-xs border border-emerald-300 shadow-sm">
                                        🥇 {{ $r1->jurusan->nama ?? 'N/A' }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-xs">-</span>
                                @endif
                            </td>

                            <td class="p-4 text-center">
                                @if($r1)
                                    <span class="font-mono font-extrabold text-sm text-sky-700 bg-sky-50 px-2 py-1 rounded-lg">
                                        {{ $r1->nilai_v }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <td class="p-4 text-center">
                                @if($r2)
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-semibold text-[11px]">
                                        🥈 {{ $r2->jurusan->nama ?? 'N/A' }} ({{ $r2->nilai_v }})
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <td class="p-4 text-center">
                                @if($r3)
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-semibold text-[11px]">
                                        🥉 {{ $r3->jurusan->nama ?? 'N/A' }} ({{ $r3->nilai_v }})
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <td class="p-4 text-center">
                                <a href="{{ route('guru.saw.detail', $s->id) }}" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-500 text-white rounded-lg font-bold text-[11px] transition shadow flex items-center justify-center space-x-1">
                                    <i class="fa-solid fa-table"></i>
                                    <span>Matriks SAW</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400 italic">Belum ada siswa yang dihitung.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
