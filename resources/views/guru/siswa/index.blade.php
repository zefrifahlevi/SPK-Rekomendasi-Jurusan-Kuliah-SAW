@extends('layouts.app')

@section('title', 'Data Siswa & Rapor')
@section('page_heading', 'Kelola Data Siswa & Rapor Semester 1–5')

@section('content')
<div class="space-y-6" x-data="{ modalOpen: false, editMode: false, currentSiswa: {} }">
    <!-- Action Bar & Search Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
        <!-- Filter Peminatan & Search -->
        <form method="GET" action="{{ route('guru.siswa.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="w-48">
                <select name="peminatan_id" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-300 text-slate-700 text-xs rounded-xl px-3 py-2.5 font-medium focus:ring-sky-500 focus:border-sky-500">
                    <option value="">-- Semua Peminatan --</option>
                    @foreach($peminatanList as $pem)
                        <option value="{{ $pem->id }}" {{ request('peminatan_id') == $pem->id ? 'selected' : '' }}>
                            {{ $pem->kode }} - {{ $pem->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="relative flex-1 max-w-xs">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NISN / Kelas..." class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:ring-sky-500 focus:border-sky-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            </div>
            <button type="submit" class="px-3 py-2 bg-slate-800 text-white text-xs font-semibold rounded-xl hover:bg-slate-700 transition">
                Filter
            </button>
            @if(request()->hasAny(['peminatan_id', 'search']))
                <a href="{{ route('guru.siswa.index') }}" class="text-xs text-rose-600 hover:underline">Reset</a>
            @endif
        </form>

        <button 
            @click="modalOpen = true; editMode = false; currentSiswa = {}"
            class="px-4 py-2.5 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center justify-center space-x-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Siswa Baru</span>
        </button>
    </div>

    <!-- Student Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100/80 text-slate-600 font-semibold border-b border-slate-200">
                        <th class="p-4">No</th>
                        <th class="p-4">Nama Siswa</th>
                        <th class="p-4">NISN</th>
                        <th class="p-4">Kelas</th>
                        <th class="p-4">Peminatan</th>
                        <th class="p-4 text-center">Status Rapor</th>
                        <th class="p-4 text-center">Rekomendasi Top #1 SAW</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswaList as $index => $s)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 text-slate-400 font-medium">{{ $siswaList->firstItem() + $index }}</td>
                            <td class="p-4">
                                <span class="font-bold text-slate-800 block text-sm">{{ $s->name }}</span>
                                <span class="text-[11px] text-slate-400">{{ $s->email }}</span>
                            </td>
                            <td class="p-4 font-mono font-medium text-slate-700">{{ $s->nisn }}</td>
                            <td class="p-4 font-semibold text-slate-700">{{ $s->kelas }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-800 text-white">
                                    {{ $s->peminatan->kode ?? 'N/A' }}
                                </span>
                                <span class="text-[11px] text-slate-500 block mt-0.5">{{ $s->peminatan->nama ?? '' }}</span>
                            </td>
                            <td class="p-4 text-center">
                                @if($s->nilaiRapor()->exists())
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-semibold text-[11px] inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                        <span>Terisi</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 font-semibold text-[11px]">Belum</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                @php
                                    $topSaw = $s->hasilSaw->where('ranking', 1)->first();
                                @endphp
                                @if($topSaw)
                                    <span class="px-2.5 py-1 rounded-full bg-sky-100 text-sky-800 font-bold text-[11px] border border-sky-200">
                                        {{ $topSaw->jurusan->nama ?? 'N/A' }} (V: {{ $topSaw->nilai_v }})
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">- Belum ada -</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Input Rapor Button -->
                                    <a href="{{ route('guru.rapor.input', $s->id) }}" title="Input Nilai Rapor (Sem 1-5)" class="px-2.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-[11px] font-bold transition flex items-center space-x-1">
                                        <i class="fa-solid fa-file-pen"></i>
                                        <span>Rapor</span>
                                    </a>

                                    <!-- Edit Siswa -->
                                    <button 
                                        @click="modalOpen = true; editMode = true; currentSiswa = {{ json_encode($s) }}" 
                                        title="Edit Siswa" class="p-1.5 text-slate-500 hover:text-sky-600 transition">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <!-- Delete Siswa -->
                                    <form action="{{ route('guru.siswa.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Siswa" class="p-1.5 text-slate-400 hover:text-rose-600 transition">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400 italic">Tidak ada data siswa yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $siswaList->withQueryString()->links() }}
        </div>
    </div>

    <!-- Modal Form (Tambah / Edit Siswa) -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4" @click.away="modalOpen = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-outfit text-lg font-bold text-slate-800" x-text="editMode ? 'Edit Data Siswa' : 'Tambah Siswa Baru'"></h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="editMode ? '{{ url('guru/siswa') }}/' + currentSiswa.id : '{{ route('guru.siswa.store') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nama Lengkap Siswa</label>
                    <input type="text" name="name" :value="currentSiswa.name" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">NISN</label>
                        <input type="text" name="nisn" :value="currentSiswa.nisn" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Kelas (XI-1 s/d XII-12)</label>
                        <input type="text" name="kelas" :value="currentSiswa.kelas" placeholder="e.g. XI-1" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Peminatan Kurikulum</label>
                    <select name="peminatan_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                        <option value="">-- Pilih Peminatan --</option>
                        @foreach($peminatanList as $pem)
                            <option value="{{ $pem->id }}" :selected="currentSiswa.peminatan_id == {{ $pem->id }}">
                                {{ $pem->kode }} - {{ $pem->nama }} ({{ $pem->daftar_kelas }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Email</label>
                    <input type="email" name="email" :value="currentSiswa.email" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                        <span x-text="editMode ? 'Password (Kosongkan jika tidak diubah)' : 'Password'"></span>
                    </label>
                    <input type="password" name="password" :required="!editMode" placeholder="••••••••" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded-xl text-xs font-bold hover:bg-sky-500 shadow-md">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
