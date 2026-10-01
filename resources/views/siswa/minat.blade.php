@extends('layouts.app')

@section('title', 'Kuesioner Minat Bakat')
@section('page_heading', 'Kuesioner Tes Minat Bakat Jurusan Kuliah')

@section('content')
<div class="space-y-6 max-w-4xl">
    <!-- Instruction Banner -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2">
        <h3 class="font-outfit text-lg font-bold text-slate-800 flex items-center space-x-2">
            <i class="fa-solid fa-heart text-rose-500"></i>
            <span>Instruksi Pengisian Tes Minat Bakat</span>
        </h3>
        <p class="text-xs text-slate-600 leading-relaxed">
            Berikan nilai minat Anda terhadap rumpun keahlian jurusan kuliah berikut dari skala 1 (Sangat Tidak Berminat) hingga 5 (Sangat Berminat):
        </p>
    </div>

    <!-- Questionnaire Form -->
    <form action="{{ route('siswa.minat.store') }}" method="POST" class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6">
        @csrf

        <div class="space-y-4">
            @foreach($pertanyaanList as $index => $pm)
                @php
                    $val = $jawabanExisting[$pm->id] ?? 3;
                @endphp
                <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-3">
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 font-bold text-[11px]">
                            Rumpun Target: {{ $pm->kategori_target }}
                        </span>
                    </div>
                    <p class="text-xs font-semibold text-slate-800 leading-relaxed">{{ $pm->pertanyaan }}</p>
                    
                    <div class="grid grid-cols-5 gap-2 pt-1">
                        @for($s = 1; $s <= 5; $s++)
                            <label class="relative block cursor-pointer select-none">
                                <input 
                                    type="radio" 
                                    name="jawaban[{{ $pm->id }}]" 
                                    value="{{ $s }}" 
                                    {{ $val == $s ? 'checked' : '' }} 
                                    class="peer sr-only">
                                <div class="py-2 text-center text-xs font-bold rounded-xl border border-slate-300 bg-white text-slate-700 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-600 peer-checked:shadow-md transition">
                                    Skor {{ $s }}
                                </div>
                            </label>
                        @endfor
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('siswa.dashboard') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold shadow-lg transition flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Jawaban Minat</span>
            </button>
        </div>
    </form>
</div>
@endsection
