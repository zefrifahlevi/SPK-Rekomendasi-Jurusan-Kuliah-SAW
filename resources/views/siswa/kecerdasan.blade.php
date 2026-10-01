@extends('layouts.app')

@section('title', 'Kuesioner Kecerdasan Majemuk')
@section('page_heading', 'Kuesioner Tes Kecerdasan Majemuk (Multiple Intelligences)')

@section('content')
<div class="space-y-6 max-w-4xl">
    <!-- Instruction Banner -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2">
        <h3 class="font-outfit text-lg font-bold text-slate-800 flex items-center space-x-2">
            <i class="fa-solid fa-brain text-purple-600"></i>
            <span>Instruksi Pengisian Tes Kecerdasan Majemuk</span>
        </h3>
        <p class="text-xs text-slate-600 leading-relaxed">
            Pilihlah skala penilaian 1 sampai 5 yang paling sesuai dengan gambaran diri Anda sesungguhnya:
        </p>
        <div class="flex flex-wrap gap-3 pt-2 text-[11px] font-semibold text-slate-700">
            <span class="px-2.5 py-1 bg-slate-100 rounded-lg">1 = Sangat Tidak Sesuai</span>
            <span class="px-2.5 py-1 bg-slate-100 rounded-lg">2 = Tidak Sesuai</span>
            <span class="px-2.5 py-1 bg-slate-100 rounded-lg">3 = Ragu-Ragu / Cukup</span>
            <span class="px-2.5 py-1 bg-slate-100 rounded-lg">4 = Sesuai</span>
            <span class="px-2.5 py-1 bg-sky-100 text-sky-800 rounded-lg">5 = Sangat Sesuai</span>
        </div>
    </div>

    <!-- Questionnaire Form -->
    <form action="{{ route('siswa.kecerdasan.store') }}" method="POST" class="space-y-6">
        @csrf

        @foreach($kategoriList as $kat)
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div class="border-b border-slate-100 pb-3 flex items-center space-x-3">
                    <span class="px-3 py-1 rounded-xl bg-purple-100 text-purple-900 font-outfit font-extrabold text-xs">
                        {{ $kat->kode }}
                    </span>
                    <div>
                        <h4 class="font-outfit font-bold text-slate-800 text-base">{{ $kat->nama }}</h4>
                        <p class="text-xs text-slate-500">{{ $kat->deskripsi }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($kat->pertanyaan as $pk)
                        @php
                            $val = $jawabanExisting[$pk->id] ?? 3;
                        @endphp
                        <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-3">
                            <p class="text-xs font-semibold text-slate-800 leading-relaxed">{{ $pk->pertanyaan }}</p>
                            
                            <div class="grid grid-cols-5 gap-2">
                                @for($s = 1; $s <= 5; $s++)
                                    <label class="relative block cursor-pointer select-none">
                                        <input 
                                            type="radio" 
                                            name="jawaban[{{ $pk->id }}]" 
                                            value="{{ $s }}" 
                                            {{ $val == $s ? 'checked' : '' }} 
                                            class="peer sr-only">
                                        <div class="py-2 text-center text-xs font-bold rounded-xl border border-slate-300 bg-white text-slate-700 peer-checked:bg-purple-600 peer-checked:text-white peer-checked:border-purple-600 peer-checked:shadow-md transition">
                                            Skor {{ $s }}
                                        </div>
                                    </label>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex items-center justify-end space-x-3 pt-4">
            <a href="{{ route('siswa.dashboard') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-bold shadow-lg transition flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Jawaban Kecerdasan</span>
            </button>
        </div>
    </form>
</div>
@endsection
