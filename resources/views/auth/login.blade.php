<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Monitoring Kelas & CCTV</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #1e293b, #0f172a);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-10">

    <div
        class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[540px] border border-white/20">

        <!-- LEFT PANEL: CAMPUS BRANDING & HERO -->
        <div
            class="md:w-1/2 relative bg-gradient-to-br from-[#0b1628] via-[#122340] to-[#1e3b66] text-white flex flex-col justify-between p-8 sm:p-10 overflow-hidden">
            <!-- Campus background overlay -->
            <img src="{{ asset('images/campus.jpg') }}" alt="Campus Building"
                class="absolute inset-0 w-full h-full object-cover opacity-25 mix-blend-overlay pointer-events-none">

            <!-- Top badge -->
            <div class="relative z-10 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-semibold text-sky-300 tracking-wider uppercase">Politeknik Negeri
                    Malang</span>
            </div>

            <!-- Center brand title -->
            <div class="relative z-10 text-center flex flex-col items-center py-8">
                <div
                    class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-blue-600 to-sky-400 flex items-center justify-center mb-5 shadow-xl shadow-blue-500/30 border border-white/20 ring-4 ring-white/10">
                    <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z" />
                    </svg>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black tracking-tight leading-tight">
                    Sistem Monitoring<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-blue-300">Kelas &
                        CCTV</span>
                </h1>

                <p class="text-xs text-slate-300 mt-2 max-w-xs font-medium leading-relaxed">
                    Platform pengawasan jadwal, ketersediaan ruangan, dan pemantauan CCTV secara real-time.
                </p>
            </div>

            <!-- Footer copyright -->
            <div class="relative z-10 text-[11px] text-slate-400 text-center">
                &copy; {{ date('Y') }} Sistem Monitoring Ruangan & CCTV
            </div>

            <!-- Decorative Glows -->
            <div
                class="absolute -bottom-16 -left-16 w-52 h-52 rounded-full bg-blue-500/20 blur-3xl pointer-events-none">
            </div>
            <div class="absolute -top-16 -right-16 w-52 h-52 rounded-full bg-sky-400/20 blur-3xl pointer-events-none">
            </div>
        </div>

        <!-- RIGHT PANEL: LOGIN FORM -->
        <div class="md:w-1/2 bg-white p-8 sm:p-12 flex flex-col justify-center">
            <div class="max-w-sm mx-auto w-full">

                <div class="mb-7">
                    <span
                        class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100 mb-2">
                        Portal Otentikasi
                    </span>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Login Administrator</h2>
                    <p class="text-xs text-slate-400 mt-1">Masukkan kredensial akun untuk mengakses sistem.</p>
                </div>
                <!-- Menampilkan pesan error manual (seperti dari Google Login) -->
                @if(session('error'))
                    <div
                        class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div
                        class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Username / Email Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Username /
                            Email</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                            </span>
                            <input type="text" name="username" id="username" value="{{ old('username', 'Admin') }}"
                                required placeholder="Admin / admin@monitoring.com"
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Password</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0110 0v4"></path>
                                </svg>
                            </span>
                            <input type="password" name="password" id="password" value="admin123" required
                                placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    <!-- Login Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-xl text-xs sm:text-sm shadow-lg shadow-blue-500/25 active:scale-[0.99] transition-all mb-3">
                            Masuk ke Dashboard
                        </button>

                        <!-- Tombol Login Google -->
                        <a href="{{ route('google.login') }}"
                            class="w-full flex items-center justify-center gap-2 py-3.5 px-4 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-extrabold rounded-xl text-xs sm:text-sm shadow-sm active:scale-[0.99] transition-all">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                <path fill="#EA4335"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            Login dengan Google
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>

</body>

</html>