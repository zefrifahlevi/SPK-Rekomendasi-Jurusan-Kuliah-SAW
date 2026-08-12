@extends('layouts.app')

@section('title', 'Bank Pertanyaan Tes')
@section('page_heading', 'Kelola Data Pertanyaan Kecerdasan Majemuk & Minat Bakat')

@section('content')
<div class="space-y-6" x-data="{ tab: 'kecerdasan' }">
    <!-- Tab Navigation Bar -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-sm flex space-x-2">
        <button 
            @click="tab = 'kecerdasan'"
            :class="tab === 'kecerdasan' ? 'bg-sky-600 text-white shadow-md font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
            class="px-5 py-2.5 rounded-xl text-xs transition flex items-center space-x-2">
            <i class="fa-solid fa-brain"></i>
            <span>1. Pertanyaan Kecerdasan Majemuk (Multiple Intelligences)</span>
        </button>
        <button 
            @click="tab = 'minat'"
            :class="tab === 'minat' ? 'bg-sky-600 text-white shadow-md font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
            class="px-5 py-2.5 rounded-xl text-xs transition flex items-center space-x-2">
            <i class="fa-solid fa-heart"></i>
            <span>2. Pertanyaan Minat Bakat (Angket SAW)</span>
        </button>
    </div>

    <!-- Section 1: Kecerdasan Majemuk -->
    <div x-show="tab === 'kecerdasan'" class="space-y-6">
        <!-- Form Tambah Pertanyaan Kecerdasan -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <h3 class="font-outfit text-base font-bold text-slate-800 mb-3">Tambah Pertanyaan Kecerdasan Majemuk</h3>
            <form action="{{ route('guru.pertanyaan.kecerdasan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Kategori Kecerdasan</label>
                    <select name="kategori_kecerdasan_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriKecerdasan as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->kode }} - {{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Teks Pertanyaan (Skala Likert 1-5)</label>
                    <input type="text" name="pertanyaan" required placeholder="Tuliskan butir pertanyaan..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full py-2 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold shadow transition">
                        + Tambah Pertanyaan
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Pertanyaan Kecerdasan -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100/80 text-slate-700 font-bold border-b border-slate-200">
                        <th class="p-4 w-12">No</th>
                        <th class="p-4">Kategori Kecerdasan</th>
                        <th class="p-4">Butir Pertanyaan</th>
                        <th class="p-4 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pertanyaanKecerdasan as $index => $pk)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 text-slate-400 font-medium">{{ $pertanyaanKecerdasan->firstItem() + $index }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full bg-purple-100 text-purple-800 font-bold text-[11px]">
                                    {{ $pk->kategori->kode ?? '' }} - {{ $pk->kategori->nama ?? '' }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-800 font-medium leading-relaxed">{{ $pk->pertanyaan }}</td>
                            <td class="p-4 text-center">
                                <form action="{{ route('guru.pertanyaan.kecerdasan.destroy', $pk->id) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400 italic">Belum ada data pertanyaan kecerdasan majemuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100">
                {{ $pertanyaanKecerdasan->appends(['page_m' => request('page_m')])->links() }}
            </div>
        </div>
    </div>

    <!-- Section 2: Minat Bakat -->
    <div x-show="tab === 'minat'" class="space-y-6" x-cloak>
        <!-- Form Tambah Pertanyaan Minat -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <h3 class="font-outfit text-base font-bold text-slate-800 mb-3">Tambah Pertanyaan Minat Bakat</h3>
            <form action="{{ route('guru.pertanyaan.minat.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Kategori Rumpun Target</label>
                    <input type="text" name="kategori_target" required placeholder="e.g. Ekonomi & Bisnis" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Teks Pertanyaan</label>
                    <input type="text" name="pertanyaan" required placeholder="Tuliskan pertanyaan angket minat..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full py-2 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold shadow transition">
                        + Tambah Pertanyaan
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Pertanyaan Minat -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100/80 text-slate-700 font-bold border-b border-slate-200">
                        <th class="p-4 w-12">No</th>
                        <th class="p-4">Kategori Rumpun Target</th>
                        <th class="p-4">Butir Pertanyaan</th>
                        <th class="p-4 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pertanyaanMinat as $index => $pm)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 text-slate-400 font-medium">{{ $pertanyaanMinat->firstItem() + $index }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[11px]">
                                    {{ $pm->kategori_target }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-800 font-medium leading-relaxed">{{ $pm->pertanyaan }}</td>
                            <td class="p-4 text-center">
                                <form action="{{ route('guru.pertanyaan.minat.destroy', $pm->id) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400 italic">Belum ada data pertanyaan minat bakat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100">
                {{ $pertanyaanMinat->appends(['page_k' => request('page_k')])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
