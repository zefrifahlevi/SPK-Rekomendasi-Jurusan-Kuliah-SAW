@extends('layouts.app')

@section('title', 'Master Jurusan Kuliah')
@section('page_heading', 'Kelola Master Data Jurusan Kuliah & Rumpun')

@section('content')
<div class="space-y-6" x-data="{ modalOpen: false, editMode: false, currentJurusan: {} }">
    <!-- Header Filter & Add Button -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('guru.jurusan.index') }}" class="flex items-center space-x-3 flex-1 max-w-md">
            <select name="peminatan_id" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-300 text-slate-700 text-xs rounded-xl px-3 py-2.5 font-medium focus:ring-sky-500 focus:border-sky-500">
                <option value="">-- Semua Peminatan SMAN 11 Garut --</option>
                @foreach($peminatanList as $pem)
                    <option value="{{ $pem->id }}" {{ request('peminatan_id') == $pem->id ? 'selected' : '' }}>
                        {{ $pem->kode }} - {{ $pem->nama }}
                    </option>
                @endforeach
            </select>
        </form>

        <button 
            @click="modalOpen = true; editMode = false; currentJurusan = {}"
            class="px-4 py-2.5 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center justify-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Jurusan Baru</span>
        </button>
    </div>

    <!-- Jurusan Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($jurusanList as $j)
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-full bg-slate-900 text-white text-[10px] font-bold">
                            {{ $j->rumpun->peminatan->kode ?? 'Peminatan' }}
                        </span>
                        <span class="text-[11px] font-semibold text-sky-600">
                            {{ $j->rumpun->nama ?? 'Rumpun' }}
                        </span>
                    </div>

                    <h3 class="font-outfit text-lg font-bold text-slate-800 leading-tight">{{ $j->nama }}</h3>
                    <p class="text-xs text-slate-500 line-clamp-2">{{ $j->deskripsi ?? 'Belum ada deskripsi.' }}</p>

                    <div class="pt-2 space-y-1 text-[11px] text-slate-600 border-t border-slate-100">
                        <p class="flex items-center space-x-1.5">
                            <i class="fa-solid fa-briefcase text-slate-400"></i>
                            <span class="font-medium text-slate-700 truncate">Prospek: {{ $j->prospek_kerja ?? '-' }}</span>
                        </p>
                        <p class="flex items-center space-x-1.5">
                            <i class="fa-solid fa-brain text-purple-500"></i>
                            <span>Target Kecerdasan: <strong>{{ $j->kecerdasanUtama->nama ?? 'Umum' }}</strong></span>
                        </p>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button 
                        @click="modalOpen = true; editMode = true; currentJurusan = {{ json_encode($j) }}"
                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                        Edit
                    </button>
                    <form action="{{ route('guru.jurusan.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus jurusan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-semibold transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-8 rounded-3xl border border-slate-200 text-center text-slate-400 italic">
                Belum ada jurusan kuliah.
            </div>
        @endforelse
    </div>

    <div class="pt-4">
        {{ $jurusanList->withQueryString()->links() }}
    </div>

    <!-- Modal Form (Tambah/Edit Jurusan) -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4" @click.away="modalOpen = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-outfit text-lg font-bold text-slate-800" x-text="editMode ? 'Edit Jurusan Kuliah' : 'Tambah Jurusan Baru'"></h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="editMode ? '{{ url('guru/jurusan') }}/' + currentJurusan.id : '{{ route('guru.jurusan.store') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nama Jurusan Kuliah</label>
                    <input type="text" name="nama" :value="currentJurusan.nama" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Rumpun Jurusan & Peminatan</label>
                    <select name="rumpun_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                        <option value="">-- Pilih Rumpun --</option>
                        @foreach($rumpunList as $r)
                            <option value="{{ $r->id }}" :selected="currentJurusan.rumpun_id == {{ $r->id }}">
                                [{{ $r->peminatan->kode ?? 'F' }}] {{ $r->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Kecerdasan Relevan</label>
                        <select name="kategori_kecerdasan_utama_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                            <option value="">-- Opsional --</option>
                            @foreach($kategoriKecerdasan as $kk)
                                <option value="{{ $kk->id }}" :selected="currentJurusan.kategori_kecerdasan_utama_id == {{ $kk->id }}">{{ $kk->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Mapel Utama Relevan</label>
                        <select name="mapel_utama_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
                            <option value="">-- Opsional --</option>
                            @foreach($mataPelajaran as $mp)
                                <option value="{{ $mp->id }}" :selected="currentJurusan.mapel_utama_id == {{ $mp->id }}">{{ $mp->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Deskripsi Singkat Jurusan</label>
                    <textarea name="deskripsi" :value="currentJurusan.deskripsi" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Prospek Kerja / Karir</label>
                    <input type="text" name="prospek_kerja" :value="currentJurusan.prospek_kerja" placeholder="e.g. Auditor, Financial Analyst" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-sky-500 focus:border-sky-500">
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
