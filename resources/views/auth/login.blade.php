<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPK Rekomendasi Jurusan SMAN 11 Garut</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .bg-mesh {
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, hsla(217,91%,60%,0.2) 0px, transparent 50%),
                radial-gradient(at 100% 100%, hsla(242,88%,64%,0.2) 0px, transparent 50%),
                radial-gradient(at 50% 50%, hsla(199,89%,48%,0.15) 0px, transparent 50%);
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-100 bg-mesh flex items-center justify-center p-4">

    <div class="w-full max-w-md" x-data="{ tab: 'siswa', loginId: '0051234501' }">
        <!-- Logo & Title Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-500 to-indigo-600 shadow-2xl text-white font-outfit font-extrabold text-2xl mb-4 border border-sky-400/30">
                11
            </div>
            <h1 class="font-outfit text-2xl font-bold text-white tracking-tight">Sistem Rekomendasi Jurusan</h1>
            <p class="text-sm text-sky-400 mt-1">Implementasi SAW • SMAN 11 Garut</p>
        </div>

        <!-- Glassmorphism Auth Card -->
        <div class="bg-slate-800/80 backdrop-blur-xl border border-slate-700/60 rounded-3xl p-8 shadow-2xl">
            <!-- Role Toggle Tabs -->
            <div class="grid grid-cols-2 p-1 bg-slate-900/60 rounded-2xl mb-6 border border-slate-700/50">
                <button 
                    type="button" 
                    @click="tab = 'siswa'; loginId = '0051234501'"
                    :class="tab === 'siswa' ? 'bg-sky-600 text-white shadow-md font-semibold' : 'text-slate-400 hover:text-white font-medium'"
                    class="py-2.5 rounded-xl text-xs transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span>Login Siswa</span>
                </button>
                <button 
                    type="button" 
                    @click="tab = 'guru'; loginId = 'admin@sman11garut.sch.id'"
                    :class="tab === 'guru' ? 'bg-indigo-600 text-white shadow-md font-semibold' : 'text-slate-400 hover:text-white font-medium'"
                    class="py-2.5 rounded-xl text-xs transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>Login Guru BK</span>
                </button>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        <span x-text="tab === 'siswa' ? 'NISN / Email Siswa' : 'Email Admin Guru BK'"></span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i :class="tab === 'siswa' ? 'fa-solid fa-id-card' : 'fa-solid fa-envelope'"></i>
                        </div>
                        <input 
                            type="text" 
                            name="login_id" 
                            x-model="loginId"
                            required 
                            placeholder="Masukkan NISN atau Email"
                            class="w-full pl-10 pr-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            value="password"
                            required 
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                    </div>
                </div>

                @if($errors->any())
                    <div class="p-3 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-300 text-xs flex items-center space-x-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <button 
                    type="submit" 
                    :class="tab === 'siswa' ? 'bg-sky-600 hover:bg-sky-500' : 'bg-indigo-600 hover:bg-indigo-500'"
                    class="w-full py-3.5 rounded-xl font-bold text-sm text-white shadow-lg transition flex items-center justify-center space-x-2 mt-4">
                    <span>Masuk ke Sistem</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="mt-6 pt-5 border-t border-slate-700/60 text-xs text-slate-400 space-y-2">
                <p class="font-semibold text-slate-300 flex items-center space-x-1.5">
                    <i class="fa-solid fa-circle-info text-sky-400"></i>
                    <span>Akun Demo Pengujian:</span>
                </p>
                <div class="bg-slate-900/50 p-2.5 rounded-xl border border-slate-800 space-y-1 font-mono text-[11px]">
                    <p><span class="text-sky-400">Guru BK:</span> admin@sman11garut.sch.id / password</p>
                    <p><span class="text-amber-400">Siswa (F1):</span> 0051234501 / password</p>
                    <p><span class="text-emerald-400">Siswa (F2):</span> 0051234502 / password</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
