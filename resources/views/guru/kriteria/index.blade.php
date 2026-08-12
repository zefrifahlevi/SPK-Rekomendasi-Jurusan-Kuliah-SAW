@extends('layouts.app')

@section('title', 'Kriteria & Bobot SAW')
@section('page_heading', 'Konfigurasi Kriteria & Bobot Penilaian SAW')

@section('content')
<div class="space-y-6 max-w-4xl">
    <!-- Explanatory Header Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2">
        <h3 class="font-outfit text-lg font-bold text-slate-800 flex items-center space-x-2">
            <i class="fa-solid fa-sliders text-sky-600"></i>
            <span>Penetapan Bobot SAW (Simple Additive Weighting)</span>
        </h3>
        <p class="text-xs text-slate-600 leading-relaxed">
            Metode SAW membutuhkan total bobot kriteria $\sum W = 1.0$ (atau 100%). Kriteria pembobotan penilaian rekomendasi jurusan kuliah meliputi 3 komponen utama:
        </p>
    </div>

    <!-- Form Setting Bobot SAW -->
    <form action="{{ route('guru.kriteria.update') }}" method="POST" class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            @foreach($kriteriaList as $k)
                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/80 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 rounded-md font-outfit font-extrabold text-xs text-white bg-slate-800">
                                {{ $k->kode }}
                            </span>
                            <h4 class="text-sm font-bold text-slate-800">{{ $k->nama }}</h4>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                {{ $k->jenis }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 max-w-xl leading-relaxed">{{ $k->deskripsi }}</p>
                    </div>

                    <div class="flex items-center space-x-3 self-end md:self-auto">
                        <label class="text-xs font-semibold text-slate-600">Bobot W:</label>
                        <input 
                            type="number" 
                            step="0.01" 
                            min="0" 
                            max="1" 
                            name="bobot[{{ $k->id }}]" 
                            value="{{ $k->bobot }}" 
                            required 
                            class="w-24 px-3 py-2 text-center font-bold text-sm bg-white border border-slate-300 rounded-xl focus:ring-sky-500 focus:border-sky-500 shadow-sm">
                        <span class="text-xs font-bold text-slate-500">({{ $k->bobot * 100 }}%)</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="p-4 rounded-2xl bg-sky-50 border border-sky-100 flex items-center justify-between">
            <span class="text-xs font-bold text-sky-900">Total Akumulasi Bobot Kriteria:</span>
            <span class="font-outfit font-extrabold text-lg text-sky-700">
                {{ $totalBobot }} / 1.0 ({{ $totalBobot * 100 }}%)
            </span>
        </div>

        <div class="flex items-center justify-end pt-2">
            <button type="submit" class="px-6 py-3 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold shadow-lg transition flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Perubahan & Rekalkulasi SAW</span>
            </button>
        </div>
    </form>
</div>
@endsection
