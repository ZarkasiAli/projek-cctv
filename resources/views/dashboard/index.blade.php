@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-7">

    <!-- WELCOME HERO BANNER -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0b1628] via-[#132847] to-[#1e3a66] p-6 sm:p-8 text-white shadow-xl border border-slate-800/60">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-sky-300 text-xs font-semibold mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Monitoring Real-time Aktif</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ auth()->user()->nama ?? 'Admin' }}! 👋</h2>
                <p class="text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                    Sistem pemantauan visual kelas dan verifikasi pelaksanaan perkuliahan terintegrasi dengan CCTV kampus.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('cctv.index') }}" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polygon points="23 7 16 12 23 17 23 7"></polygon>
                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                    </svg>
                    <span>Buka Live CCTV</span>
                </a>
                <a href="{{ route('sesi.index') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs backdrop-blur-md border border-white/10 transition-colors">
                    Generate Sesi
                </a>
            </div>
        </div>

        <!-- Ambient decorative shapes -->
        <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-blue-500/15 blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-48 h-48 rounded-full bg-sky-400/10 blur-2xl pointer-events-none"></div>
    </div>

    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">

        <!-- Card 1: Waktu Server -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4 transition-all hover:shadow-md hover:-translate-y-0.5">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Waktu Server</p>
                <p class="text-xl sm:text-2xl font-black font-mono text-slate-800 tracking-tight mt-0.5" id="serverClockCard">{{ $currentTime }}</p>
                <span class="text-[10px] text-slate-400 font-medium">{{ $currentHari }}</span>
            </div>
        </div>

        <!-- Card 2: Kelas Aktif -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4 transition-all hover:shadow-md hover:-translate-y-0.5">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kelas Aktif</p>
                <p class="text-xl sm:text-2xl font-black text-emerald-600 tracking-tight mt-0.5">{{ $kelasAktifCount }}</p>
                <span class="text-[10px] text-emerald-600 font-medium">Sedang Berlangsung</span>
            </div>
        </div>

        <!-- Card 3: Ruangan Tersedia -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4 transition-all hover:shadow-md hover:-translate-y-0.5">
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 border border-sky-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ruangan Tersedia</p>
                <p class="text-xl sm:text-2xl font-black text-sky-600 tracking-tight mt-0.5">{{ $ruanganKosongCount }}</p>
                <span class="text-[10px] text-slate-400 font-medium">Dari {{ $totalRuangan }} Total Ruang</span>
            </div>
        </div>

        <!-- Card 4: Total Master Data -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4 transition-all hover:shadow-md hover:-translate-y-0.5">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 border border-purple-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Dosen & Mata Kuliah</p>
                <p class="text-xl sm:text-2xl font-black text-purple-700 tracking-tight mt-0.5">{{ $totalDosen }} <span class="text-xs text-slate-400 font-normal">Dosen</span></p>
                <span class="text-[10px] text-slate-400 font-medium">{{ $totalMataKuliah }} Mata Kuliah Terdaftar</span>
            </div>
        </div>

    </div>

    <!-- VISUAL ROOM MATRIX -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Kelas yang Sedang Berlangsung (Real-Time)</h3>
                <p class="text-xs text-slate-400">Hanya menampilkan ruangan dengan perkuliahan yang sedang berjalan aktif saat ini.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3 text-xs font-semibold">
                <span class="flex items-center gap-1.5 text-emerald-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-live-dot"></span>
                    <span>Aktif / Terverifikasi ({{ $kelasAktifCount }})</span>
                </span>
                <span class="flex items-center gap-1.5 text-slate-500">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                    <span>Kosong / Bebas ({{ $ruanganKosongCount }})</span>
                </span>
            </div>
        </div>

        <!-- Filter Lantai Tab Pills (Item 1: Filter Ruangan Berdasarkan Lantai) -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider shrink-0 mr-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 21h18M3 7v14m18-14v14M6 11h2m-2 4h2m8-4h2m-2 4h2M9 3l3-2 3 2v4H9V3z"/>
                </svg>
                Lantai:
            </span>
            <a
                href="{{ route('dashboard') }}"
                class="px-3 py-1.5 rounded-xl font-bold transition-all shrink-0 {{ !$selectedLantai ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
            >
                Semua Lantai ({{ $statusRuangan->count() }} Kelas Aktif)
            </a>
            @foreach($daftarLantai as $lt)
                <a
                    href="{{ route('dashboard', ['lantai' => $lt]) }}"
                    class="px-3 py-1.5 rounded-xl font-bold transition-all shrink-0 {{ $selectedLantai === $lt ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                >
                    🏢 {{ $lt }}
                </a>
            @endforeach
            @if($selectedLantai)
                <a
                    href="{{ route('dashboard') }}"
                    class="px-2.5 py-1 rounded-lg text-xs font-semibold text-rose-600 hover:text-rose-800 hover:bg-rose-50 transition-colors ml-1"
                >
                    ✕ Reset
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-1">
            @forelse($statusRuangan as $r)
                @php
                    $isVerified = $r['is_verified'] ?? false;
                    $isOngoing = $r['is_ongoing'] ?? false;
                    $isUpcoming = $r['is_upcoming'] ?? false;
                    $isUsed = $r['is_used'] ?? false;

                    // Color scheme:
                    // If verified: Vibrant emerald/green!
                    // If ongoing (not verified yet): Blue / Amber
                    // If upcoming: Slate with blue border
                    // If empty: Neutral slate
                    if ($isVerified) {
                        $cardClass = 'bg-gradient-to-br from-emerald-100/70 via-emerald-50/40 to-white border-2 border-emerald-500 ring-2 ring-emerald-500/20 shadow-md';
                        $codeClass = 'bg-emerald-600 text-white shadow-xs';
                    } elseif ($isOngoing) {
                        $cardClass = 'bg-gradient-to-br from-blue-50/50 via-sky-50/20 to-white border-blue-300 ring-1 ring-blue-400/20 shadow-xs';
                        $codeClass = 'bg-blue-600 text-white shadow-xs';
                    } elseif ($isUpcoming) {
                        $cardClass = 'bg-slate-50/80 border-slate-200 hover:border-slate-300';
                        $codeClass = 'bg-slate-200 text-slate-700';
                    } else {
                        $cardClass = 'bg-slate-50/60 border-slate-200/80';
                        $codeClass = 'bg-slate-200 text-slate-700';
                    }
                @endphp
                <div class="rounded-2xl p-4 border transition-all hover:shadow-md {{ $cardClass }}" @if($r['active_lecture']) data-jam-selesai="{{ $r['active_lecture']->jam_selesai }}" @endif>
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold {{ $codeClass }}">
                                {{ $r['kode'] }}
                            </span>
                            <div>
                                <h4 class="font-bold text-xs sm:text-sm text-slate-800">{{ $r['nama'] }}</h4>
                                <p class="text-[11px] text-slate-400">{{ $r['lokasi'] }}</p>
                            </div>
                        </div>

                        <!-- Badge Status Okupansi -->
                        @if($isVerified)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-600 text-white shadow-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Terverifikasi</span>
                            </span>
                        @elseif($isOngoing)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                <span>Sedang Berlangsung</span>
                            </span>
                        @elseif($isUpcoming)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                <span>Akan Datang</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-200/70 text-slate-600">
                                Kosong
                            </span>
                        @endif
                    </div>

                    @if($r['active_lecture'])
                        <div class="p-3 rounded-xl {{ $isVerified ? 'bg-emerald-50/90 border border-emerald-200 ring-1 ring-emerald-300/40' : 'bg-white/90 border border-slate-100' }} space-y-1.5 text-xs mb-3 shadow-2xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold truncate {{ $isVerified ? 'text-emerald-950 font-extrabold' : 'text-slate-800' }}">
                                    {{ $r['active_lecture']->mataKuliah->nama_mata_kuliah ?? 'Mata Kuliah' }}
                                </span>
                                <span class="font-mono text-[10px] font-semibold {{ $isVerified ? 'text-emerald-700 bg-emerald-100/80 border border-emerald-200' : 'text-blue-600 bg-blue-50' }} px-1.5 py-0.5 rounded">
                                    {{ $r['active_lecture']->jam_range }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500">
                                <span class="truncate">Dosen: {{ $r['active_lecture']->dosen->nama ?? '-' }}</span>
                                <span class="font-medium text-slate-600">{{ $r['active_lecture']->kelas }}</span>
                            </div>

                            @if($isVerified)
                                <div class="pt-1.5 border-t border-emerald-200/60 flex items-center justify-between text-[10px] text-emerald-800 font-bold">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Hadir & Berjalan Sesuai Jadwal
                                    </span>
                                    @if($r['verified_session'] && $r['verified_session']->diverifikasi_pada)
                                        <span class="font-mono text-[9px] text-emerald-700 font-semibold">
                                            {{ \Carbon\Carbon::parse($r['verified_session']->diverifikasi_pada)->format('H:i') }} WIB
                                        </span>
                                    @endif
                                </div>
                            @elseif($isUpcoming)
                                <div class="pt-1 text-[10px] text-amber-700 font-semibold flex items-center gap-1">
                                    <span>⏱️ Kelas berikutnya hari ini</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="p-3 rounded-xl bg-white/50 border border-dashed border-slate-200 text-center text-xs text-slate-400 mb-3">
                            Tidak ada kegiatan perkuliahan saat ini
                        </div>
                    @endif

                    <div class="flex items-center justify-between pt-1 text-xs">
                        <span class="text-[11px] text-slate-400 font-mono truncate max-w-[140px]">{{ $r['link_stream_cctv'] }}</span>
                        <a href="{{ route('cctv.index', ['ruangan_id' => $r['id']]) }}" class="font-bold text-blue-600 hover:text-blue-800 inline-flex items-center gap-1">
                            <span>Pantau CCTV</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 px-4 rounded-2xl border border-dashed border-slate-200 bg-slate-50/50">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 14 14"></polyline>
                            </svg>
                        </div>
                        <p class="font-bold text-slate-700 text-sm">Tidak ada kelas yang sedang berlangsung saat ini{{ $selectedLantai ? ' di ' . $selectedLantai : '' }}.</p>
                        <p class="text-xs text-slate-400">Ruangan tanpa jadwal perkuliahan aktif saat ini tidak ditampilkan.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- JADWAL PERKULIAHAN AKTIF TABLE -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Daftar Jadwal Kuliah yang Sedang Berlangsung</h3>
                <p class="text-xs text-slate-400">Menampilkan mata kuliah yang sedang berjalan secara real-time pada jam sekarang.</p>
            </div>
            <a href="{{ route('jadwal.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                <span>Kelola Semua Jadwal</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 12h14m-7-7l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200/80 text-slate-400 font-bold text-[11px] uppercase tracking-wider">
                        <th class="pb-3 px-3">Waktu & Jam Ke</th>
                        <th class="pb-3 px-4">Mata Kuliah</th>
                        <th class="pb-3 px-4">Dosen Pengampu</th>
                        <th class="pb-3 px-3">Ruangan</th>
                        <th class="pb-3 px-3">Prodi & Kelas</th>
                        <th class="pb-3 px-3 text-center">Status Verifikasi</th>
                        <th class="pb-3 px-3 text-center">CCTV</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($displayJadwal as $jadwal)
                        @php
                            $sesiHariIni = $jadwal->sesiKelas->first();
                            $isJadwalVerified = $sesiHariIni && $sesiHariIni->status_verifikasi === 'terverifikasi';
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $isJadwalVerified ? 'bg-emerald-50/30' : '' }}" data-jam-selesai="{{ $jadwal->jam_selesai }}">
                            <td class="py-3.5 px-3">
                                <div class="font-mono font-bold text-slate-800 text-xs">{{ $jadwal->jam_range }}</div>
                                <div class="text-[10px] text-blue-600 font-medium">{{ $jadwal->jam_ke_range }} ({{ $jadwal->hari }})</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $jadwal->mataKuliah->nama_mata_kuliah ?? '-' }}</div>
                                <div class="text-[11px] font-mono text-slate-400">{{ $jadwal->mataKuliah->kode_mata_kuliah ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-800">{{ $jadwal->dosen->nama ?? '-' }}</div>
                                <div class="text-[11px] font-mono text-slate-400">NIP: {{ $jadwal->dosen->nip ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200/60">
                                    {{ $jadwal->ruangan->kode_ruangan ?? ('R' . str_pad($jadwal->ruangan_id, 2, '0', STR_PAD_LEFT)) }}
                                </span>
                                <div class="text-[10px] text-slate-400 mt-0.5 truncate max-w-[120px]">{{ $jadwal->ruangan->nama ?? '' }}</div>
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $jadwal->prodi }} - {{ $jadwal->kelas }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                @if($isJadwalVerified)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Terverifikasi</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span>Belum Verifikasi</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <a href="{{ route('cctv.index', ['ruangan_id' => $jadwal->ruangan_id]) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs transition-colors">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                    </svg>
                                    <span>Pantau</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-xs">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 14 14"></polyline>
                                    </svg>
                                    <p class="font-bold text-slate-600">Tidak ada perkuliahan aktif atau jadwal tersisa untuk hari ini.</p>
                                    <p class="text-[11px] text-slate-400">Jadwal yang jamnya telah selesai otomatis tidak ditampilkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Auto-check every 30 seconds for live status & automatic dismissal of past classes
    setInterval(function() {
        const now = new Date();
        const curTimeStr = String(now.getHours()).padStart(2, '0') + ':' +
                           String(now.getMinutes()).padStart(2, '0') + ':' +
                           String(now.getSeconds()).padStart(2, '0');

        let hasExpiredClass = false;
        document.querySelectorAll('[data-jam-selesai]').forEach(function(el) {
            const jamSelesai = el.getAttribute('data-jam-selesai');
            if (jamSelesai && jamSelesai <= curTimeStr) {
                hasExpiredClass = true;
            }
        });

        if (hasExpiredClass) {
            window.location.reload();
        }
    }, 30000);
</script>
@endpush
@endsection
