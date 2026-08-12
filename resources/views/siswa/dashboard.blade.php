@extends('layouts.app')

@section('title', 'Dashboard Siswa')
@section('page_heading', 'Dashboard Rekomendasi Jurusan Siswa')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header Card -->
    <div class="rounded-3xl gradient-header p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 bg-sky-500/20 border border-sky-400/30 rounded-full text-xs font-semibold text-sky-300 mb-3">
                    <i class="fa-solid fa-user-graduate mr-1"></i> Siswa Peminatan {{ $siswa->peminatan->kode ?? 'SMAN 11' }} ({{ $siswa->kelas }})
                </span>
                <h2 class="font-outfit text-3xl font-extrabold tracking-tight">Halo, {{ $siswa->name }}!</h2>
                <p class="text-slate-300 text-sm mt-2 max-w-xl leading-relaxed">
                    Selamat datang di Sistem Pendukung Keputusan Rekomendasi Jurusan Kuliah SMAN 11 Garut. Lengkapi kuesioner Anda untuk melihat jurusan kuliah terbaik sesuai bakat dan nilai rapor Anda.
                </p>
            </div>

            <a href="{{ route('siswa.hasil') }}" class="px-6 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-outfit font-extrabold text-sm rounded-2xl shadow-xl transition flex items-center space-x-2 flex-shrink-0">
                <i class="fa-solid fa-trophy"></i>
                <span>Lihat Hasil Rekomendasi</span>
            </a>
        </div>
    </div>

    <!-- Progress Steps Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Step 1: Rapor -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs">1</span>
                @if($sudahAdaNilaiRapor)
                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold">
                        <i class="fa-solid fa-check mr-1"></i> Terisi (Guru BK)
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold">Belum Diinput</span>
                @endif
            </div>

            <div>
                <h3 class="font-outfit text-base font-bold text-slate-800">Nilai Rapor (Sem 1-5)</h3>
                <p class="text-xs text-slate-500 mt-1">Data nilai rapor mata pelajaran umum & peminatan yang dikelola oleh Guru BK.</p>
            </div>
        </div>

        <!-- Step 2: Kecerdasan Majemuk -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs">2</span>
                @if($sudahIsiKecerdasan)
                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold">
                        <i class="fa-solid fa-check mr-1"></i> Selesai
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 text-[11px] font-bold">Belum Mengisi</span>
                @endif
            </div>

            <div>
                <h3 class="font-outfit text-base font-bold text-slate-800">Tes Kecerdasan Majemuk</h3>
                <p class="text-xs text-slate-500 mt-1">Tes 8 domain kecerdasan (Linguistik, Logis, Visual, Kinestetik, dll).</p>
            </div>

            <a href="{{ route('siswa.kecerdasan.form') }}" class="w-full py-2 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-xl transition text-center block">
                {{ $sudahIsiKecerdasan ? 'Edit Jawaban Kecerdasan' : 'Isi Tes Kecerdasan' }}
            </a>
        </div>

        <!-- Step 3: Minat Bakat -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs">3</span>
                @if($sudahIsiMinat)
                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold">
                        <i class="fa-solid fa-check mr-1"></i> Selesai
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 text-[11px] font-bold">Belum Mengisi</span>
                @endif
            </div>

            <div>
                <h3 class="font-outfit text-base font-bold text-slate-800">Tes Minat Bakat</h3>
                <p class="text-xs text-slate-500 mt-1">Tes preferensi angket rumpun keahlian jurusan kuliah pilihan Anda.</p>
            </div>

            <a href="{{ route('siswa.minat.form') }}" class="w-full py-2 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-xl transition text-center block">
                {{ $sudahIsiMinat ? 'Edit Jawaban Minat' : 'Isi Tes Minat Bakat' }}
            </a>
        </div>
    </div>

    <!-- Top 3 Recommendation Snapshot Card -->
    @if(count($topHasil) > 0)
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-outfit text-lg font-bold text-slate-800">Top Rekomendasi Jurusan Kuliah Anda</h3>
                    <p class="text-xs text-slate-500">Hasil dari metode Simple Additive Weighting (SAW)</p>
                </div>
                <a href="{{ route('siswa.hasil') }}" class="text-xs font-bold text-sky-600 hover:underline">Detail Transparansi <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                @foreach($topHasil as $h)
                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/80 flex flex-col justify-between space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full font-bold text-xs text-white flex items-center justify-center {{ $h->ranking == 1 ? 'bg-amber-500 shadow-md' : ($h->ranking == 2 ? 'bg-slate-400' : 'bg-amber-700') }}">
                                {{ $h->ranking }}
                            </span>
                            <span class="font-mono font-extrabold text-xs text-sky-700 bg-sky-100 px-2 py-0.5 rounded-md">
                                Skor V: {{ $h->nilai_v }}
                            </span>
                        </div>

                        <div>
                            <h4 class="font-outfit font-bold text-slate-800 text-base leading-tight">{{ $h->jurusan->nama }}</h4>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">{{ $h->jurusan->rumpun->nama ?? '' }}</p>
                        </div>

                        <p class="text-[11px] text-slate-600 line-clamp-2">{{ $h->jurusan->deskripsi }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
