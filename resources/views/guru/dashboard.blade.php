@extends('layouts.app')

@section('title', 'Dashboard Guru BK')
@section('page_heading', 'Dashboard Admin Guru BK')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header Card -->
    <div class="rounded-3xl gradient-header p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10">
            <span class="inline-block px-3 py-1 bg-sky-500/20 border border-sky-400/30 rounded-full text-xs font-semibold text-sky-300 mb-3">
                <i class="fa-solid fa-user-shield mr-1"></i> Admin Utama Guru BK
            </span>
            <h2 class="font-outfit text-3xl font-extrabold tracking-tight">Selamat Datang, {{ auth()->user()->name }}</h2>
            <p class="text-slate-300 text-sm mt-2 max-w-2xl leading-relaxed">
                Kelola data nilai rapor semester 1–5, kuesioner kecerdasan majemuk, minat bakat, serta audit perhitungan matriks Simple Additive Weighting (SAW) untuk rekomendasi jurusan siswa SMAN 11 Garut.
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center font-bold text-xl">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Siswa</p>
                <h3 class="font-outfit text-2xl font-bold text-slate-800">{{ $totalSiswa }}</h3>
                <p class="text-[11px] text-sky-600 font-medium mt-0.5">{{ $siswaCalculated }} Siswa terhitung SAW</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xl">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Jurusan</p>
                <h3 class="font-outfit text-2xl font-bold text-slate-800">{{ $totalJurusan }}</h3>
                <p class="text-[11px] text-emerald-600 font-medium mt-0.5">5 Peminatan (F1 - F5)</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-xl">
                <i class="fa-solid fa-brain"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pertanyaan Tes</p>
                <h3 class="font-outfit text-2xl font-bold text-slate-800">{{ $totalPertanyaanKecerdasan + $totalPertanyaanMinat }}</h3>
                <p class="text-[11px] text-purple-600 font-medium mt-0.5">{{ $totalPertanyaanKecerdasan }} Kecerdasan • {{ $totalPertanyaanMinat }} Minat</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-xl">
                <i class="fa-solid fa-calculator"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Metode SPK</p>
                <h3 class="font-outfit text-xl font-bold text-slate-800">SAW Method</h3>
                <p class="text-[11px] text-amber-600 font-medium mt-0.5">C1: 40% • C2: 30% • C3: 30%</p>
            </div>
        </div>
    </div>

    <!-- Peminatan Overview Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Peminatan Cards -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-outfit text-lg font-bold text-slate-800">5 Peminatan Kurikulum SMAN 11 Garut</h3>
                    <p class="text-xs text-slate-500">Struktur alokasi kelas dan bidang jurusan</p>
                </div>
                <a href="{{ route('guru.siswa.index') }}" class="text-xs text-sky-600 hover:text-sky-700 font-semibold">
                    Kelola Siswa <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="space-y-3">
                @foreach($peminatanList as $pem)
                    <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50 flex items-center justify-between hover:bg-slate-100/80 transition">
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1.5 rounded-xl font-outfit font-extrabold text-sm text-white bg-slate-800 shadow">
                                {{ $pem->kode }}
                            </span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">{{ $pem->nama }}</h4>
                                <p class="text-xs text-slate-500">Kelas: <span class="font-medium text-slate-700">{{ $pem->daftar_kelas }}</span></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 rounded-full bg-sky-100 text-sky-700 text-xs font-semibold">
                                {{ $pem->siswa_count }} Siswa
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Major Recommendation Chart/List -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-outfit text-lg font-bold text-slate-800">Distribusi Jurusan Top #1</h3>
            <p class="text-xs text-slate-500">Jurusan terbanyak direkomendasikan sistem SAW</p>

            <div class="space-y-3 pt-2">
                @forelse($topMajors as $tm)
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700">{{ $tm['nama'] }}</span>
                            <span class="text-sky-600">{{ $tm['count'] }} Siswa</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-sky-500 h-2.5 rounded-full" style="width: {{ min(100, $tm['count'] * 25) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic text-center py-6">Belum ada hasil perangkingan SAW.</p>
                @endforelse
            </div>

            <div class="pt-4 border-t border-slate-100">
                <a href="{{ route('guru.saw.index') }}" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-calculator"></i>
                    <span>Audit Perhitungan SAW Lengkap</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
