<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Monitoring Kelas & CCTV') - Smart Classroom Monitoring</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    spacing: {
                        '68': '17rem',
                    },
                    colors: {
                        navy: {
                            800: '#111d33',
                            900: '#0b1628',
                            950: '#060c18',
                        },
                        brand: {
                            blue: '#2563eb',
                            hoverBlue: '#1d4ed8',
                            sky: '#0284c7',
                            emerald: '#059669',
                            amber: '#d97706',
                            rose: '#e11d48',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }

        /* Custom Scrollbar for Main Page */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Dedicated Scrollbar for Sidebar Navigation */
        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.2);
            border-radius: 9999px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.4);
        }
        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 163, 184, 0.2) transparent;
        }

        /* Explicit helper fallback for w-68 and lg:ml-68 */
        .w-68 {
            width: 17rem;
        }
        @media (min-width: 1024px) {
            .lg\:ml-68 {
                margin-left: 17rem;
            }
        }

        /* Nav link transition */
        .sidebar-item {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-item:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            transform: translateX(3px);
        }
        .sidebar-item.active {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px -2px rgba(37, 99, 235, 0.45);
        }

        /* Animations */
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.1); }
        }
        .animate-live-dot {
            animation: pulse-slow 1.8s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(1000%); }
        }
        .animate-scan {
            animation: scanline 8s linear infinite;
        }

        @media print {
            aside, header, #sidebarBackdrop, .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
            .lg\:ml-68 {
                margin-left: 0 !important;
            }
            main {
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 antialiased min-h-screen selection:bg-blue-500 selection:text-white">

    <!-- MOBILE SIDEBAR BACKDROP OVERLAY -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs z-30 lg:hidden hidden transition-opacity"></div>

    <!-- LEFT SIDEBAR -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 lg:z-30 w-68 h-screen h-[100dvh] bg-[#0b1628] text-slate-300 flex flex-col border-r border-slate-800/80 shadow-2xl transition-all duration-300 hidden lg:flex">
        
        <!-- BRAND LOGO -->
        <div class="p-5 pb-4 flex items-center gap-3.5 border-b border-slate-800/80 shrink-0">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-sky-400 flex items-center justify-center text-white shadow-lg shadow-blue-500/25 ring-2 ring-white/10 shrink-0">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z"/>
                </svg>
            </div>
            <div class="overflow-hidden">
                <div class="flex items-center gap-1.5">
                    <h1 class="font-extrabold text-white text-sm tracking-tight leading-tight">MONITORING</h1>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/20 text-blue-400 border border-blue-400/30">PRO</span>
                </div>
                <p class="text-xs font-semibold text-sky-400 tracking-wider uppercase">Kelas & CCTV</p>
            </div>
        </div>

        <!-- NAVIGATION MENU -->
        <nav class="sidebar-scroll flex-1 min-h-0 overflow-y-auto p-3.5 space-y-6 text-sm">
            <!-- Group 1: Monitoring Utama -->
            <div class="space-y-1">
                <span class="px-3 text-[11px] font-bold tracking-wider text-slate-400 uppercase">Menu Utama</span>
                
                <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium {{ request()->routeIs('dashboard*') ? 'active' : 'text-slate-300' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>

                <a href="{{ route('cctv.index') }}" class="sidebar-item flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium {{ request()->routeIs('cctv*') ? 'active' : 'text-slate-300' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                        </svg>
                        <span>Monitoring CCTV</span>
                    </div>
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                </a>
            </div>

            <!-- Group 2: Data Master -->
            <div class="space-y-1">
                <span class="px-3 text-[11px] font-bold tracking-wider text-slate-400 uppercase">Master Data</span>
                
                <a href="{{ route('ruangan.index') }}" class="sidebar-item flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium {{ request()->routeIs('ruangan*') ? 'active' : 'text-slate-300' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>Data Ruangan</span>
                </a>

                <a href="{{ route('dosen.index') }}" class="sidebar-item flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium {{ request()->routeIs('dosen*') ? 'active' : 'text-slate-300' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Dosen</span>
                </a>

                <a href="{{ route('mata-kuliah.index') }}" class="sidebar-item flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium {{ request()->routeIs('mata-kuliah*') ? 'active' : 'text-slate-300' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span>Mata Kuliah</span>
                </a>

                <a href="{{ route('jadwal.index') }}" class="sidebar-item flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium {{ request()->routeIs('jadwal*') ? 'active' : 'text-slate-300' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>Jadwal Kuliah</span>
                </a>
            </div>

            <!-- Group 3: Operasional & Laporan -->
            <div class="space-y-1">
                <span class="px-3 text-[11px] font-bold tracking-wider text-slate-400 uppercase">Operasional</span>
                
                <a href="{{ route('sesi.index') }}" class="sidebar-item flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium {{ request()->routeIs('sesi*') ? 'active' : 'text-slate-300' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Generate Sesi</span>
                </a>

                <a href="{{ route('report.index') }}" class="sidebar-item flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium {{ request()->routeIs('report*') ? 'active' : 'text-slate-300' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Laporan Rekap</span>
                </a>
            </div>
        </nav>

        <!-- USER FOOTER & LOGOUT -->
        <div class="p-3.5 border-t border-slate-800/80 bg-slate-950/40 shrink-0">
            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-900/60 border border-slate-800/60">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-sky-400 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-sm">
                        {{ strtoupper(substr(auth()->user()->nama ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0 truncate">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->nama ?? 'Administrator' }}</p>
                        <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->role ?? 'Admin' }}</p>
                    </div>
                </div>
                <button onclick="openLogoutModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition-colors" title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen lg:ml-68">

        <!-- TOP BAR -->
        <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200/80 flex items-center justify-between px-6 sm:px-8 shrink-0 sticky top-0 z-20 shadow-xs">
            
            <!-- LEFT TITLE / STATUS -->
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>

                <div class="hidden sm:flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Sistem Aktif</span>
                    </span>
                </div>
            </div>

            <!-- RIGHT HEADER CONTROLS (Date, Live Clock, User Profile) -->
            <div class="flex items-center gap-4 sm:gap-6">
                <!-- Indonesian Date -->
                <div class="hidden md:flex items-center gap-2 text-xs font-semibold text-slate-600 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200/60">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
                        <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
                    </svg>
                    <span id="liveHeaderDate">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                </div>

                <!-- Live Digital Clock -->
                <div class="bg-blue-50/80 border border-blue-200/60 text-blue-700 px-3 py-1.5 rounded-xl text-xs font-mono font-bold tracking-wider flex items-center gap-2 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                    <span id="liveHeaderClock">{{ date('H:i:s') }}</span>
                </div>

                <!-- User Profile Pill -->
                <div class="relative">
                    <button onclick="toggleUserMenu()" class="flex items-center gap-2 pl-1.5 pr-2.5 py-1 rounded-full border border-slate-200/80 hover:bg-slate-50 transition-colors shadow-2xs">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold text-xs flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->nama ?? 'A', 0, 1)) }}
                        </div>
                        <span class="text-xs font-bold text-slate-700 hidden sm:inline">{{ auth()->user()->nama ?? 'Admin' }}</span>
                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- User Dropdown Menu -->
                    <div id="userDropdownMenu" class="hidden absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-1.5 z-40 animate-fade-in">
                        <div class="px-4 py-2.5 border-b border-slate-100">
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Login Sebagai</p>
                            <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->nama ?? 'Admin' }}</p>
                            <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email ?? 'admin@monitoring.com' }}</p>
                        </div>
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Dashboard</a>
                        <a href="{{ route('cctv.index') }}" class="block px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Live CCTV</a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <button onclick="openLogoutModal()" class="w-full text-left block px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50">Logout</button>
                    </div>
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 p-6 sm:p-8 max-w-7xl w-full mx-auto">
            <!-- Flash Message Toast -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-900 flex items-center justify-between shadow-xs animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-sm">✓</div>
                        <span class="font-semibold text-xs sm:text-sm">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-lg font-bold px-2">&times;</button>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-900 shadow-xs animate-fade-in">
                    <div class="font-bold text-xs sm:text-sm mb-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>Perhatian:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 ml-6 text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- LOGOUT CONFIRMATION MODAL -->
    <div id="logoutModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center z-50 hidden transition-all">
        <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-sm w-full mx-4 shadow-2xl border border-slate-100 text-center transform transition-all">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </div>
            <h3 class="text-base font-extrabold text-slate-900 mb-1">Konfirmasi Logout</h3>
            <p class="text-xs text-slate-500 mb-6 leading-relaxed">Apakah Anda yakin ingin keluar dari sistem monitoring ruangan?</p>

            <form action="{{ route('logout') }}" method="POST" class="flex gap-3 justify-center">
                @csrf
                <button type="button" onclick="closeLogoutModal()" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-xs hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-md shadow-rose-600/25 transition-all">
                    Ya, Logout
                </button>
            </form>
        </div>
    </div>

    <!-- SCRIPT FOR LIVE CLOCK & UI HELPERS -->
    <script>
        function updateLiveClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;

            const headerClock = document.getElementById('liveHeaderClock');
            if (headerClock) headerClock.textContent = timeString;

            const serverClockCard = document.getElementById('serverClockCard');
            if (serverClockCard) serverClockCard.textContent = timeString;

            const cctvOverlayClock = document.getElementById('cctvOverlayClock');
            if (cctvOverlayClock) {
                const day = String(now.getDate()).padStart(2, '0');
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                const month = months[now.getMonth()];
                const year = now.getFullYear();
                cctvOverlayClock.textContent = `${day} ${month} ${year} ${timeString}`;
            }
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        function toggleUserMenu() {
            const menu = document.getElementById('userDropdownMenu');
            if (menu) menu.classList.toggle('hidden');
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) sidebar.classList.toggle('hidden');
            if (backdrop) backdrop.classList.toggle('hidden');
        }

        function openLogoutModal() {
            document.getElementById('logoutModal').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdownMenu');
            if (dropdown && !e.target.closest('.relative')) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
