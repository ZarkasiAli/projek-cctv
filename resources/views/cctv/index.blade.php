@extends('layouts.app')

@section('title', 'Monitoring CCTV')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-red-50 text-red-600 border border-red-200/60 text-[11px] font-bold uppercase tracking-wider mb-1">
                <span class="w-2 h-2 rounded-full bg-red-600 animate-ping"></span>
                <span>Real-Time CCTV Stream</span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Monitoring Visual & Verifikasi Kelas</h2>
            <p class="text-xs sm:text-sm text-slate-500">Pantau kehadiran dosen dan mahasiswa di kelas via CCTV secara real-time.</p>
        </div>

        <!-- CAMERA SELECTOR PILLS & VIEW MODE SWITCHER -->
        <div class="flex flex-wrap items-center gap-2">
            <!-- Mode Switcher -->
            <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200/80 text-xs font-semibold">
                <a href="{{ route('cctv.index', ['ruangan_id' => $selectedRoom->id ?? 1, 'mode' => 'single']) }}" class="px-3 py-1.5 rounded-lg transition-all {{ $viewMode !== 'grid' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Fokus 1 Cam
                </a>
                <a href="{{ route('cctv.index', ['mode' => 'grid']) }}" class="px-3 py-1.5 rounded-lg transition-all {{ $viewMode === 'grid' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Grid Multi-Cam
                </a>
            </div>

            <!-- Room Pills -->
            <div class="flex items-center gap-1 overflow-x-auto max-w-full">
                @foreach($ruanganList as $r)
                    <a 
                        href="{{ route('cctv.index', ['ruangan_id' => $r->id, 'mode' => 'single']) }}" 
                        class="px-3 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ ($selectedRoom && $selectedRoom->id == $r->id && $viewMode !== 'grid') ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}"
                    >
                        {{ $r->kode_ruangan ?? ('R' . str_pad($r->id, 2, '0', STR_PAD_LEFT)) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @if($viewMode === 'grid')
        <!-- MULTI-CAMERA 4-GRID VIEW -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($roomFeeds as $feed)
                @php $r = $feed['ruangan']; $sch = $feed['jadwal']; @endphp
                <div class="bg-slate-900 rounded-3xl overflow-hidden shadow-lg border border-slate-800 flex flex-col group">
                    <!-- Stream box -->
                    <div class="relative aspect-video bg-black overflow-hidden flex items-center justify-center">
                        <img 
                            src="{{ asset('images/cctv_feed.jpg') }}" 
                            alt="CCTV {{ $r->nama }}" 
                            class="w-full h-full object-cover filter contrast-105 brightness-95 group-hover:scale-105 transition-transform duration-500"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/40 pointer-events-none"></div>

                        <!-- Scanline -->
                        <div class="absolute inset-x-0 h-1 bg-sky-400/20 shadow-xs shadow-sky-400/50 pointer-events-none animate-scan"></div>

                        <!-- Top Badges -->
                        <div class="absolute top-3 left-3 flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-red-600/90 text-white font-mono font-bold text-[10px] backdrop-blur-xs tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                <span>REC</span>
                            </span>
                            <span class="px-2 py-0.5 rounded-md bg-black/60 text-slate-300 font-mono text-[10px] backdrop-blur-xs">
                                CAM-{{ sprintf('%02d', $r->id) }}
                            </span>
                        </div>

                        <!-- Bottom Info -->
                        <div class="absolute bottom-3 inset-x-3 flex items-center justify-between text-white text-xs">
                            <span class="font-bold text-xs truncate drop-shadow-sm">{{ $r->kode_ruangan ?? ('R' . $r->id) }} {{ $r->nama }}</span>
                            <a href="{{ route('cctv.index', ['ruangan_id' => $r->id, 'mode' => 'single']) }}" class="px-2.5 py-1 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-bold text-[11px] transition-colors shadow-sm">
                                Perbesar
                            </a>
                        </div>
                    </div>

                    <!-- Room Mini Detail -->
                    <div class="p-4 bg-slate-900 text-slate-300 text-xs space-y-1.5">
                        @if($sch)
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-white truncate max-w-[180px]">{{ $sch->mataKuliah->nama_mata_kuliah ?? '-' }}</span>
                                <span class="font-mono text-[10px] text-blue-400 bg-blue-950 px-1.5 py-0.5 rounded border border-blue-800">{{ $sch->jam_range }}</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-400">
                                <span class="truncate">Dosen: {{ $sch->dosen->nama ?? '-' }}</span>
                                <span class="font-medium text-slate-300">{{ $sch->kelas }}</span>
                            </div>
                        @else
                            <div class="text-slate-500 text-[11px] italic">Tidak ada perkuliahan aktif</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- SINGLE CAMERA STREAM & ACTION SUITE -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- LEFT: MAIN CCTV PLAYER (8 of 12) -->
            <div class="lg:col-span-8 bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border border-slate-800 relative group aspect-video flex items-center justify-center">
                
                <div class="w-full h-full relative overflow-hidden bg-black flex items-center justify-center">
                    <img 
                        src="{{ asset('images/cctv_feed.jpg') }}" 
                        alt="Live CCTV Feed" 
                        class="w-full h-full object-cover filter contrast-[1.08] brightness-95"
                    >

                    <!-- Scanlines & Vignette -->
                    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/60 pointer-events-none"></div>
                    <div class="absolute inset-x-0 h-1 bg-cyan-400/25 shadow-sm shadow-cyan-400 pointer-events-none animate-scan"></div>

                    <!-- OVERLAY TOP LEFT: LIVE BADGE -->
                    <div class="absolute top-4 left-4 z-10 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-red-600/90 text-white font-mono font-bold text-xs shadow-lg backdrop-blur-md tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                            <span>🔴 LIVE</span>
                        </span>
                        <span class="px-2.5 py-1 rounded-lg bg-black/70 text-slate-300 font-mono text-xs backdrop-blur-md border border-white/10">
                            CAM-{{ sprintf('%02d', $selectedRoom->id ?? 1) }}
                        </span>
                        <span class="hidden sm:inline-flex px-2.5 py-1 rounded-lg bg-black/70 text-emerald-400 font-mono text-xs backdrop-blur-md border border-white/10">
                            1080P 30FPS
                        </span>
                    </div>

                    <!-- OVERLAY TOP RIGHT: CONTROLS -->
                    <div class="absolute top-4 right-4 z-10 flex items-center gap-2">
                        <button onclick="toggleCctvFullscreen()" class="p-2.5 rounded-xl bg-black/70 text-white hover:bg-black/90 backdrop-blur-md border border-white/10 transition-colors shadow-sm" title="Mode Layar Penuh">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- OVERLAY BOTTOM LEFT: ROOM NAME -->
                    <div class="absolute bottom-4 left-4 z-10">
                        <div class="px-3.5 py-2 rounded-xl bg-black/80 backdrop-blur-md text-white text-xs font-mono font-bold border border-white/15 flex items-center gap-2.5 shadow-lg">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>{{ $selectedRoom->kode_ruangan ?? ('R' . ($selectedRoom->id ?? 1)) }} {{ $selectedRoom->nama ?? 'Ruangan' }} ({{ $selectedRoom->lokasi ?? '' }})</span>
                        </div>
                    </div>

                    <!-- OVERLAY BOTTOM RIGHT: REAL-TIME TIMESTAMP -->
                    <div class="absolute bottom-4 right-4 z-10">
                        <div class="px-3.5 py-2 rounded-xl bg-black/80 backdrop-blur-md text-slate-200 text-xs font-mono font-semibold border border-white/15 shadow-lg" id="cctvOverlayClock">
                            {{ $formattedDate }} {{ date('H:i:s') }}
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT: INFORMASI KELAS (4 of 12) -->
            <div class="lg:col-span-4 bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Informasi Perkuliahan</h3>
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-blue-700">
                        {{ $selectedRoom->kode_ruangan ?? ('R' . ($selectedRoom->id ?? 1)) }}
                    </span>
                </div>

                @if($activeJadwal)
                    <div class="space-y-3.5 text-xs sm:text-sm">
                        <!-- Mata Kuliah -->
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mata Kuliah</span>
                                <span class="font-mono text-[10px] font-bold text-blue-600 bg-blue-100/60 px-1.5 py-0.5 rounded">
                                    {{ $activeJadwal->mataKuliah->kode_mata_kuliah ?? '-' }}
                                </span>
                            </div>
                            <p class="font-extrabold text-slate-900 text-sm leading-snug">{{ $activeJadwal->mataKuliah->nama_mata_kuliah ?? '-' }}</p>
                        </div>

                        <!-- Dosen -->
                        <div class="flex items-start justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Dosen Pengampu</span>
                            <div class="text-right">
                                <p class="font-bold text-slate-800">{{ $activeJadwal->dosen->nama ?? '-' }}</p>
                                <p class="text-[11px] font-mono text-slate-400">NIP: {{ $activeJadwal->dosen->nip ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Prodi & Kelas -->
                        <div class="flex items-center justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Prodi & Kelas</span>
                            <span class="font-bold text-slate-800">{{ $activeJadwal->prodi }} {{ $activeJadwal->kelas }}</span>
                        </div>

                        <!-- Jam Ke & Jam Waktu -->
                        <div class="flex items-center justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Jam Kuliah</span>
                            <div class="text-right">
                                <span class="font-mono font-bold text-slate-800 text-xs">{{ $activeJadwal->jam_range }}</span>
                                <div class="text-[11px] text-blue-600 font-medium">{{ $activeJadwal->jam_ke_range }} ({{ $activeJadwal->hari }})</div>
                            </div>
                        </div>

                        <!-- Status Verifikasi Sesi -->
                        <div class="flex items-center justify-between pt-1">
                            <span class="text-slate-400 font-medium">Status Verifikasi</span>
                            @if($sesi && $sesi->status_verifikasi === 'terverifikasi')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Terverifikasi</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    <span>Belum Diverifikasi</span>
                                </span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="py-12 text-center text-slate-400 text-xs">
                        <p class="font-bold">Tidak ada jadwal kuliah aktif di ruangan ini.</p>
                        <p class="mt-1 text-[11px]">Silakan pilih ruangan lain pada bar di atas.</p>
                    </div>
                @endif

                <div class="pt-3 border-t border-slate-100">
                    <div class="text-[11px] text-slate-400 flex items-center justify-between">
                        <span>Stream CCTV:</span>
                        <span class="font-mono text-slate-600 truncate max-w-[170px]">{{ $selectedRoom->link_stream_cctv ?? '-' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- BOTTOM CARD: FORM VERIFIKASI KELAS -->
        @if($activeJadwal)
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Verifikasi Kehadiran & Pelaksanaan Kuliah</h3>
                        <p class="text-xs text-slate-400">Tandai bahwa perkuliahan telah dipantau melalui CCTV dan berlangsung sesuai jadwal.</p>
                    </div>

                    @if($sesi && $sesi->status_verifikasi === 'terverifikasi')
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
                            <span>✓ Telah diverifikasi</span>
                        </div>
                    @endif
                </div>

                <!-- Form hanya muncul JIKA sesi belum ada atau belum terverifikasi -->
                @if(!$sesi || $sesi->status_verifikasi !== 'terverifikasi')
                    <form action="{{ route('cctv.verifikasi') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <input type="hidden" name="jadwal_id" value="{{ $activeJadwal->id }}">
                        @if($sesi)
                            <input type="hidden" name="sesi_id" value="{{ $sesi->id }}">
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Petugas Verifikator -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Petugas Verifikator</label>
                                <div class="relative">
                                    <select name="user_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 appearance-none">
                                        @foreach($allUsers as $u)
                                            <option value="{{ $u->id }}" {{ (auth()->id() == $u->id) ? 'selected' : '' }}>
                                                {{ $u->nama }} ({{ $u->role }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Catatan Pemantauan -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Catatan Pemantauan Visual</label>
                                <input 
                                    type="text" 
                                    name="catatan" 
                                    value="Kelas berjalan tertib dan sesuai jadwal perkuliahan" 
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    placeholder="Masukkan catatan observasi CCTV..."
                                >
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-2">
                            <button 
                                type="submit" 
                                class="w-full py-3.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl text-xs sm:text-sm shadow-lg shadow-blue-500/25 transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5"
                            >
                                <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Verifikasi Kelas Berjalan Sekarang</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        @endif
    @endif

</div>

<!-- SUCCESS MODAL: VERIFIKASI BERHASIL -->
@if(session('verified_success'))
    <div id="verifiedSuccessModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center z-50 animate-fade-in">
        <div class="bg-white rounded-3xl p-7 sm:p-8 max-w-sm w-full mx-4 shadow-2xl border border-slate-100 text-center transform transition-all">
            
            <h3 class="text-lg font-extrabold text-slate-900 mb-4">{{ session('verified_success')['title'] }}</h3>

            <!-- Green Circle Checkmark Icon -->
            <div class="w-16 h-16 rounded-2xl bg-emerald-500 text-white flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-500/35 ring-4 ring-emerald-100">
                <svg class="w-9 h-9" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            <p class="text-xs text-slate-600 mb-2 leading-relaxed font-medium">
                {{ session('verified_success')['message'] }}
            </p>
            <p class="text-[11px] font-mono text-slate-400 mb-6">Waktu: {{ session('verified_success')['waktu'] }}</p>

            <button 
                type="button" 
                onclick="document.getElementById('verifiedSuccessModal').remove()" 
                class="w-full py-2.5 px-6 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all"
            >
                OK, Mengerti
            </button>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
    function toggleCctvFullscreen() {
        const streamContainer = document.querySelector('.aspect-video');
        if (!document.fullscreenElement) {
            if (streamContainer.requestFullscreen) {
                streamContainer.requestFullscreen();
            } else if (streamContainer.webkitRequestFullscreen) {
                streamContainer.webkitRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }
</script>
@endpush