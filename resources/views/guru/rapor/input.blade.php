@extends('layouts.app')

@section('title', 'Input Nilai Rapor Siswa')
@section('page_heading', 'Input / Edit Nilai Rapor Semester 1–5')

@section('content')
<div class="space-y-6">
    <!-- Student Header Summary Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-1 rounded-full bg-sky-100 text-sky-800 text-xs font-bold">
                    NISN: {{ $siswa->nisn }}
                </span>
                <span class="px-2.5 py-1 rounded-full bg-slate-800 text-white text-xs font-bold">
                    Peminatan {{ $siswa->peminatan->kode ?? 'N/A' }} ({{ $siswa->kelas }})
                </span>
            </div>
            <h2 class="font-outfit text-2xl font-bold text-slate-800 mt-2">{{ $siswa->name }}</h2>
            <p class="text-xs text-slate-500">{{ $siswa->peminatan->nama ?? 'Peminatan Umum' }}</p>
        </div>

        <a href="{{ route('guru.siswa.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Siswa</span>
        </a>
    </div>

    <!-- Rapor Input Matrix Form -->
    <form action="{{ route('guru.rapor.store', $siswa->id) }}" method="POST" class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6">
        @csrf

        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-outfit text-lg font-bold text-slate-800">Matriks Nilai Rapor (Skala 0 - 100)</h3>
            <p class="text-xs text-slate-500">Mata Pelajaran Umum berlaku dari Sem 1-5, Mata Pelajaran Pilihan berlaku untuk Peminatan {{ $siswa->peminatan->kode ?? 'Siswa' }}.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100/80 text-slate-700 font-bold border-b border-slate-200">
                        <th class="p-3 w-12">No</th>
                        <th class="p-3">Mata Pelajaran</th>
                        <th class="p-3">Jenis</th>
                        <th class="p-3 text-center w-24">Sem 1</th>
                        <th class="p-3 text-center w-24">Sem 2</th>
                        <th class="p-3 text-center w-24">Sem 3</th>
                        <th class="p-3 text-center w-24">Sem 4</th>
                        <th class="p-3 text-center w-24">Sem 5</th>
                        <th class="p-3 text-center w-24">Rata-Rata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($mataPelajaran as $index => $mp)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3 text-slate-400 font-medium">{{ $index + 1 }}</td>
                            <td class="p-3">
                                <span class="font-bold text-slate-800 block text-xs">{{ $mp->nama }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $mp->kode }}</span>
                            </td>
                            <td class="p-3">
                                @if($mp->kategori === 'umum')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700">A. Umum</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">B. Pilihan {{ $siswa->peminatan->kode }}</span>
                                @endif
                            </td>
                            
                            @php
                                $valArr = [];
                            @endphp

                            @for($sem = 1; $sem <= 5; $sem++)
                                @php
                                    $val = isset($existingNilai[$mp->id][$sem]) ? $existingNilai[$mp->id][$sem]->nilai : '';
                                    if ($val !== '') { $valArr[] = (float)$val; }
                                @endphp
                                <td class="p-2 text-center">
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        min="0" 
                                        max="100" 
                                        name="nilai[{{ $mp->id }}][{{ $sem }}]" 
                                        value="{{ $val }}" 
                                        placeholder="0-100"
                                        class="w-20 px-2 py-1 text-center bg-slate-50 border border-slate-300 rounded-lg text-xs font-semibold focus:ring-sky-500 focus:border-sky-500">
                                </td>
                            @endfor

                            <td class="p-3 text-center font-bold text-sky-700">
                                {{ count($valArr) > 0 ? round(array_sum($valArr) / count($valArr), 1) : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('guru.siswa.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold shadow-lg transition flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Nilai Rapor & Hitung SAW</span>
            </button>
        </div>
    </form>
</div>
@endsection
