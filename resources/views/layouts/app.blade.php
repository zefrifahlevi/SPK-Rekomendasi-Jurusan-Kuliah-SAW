<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPK Rekomendasi Jurusan SAW') - SMAN 11 Garut</title>
    
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0284c7',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        indigoCustom: {
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js & Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .gradient-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0369a1 100%);
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50 flex flex-col" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col justify-between shadow-xl transition-all duration-300">
            <div>
                <!-- Brand Header -->
                <div class="p-5 flex items-center justify-between border-b border-slate-800">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white font-outfit font-bold text-xl shadow-lg">
                            11
                        </div>
                        <div>
                            <h1 class="font-outfit font-bold text-base leading-tight text-white">SPK SAW Jurusan</h1>
                            <p class="text-xs text-sky-400 font-medium">SMAN 11 Garut</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1">
                    @auth
                        @if(auth()->user()->isGuruBk())
                            <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Menu Guru BK</p>
                            <a href="{{ route('guru.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition {{ request()->routeIs('guru.dashboard') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                                <span>Dashboard Admin</span>
                            </a>
                            <a href="{{ route('guru.siswa.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition {{ request()->routeIs('guru.siswa.*') || request()->routeIs('guru.rapor.*') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-user-graduate w-5 text-center"></i>
                                <span>Data Siswa & Rapor</span>
                            </a>
                            <a href="{{ route('guru.jurusan.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition {{ request()->routeIs('guru.jurusan.*') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-graduation-cap w-5 text-center"></i>
                                <span>Master Jurusan</span>
                            </a>
                            <a href="{{ route('guru.pertanyaan.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition {{ request()->routeIs('guru.pertanyaan.*') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-clipboard-question w-5 text-center"></i>
                                <span>Bank Pertanyaan</span>
                            </a>
                            <a href="{{ route('guru.kriteria.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition {{ request()->routeIs('guru.kriteria.*') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-sliders w-5 text-center"></i>
                                <span>Kriteria & Bobot SAW</span>
                            </a>
                            <a href="{{ route('guru.saw.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition {{ request()->routeIs('guru.saw.*') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-calculator w-5 text-center"></i>
                                <span>Proses Perhitungan SAW</span>
                            </a>
                        @else
                            <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Menu Siswa</p>
                            <a href="{{ route('siswa.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition {{ request()->routeIs('siswa.dashboard') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-house w-5 text-center"></i>
                                <span>Dashboard Siswa</span>
                            </a>
                            <a href="{{ route('siswa.kecerdasan.form') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition {{ request()->routeIs('siswa.kecerdasan.*') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-brain w-5 text-center"></i>
                                <span>Tes Kecerdasan Majemuk</span>
                            </a>
                            <a href="{{ route('siswa.minat.form') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition {{ request()->routeIs('siswa.minat.*') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-heart w-5 text-center"></i>
                                <span>Tes Minat Bakat</span>
                            </a>
                            <a href="{{ route('siswa.hasil') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition {{ request()->routeIs('siswa.hasil') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-trophy w-5 text-center"></i>
                                <span>Hasil Rekomendasi SAW</span>
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>

            <!-- User Footer Info & Logout -->
            @auth
            <div class="p-4 border-t border-slate-800 bg-slate-950/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-full bg-slate-800 text-sky-400 border border-slate-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <span class="inline-block px-2 py-0.5 text-[10px] font-medium rounded-full {{ auth()->user()->isGuruBk() ? 'bg-amber-500/20 text-amber-300' : 'bg-sky-500/20 text-sky-300' }}">
                                {{ auth()->user()->isGuruBk() ? 'Guru BK Admin' : 'Siswa ' . auth()->user()->kelas }}
                            </span>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" title="Keluar" class="text-slate-400 hover:text-rose-400 p-2 transition">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col overflow-y-auto">
            <!-- Top App Bar -->
            <header class="bg-white border-b border-slate-200 sticky top-0 z-30 px-6 py-4 flex items-center justify-between shadow-sm">
                <div>
                    <h2 class="font-outfit text-xl font-bold text-slate-800">@yield('page_heading', 'Dashboard')</h2>
                    <p class="text-xs text-slate-500">Sistem Pendukung Keputusan Rekomendasi Jurusan Kuliah Metode SAW</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-xs px-3 py-1.5 rounded-full bg-slate-100 font-medium text-slate-600 border border-slate-200">
                        <i class="fa-solid fa-school text-sky-600 mr-1.5"></i> SMAN 11 Garut
                    </span>
                </div>
            </header>

            <!-- Page Body -->
            <div class="p-6 flex-1 bg-slate-50">
                <!-- Session Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-800">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl shadow-sm" x-data="{ show: true }" x-show="show">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-3">
                                <i class="fa-solid fa-triangle-exclamation text-rose-500 text-lg mt-0.5"></i>
                                <div>
                                    <h4 class="text-sm font-bold text-rose-900">Terjadi Kesalahan:</h4>
                                    <ul class="mt-1 text-xs space-y-1 list-disc list-inside">
                                        @foreach($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button @click="show = false" class="text-rose-500 hover:text-rose-800">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Global Footer -->
            <footer class="bg-white border-t border-slate-200 px-6 py-4 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} SMAN 11 Garut - Sistem Pendukung Keputusan Rekomendasi Jurusan (Simple Additive Weighting - SAW)
            </footer>
        </main>
    </div>

</body>
</html>
