@extends('layouts.app')

@section('title', 'Laporan Rekapitulasi Monitoring')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 no-print">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan & Rekapitulasi Monitoring</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Audit pelaksanaan perkuliahan, kehadiran dosen, dan hasil verifikasi CCTV.</p>
        </div>

        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-xs sm:text-sm shadow-xs transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            <span>Cetak / Ekspor PDF</span>
        </button>
    </div>

    <!-- FILTER BAR (Hidden on Print) -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs no-print space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Filter Laporan</h3>
            <a href="{{ route('report.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">Reset Filter</a>
        </div>

        <form action="{{ route('report.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3.5 items-end">
            
            <!-- Tanggal Dari -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1 uppercase tracking-wider">Dari Tanggal</label>
                <input 
                    type="date" 
                    name="tanggal_dari" 
                    value="{{ $selectedTanggalDari }}" 
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <!-- Tanggal Sampai -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1 uppercase tracking-wider">Sampai Tanggal</label>
                <input 
                    type="date" 
                    name="tanggal_sampai" 
                    value="{{ $selectedTanggalSampai }}" 
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <!-- Ruangan -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1 uppercase tracking-wider">Ruangan</label>
                <select name="ruangan_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="all" {{ $selectedRuangan == 'all' ? 'selected' : '' }}>Semua Ruangan</option>
                    @foreach($ruanganList as $r)
                        <option value="{{ $r->id }}" {{ $selectedRuangan == $r->id ? 'selected' : '' }}>
                            {{ $r->kode_ruangan ?? ('R' . $r->id) }} - {{ $r->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Dosen -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1 uppercase tracking-wider">Dosen</label>
                <select name="dosen_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="all" {{ $selectedDosen == 'all' ? 'selected' : '' }}>Semua Dosen</option>
                    @foreach($dosenList as $ds)
                        <option value="{{ $ds->id }}" {{ $selectedDosen == $ds->id ? 'selected' : '' }}>
                            {{ $ds->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Mata Kuliah -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1 uppercase tracking-wider">Mata Kuliah</label>
                <select name="mata_kuliah_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="all" {{ $selectedMK == 'all' ? 'selected' : '' }}>Semua Mata Kuliah</option>
                    @foreach($mataKuliahList as $mk)
                        <option value="{{ $mk->id }}" {{ $selectedMK == $mk->id ? 'selected' : '' }}>
                            {{ $mk->kode_mata_kuliah }} - {{ $mk->nama_mata_kuliah }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Filter -->
            <div>
                <button 
                    type="submit" 
                    class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-1.5"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <span>Terapkan Filter</span>
                </button>
            </div>

        </form>
    </div>

    <!-- STAT SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Sesi</p>
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
                <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Terverifikasi</p>
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

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-purple-600 uppercase tracking-wider">Tingkat Verifikasi</p>
                <p class="text-xl font-extrabold text-purple-700">{{ $persentase }}%</p>
            </div>
        </div>

    </div>

    <!-- MAIN TABLE: LAPORAN REKAP -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-4">
        
        <!-- Print Letterhead Header -->
        <div class="hidden print:block pb-4 border-b-2 border-slate-900 mb-4 text-center">
            <h1 class="text-xl font-black text-slate-900 uppercase">SISTEM MONITORING KELAS & CCTV</h1>
            <p class="text-xs text-slate-600">Laporan Rekapitulasi Pelaksanaan Perkuliahan & Verifikasi Ruangan</p>
            <p class="text-[10px] text-slate-400 mt-1">Dicetak pada: {{ now()->translatedFormat('d F Y H:i:s') }}</p>
        </div>

        <div class="flex items-center justify-between pb-1 no-print">
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Data Riwayat & Verifikasi</h3>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                Total {{ $totalSesi }} Rekapitulasi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200/80 text-slate-400 font-bold text-[11px] uppercase tracking-wider">
                        <th class="pb-3.5 px-3 w-10 text-center">No</th>
                        <th class="pb-3.5 px-3">Tanggal</th>
                        <th class="pb-3.5 px-3">Jam Kuliah</th>
                        <th class="pb-3.5 px-4">Mata Kuliah</th>
                        <th class="pb-3.5 px-4">Dosen Pengampu</th>
                        <th class="pb-3.5 px-3">Ruangan</th>
                        <th class="pb-3.5 px-3">Kelas</th>
                        <th class="pb-3.5 px-3 text-center">Status</th>
                        <th class="pb-3.5 px-3">Petugas</th>
                        <th class="pb-3.5 px-3">Waktu Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($sesiList as $index => $item)
                        @php
                            $isVerified = $item->status_verifikasi === 'terverifikasi';
                            $verifTime = $item->diverifikasi_pada ? \Carbon\Carbon::parse($item->diverifikasi_pada)->format('d/m/Y H:i') : '-';
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-3 text-center text-slate-400 font-medium text-xs">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-4 px-3 font-mono text-slate-700 whitespace-nowrap text-xs font-semibold">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="py-4 px-3 font-mono text-xs text-slate-600 whitespace-nowrap">
                                {{ $item->jadwal->jam_range ?? '-' }}
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900">{{ $item->jadwal->mataKuliah->nama_mata_kuliah ?? '-' }}</div>
                                <div class="text-[10px] font-mono text-slate-400">{{ $item->jadwal->mataKuliah->kode_mata_kuliah ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-800">
                                {{ $item->jadwal->dosen->nama ?? '-' }}
                            </td>
                            <td class="py-4 px-3">
                                <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200/60">
                                    {{ $item->jadwal->ruangan->kode_ruangan ?? ('R' . ($item->jadwal->ruangan_id ?? 1)) }}
                                </span>
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $item->jadwal->prodi ?? '-' }} - {{ $item->jadwal->kelas ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-center">
                                @if($isVerified)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Belum
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-3 text-slate-700 font-medium text-xs">
                                {{ $isVerified ? ($item->verifikator->nama ?? 'Admin') : '-' }}
                            </td>
                            <td class="py-4 px-3 font-mono text-slate-500 text-xs">
                                {{ $verifTime }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 text-center text-slate-400 text-xs">
                                Tidak ada data hasil monitoring yang sesuai kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
