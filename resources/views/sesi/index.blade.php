@extends('layouts.app')

@section('title', 'Generate Sesi Kelas')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Generate & Manajemen Sesi Kelas</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Generate instance sesi perkuliahan harian otomatis dari jadwal mingguan.</p>
        </div>
    </div>

    <!-- DATE PICKER & ACTION BAR -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-4">
        <form action="{{ route('sesi.generate') }}" method="POST" class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            @csrf

            <div class="flex flex-col sm:flex-row sm:items-end gap-3 flex-1 max-w-xl">
                <!-- Date input -->
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Pilih Tanggal Sesi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </span>
                        <input 
                            type="date" 
                            name="tanggal" 
                            id="sesiTanggalInput"
                            value="{{ $selectedDate }}" 
                            required 
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                            onchange="window.location.href='{{ route('sesi.index') }}?tanggal=' + this.value"
                        >
                    </div>
                </div>

                <!-- Quick Date Presets -->
                <div class="flex items-center gap-1.5 pb-0.5">
                    <a href="{{ route('sesi.index', ['tanggal' => $yesterdayDate]) }}" class="px-2.5 py-2 rounded-xl text-xs font-semibold {{ $selectedDate == $yesterdayDate ? 'bg-blue-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        Kemarin
                    </a>
                    <a href="{{ route('sesi.index', ['tanggal' => $todayDate]) }}" class="px-2.5 py-2 rounded-xl text-xs font-semibold {{ $selectedDate == $todayDate ? 'bg-blue-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('sesi.index', ['tanggal' => $tomorrowDate]) }}" class="px-2.5 py-2 rounded-xl text-xs font-semibold {{ $selectedDate == $tomorrowDate ? 'bg-blue-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        Besok
                    </a>
                </div>
            </div>

            <!-- Generate Button -->
            <button 
                type="submit" 
                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs sm:text-sm shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Generate Sesi Kelas Harian</span>
            </button>
        </form>
    </div>

    <!-- STAT SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Sesi Kelas</p>
                <p class="text-xl font-extrabold text-slate-800">{{ $totalSesi }} Sesi</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Terverifikasi Berjalan</p>
                <p class="text-xl font-extrabold text-emerald-700">{{ $totalTerverifikasi }} Sesi</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Belum Diverifikasi</p>
                <p class="text-xl font-extrabold text-amber-700">{{ $totalBelum }} Sesi</p>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE: SESI KELAS -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-1">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">
                    Daftar Sesi Perkuliahan: <span class="text-blue-600">{{ $formattedDate }}</span>
                </h3>
                <p class="text-xs text-slate-400">Jadwal hari {{ $dayOfWeek }} yang aktif untuk pemantauan CCTV.</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                Hari {{ $dayOfWeek }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200/80 text-slate-400 font-bold text-[11px] uppercase tracking-wider">
                        <th class="pb-3.5 px-3 w-10 text-center">No</th>
                        <th class="pb-3.5 px-3">Jam Ke & Waktu</th>
                        <th class="pb-3.5 px-4">Mata Kuliah</th>
                        <th class="pb-3.5 px-4">Dosen Pengampu</th>
                        <th class="pb-3.5 px-3">Ruangan</th>
                        <th class="pb-3.5 px-3">Kelas</th>
                        <th class="pb-3.5 px-3 text-center">Status</th>
                        <th class="pb-3.5 px-3">Verifikator</th>
                        <th class="pb-3.5 px-3 text-center">CCTV</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($sesiList as $index => $sesi)
                        @php
                            $jadwal = $sesi->jadwal;
                            $isVerified = $sesi->status_verifikasi === 'terverifikasi';
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-3 text-center text-slate-400 font-medium text-xs">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-4 px-3">
                                <div class="font-mono font-bold text-slate-800 text-xs">{{ $jadwal->jam_range ?? '-' }}</div>
                                <div class="text-[10px] text-blue-600 font-medium">{{ $jadwal->jam_ke_range ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900">{{ $jadwal->mataKuliah->nama_mata_kuliah ?? '-' }}</div>
                                <div class="text-[11px] font-mono text-slate-400">{{ $jadwal->mataKuliah->kode_mata_kuliah ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-semibold text-slate-800">{{ $jadwal->dosen->nama ?? '-' }}</div>
                                <div class="text-[11px] font-mono text-slate-400">NIP: {{ $jadwal->dosen->nip ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-3">
                                <span class="px-2 py-1 rounded-lg text-xs font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200/60">
                                    {{ $jadwal->ruangan->kode_ruangan ?? ('R' . ($jadwal->ruangan_id ?? 1)) }}
                                </span>
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $jadwal->prodi ?? '-' }} - {{ $jadwal->kelas ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-center">
                                @if($isVerified)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Terverifikasi</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>Belum</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-3 text-xs text-slate-600">
                                @if($isVerified)
                                    <span class="font-semibold text-slate-800">{{ $sesi->verifikator->nama ?? 'Admin' }}</span>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $sesi->diverifikasi_pada ? $sesi->diverifikasi_pada->format('H:i') : '' }}</div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-3 text-center">
                                <a href="{{ route('cctv.index', ['ruangan_id' => $jadwal->ruangan_id ?? 1]) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs transition-colors">
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
                            <td colspan="9" class="py-12 text-center text-slate-400 text-xs">
                                <p class="text-sm font-bold text-slate-500">Belum ada sesi kelas untuk tanggal {{ $formattedDate }}.</p>
                                <p class="mt-1">Klik tombol <span class="font-bold text-blue-600">"Generate Sesi Kelas Harian"</span> di atas untuk membuat sesi dari jadwal aktif.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
