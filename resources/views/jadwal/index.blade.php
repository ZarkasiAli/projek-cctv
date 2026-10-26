@extends('layouts.app')

@section('title', 'Jadwal Kuliah')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Jadwal Perkuliahan</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola alokasi ruangan, dosen pengampu, mata kuliah, jam ke, dan kelas.</p>
        </div>

        <button onclick="toggleFormCard()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs sm:text-sm shadow-md shadow-blue-500/20 transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span id="btnToggleText">Tambah Jadwal Baru</span>
        </button>
    </div>

    <!-- FORM INPUT JADWAL CARD (Collapsible) -->
    <div id="formJadwalCard" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs transition-all">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Form Input Jadwal Kuliah</h3>
                <p class="text-xs text-slate-400">Pastikan ruangan dan dosen tidak bentrok dengan jadwal lain</p>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                Jadwal Baru
            </span>
        </div>

        <form action="{{ route('jadwal.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                <!-- Mata Kuliah (Foreign Key -> master_mata_kuliah) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Mata Kuliah *</label>
                    <div class="relative">
                        <select name="mata_kuliah_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 appearance-none">
                            <option value="" disabled selected>Pilih Mata Kuliah</option>
                            @foreach($mataKuliahList as $mk)
                                <option value="{{ $mk->id }}" {{ old('mata_kuliah_id') == $mk->id ? 'selected' : '' }}>
                                    [{{ $mk->kode_mata_kuliah }}] {{ $mk->nama_mata_kuliah }}
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

                <!-- Dosen (Foreign Key -> master_dosen) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Dosen Pengampu *</label>
                    <div class="relative">
                        <select name="dosen_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 appearance-none">
                            <option value="" disabled selected>Pilih Dosen</option>
                            @foreach($dosenList as $ds)
                                <option value="{{ $ds->id }}" {{ old('dosen_id') == $ds->id ? 'selected' : '' }}>
                                    {{ $ds->nama }} (NIP: {{ $ds->nip }})
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

                <!-- Ruangan (Foreign Key -> ruangan) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Ruangan Kelas *</label>
                    <div class="relative">
                        <select name="ruangan_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 appearance-none">
                            <option value="" disabled selected>Pilih Ruangan</option>
                            @foreach($ruangan as $r)
                                <option value="{{ $r->id }}" {{ old('ruangan_id') == $r->id ? 'selected' : '' }}>
                                    {{ $r->kode_ruangan ?? ('R' . str_pad($r->id, 2, '0', STR_PAD_LEFT)) }} - {{ $r->nama }} ({{ $r->lokasi }})
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
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Hari -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Hari *</label>
                    <div class="relative">
                        <select name="hari" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 appearance-none">
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hariOption)
                                <option value="{{ $hariOption }}" {{ old('hari', 'Senin') == $hariOption ? 'selected' : '' }}>{{ $hariOption }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Jam Ke Mulai -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Jam Ke Mulai *</label>
                    <input 
                        type="number" 
                        name="jam_ke_mulai" 
                        min="1" 
                        max="12" 
                        value="{{ old('jam_ke_mulai', 1) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    >
                </div>

                <!-- Jam Ke Selesai -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Jam Ke Selesai *</label>
                    <input 
                        type="number" 
                        name="jam_ke_selesai" 
                        min="1" 
                        max="12" 
                        value="{{ old('jam_ke_selesai', 2) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    >
                </div>

                <!-- Jam Waktu Mulai -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Jam Mulai *</label>
                    <input 
                        type="time" 
                        name="jam_mulai" 
                        value="{{ old('jam_mulai', '08:00') }}" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    >
                </div>

                <!-- Jam Waktu Selesai -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Jam Selesai *</label>
                    <input 
                        type="time" 
                        name="jam_selesai" 
                        value="{{ old('jam_selesai', '10:00') }}" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Prodi -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Program Studi (Prodi) *</label>
                    <input 
                        type="text" 
                        name="prodi" 
                        value="{{ old('prodi', 'RPL') }}" 
                        required 
                        placeholder="Contoh: RPL, TKJ, D4 Teknik Informatika" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    >
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Kelas / Rombel *</label>
                    <input 
                        type="text" 
                        name="kelas" 
                        value="{{ old('kelas', '11 RPL 1') }}" 
                        required 
                        placeholder="Contoh: 11 RPL 1, 10 TKJ 2, TI-3A" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    >
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs sm:text-sm shadow-md shadow-blue-500/20 transition-all hover:-translate-y-0.5">
                    Simpan Jadwal Perkuliahan
                </button>
            </div>
        </form>
    </div>

    <!-- TABLE DAFTAR JADWAL -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
        
        <!-- Filter Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-1">
            <form action="{{ route('jadwal.index') }}" method="GET" class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-2.5">
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8" stroke-width="2"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"></line>
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari mata kuliah, dosen, kelas..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    >
                </div>

                <select name="hari" onchange="this.form.submit()" class="w-full sm:w-36 px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="all" {{ request('hari') == 'all' ? 'selected' : '' }}>Semua Hari</option>
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                        <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                </select>

                <select name="ruangan_id" onchange="this.form.submit()" class="w-full sm:w-44 px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="all" {{ request('ruangan_id') == 'all' ? 'selected' : '' }}>Semua Ruangan</option>
                    @foreach($ruangan as $r)
                        <option value="{{ $r->id }}" {{ request('ruangan_id') == $r->id ? 'selected' : '' }}>
                            {{ $r->kode_ruangan ?? ('R' . str_pad($r->id, 2, '0', STR_PAD_LEFT)) }} - {{ $r->nama }}
                        </option>
                    @endforeach
                </select>
            </form>

            <div class="text-xs text-slate-500 font-medium">
                Total: <span class="font-bold text-slate-800">{{ $totalJadwal }}</span> Jadwal Aktif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200/80 text-slate-400 font-bold text-[11px] uppercase tracking-wider">
                        <th class="pb-3.5 px-3 w-10 text-center">No</th>
                        <th class="pb-3.5 px-3">Hari & Jam Ke</th>
                        <th class="pb-3.5 px-3">Waktu</th>
                        <th class="pb-3.5 px-4">Mata Kuliah</th>
                        <th class="pb-3.5 px-4">Dosen Pengampu</th>
                        <th class="pb-3.5 px-3">Ruangan</th>
                        <th class="pb-3.5 px-3">Prodi & Kelas</th>
                        <th class="pb-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($jadwalList as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-3 text-center text-slate-400 font-medium text-xs">
                                {{ $jadwalList->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-3">
                                <span class="font-bold text-slate-800">{{ $item->hari }}</span>
                                <div class="text-[11px] text-blue-600 font-medium">{{ $item->jam_ke_range }}</div>
                            </td>
                            <td class="py-4 px-3 font-mono text-xs font-semibold text-slate-700">
                                {{ $item->jam_range }}
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900">{{ $item->mataKuliah->nama_mata_kuliah ?? '-' }}</div>
                                <div class="text-[11px] font-mono text-slate-400">{{ $item->mataKuliah->kode_mata_kuliah ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-semibold text-slate-800">{{ $item->dosen->nama ?? '-' }}</div>
                                <div class="text-[11px] font-mono text-slate-400">NIP: {{ $item->dosen->nip ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-3">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200/60">
                                    {{ $item->ruangan->kode_ruangan ?? ('R' . str_pad($item->ruangan_id, 2, '0', STR_PAD_LEFT)) }}
                                </span>
                                <div class="text-[10px] text-slate-400 mt-0.5 truncate max-w-[120px]">{{ $item->ruangan->nama ?? '' }}</div>
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $item->prodi }} - {{ $item->kelas }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <button 
                                        type="button" 
                                        onclick="openEditJadwalModal({{ json_encode($item) }})" 
                                        class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                        title="Edit Jadwal"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>

                                    <form action="{{ route('jadwal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini? Sesi kelas terkait juga akan dihapus.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Jadwal">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-slate-400 text-xs">
                                Tidak ada jadwal kuliah yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-slate-100">
            {{ $jadwalList->links() }}
        </div>
    </div>

</div>

<!-- MODAL EDIT JADWAL -->
<div id="editJadwalModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-2xl w-full mx-4 shadow-2xl border border-slate-100 transform transition-all max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-5">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Edit Jadwal Perkuliahan</h3>
                <p class="text-xs text-slate-400">Sesuaikan data ruangan, dosen, mata kuliah, dan waktu</p>
            </div>
            <button onclick="closeEditJadwalModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold">&times;</button>
        </div>

        <form id="editJadwalForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Mata Kuliah</label>
                    <select id="edit_mata_kuliah_id" name="mata_kuliah_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800">
                        @foreach($mataKuliahList as $mk)
                            <option value="{{ $mk->id }}">[{{ $mk->kode_mata_kuliah }}] {{ $mk->nama_mata_kuliah }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Dosen</label>
                    <select id="edit_dosen_id" name="dosen_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800">
                        @foreach($dosenList as $ds)
                            <option value="{{ $ds->id }}">{{ $ds->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Ruangan</label>
                    <select id="edit_ruangan_id" name="ruangan_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800">
                        @foreach($ruangan as $r)
                            <option value="{{ $r->id }}">{{ $r->kode_ruangan ?? ('R' . str_pad($r->id, 2, '0', STR_PAD_LEFT)) }} - {{ $r->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Hari</label>
                    <select id="edit_hari" name="hari" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hariOpt)
                            <option value="{{ $hariOpt }}">{{ $hariOpt }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Jam Ke Mulai</label>
                    <input type="number" id="edit_jam_ke_mulai" name="jam_ke_mulai" min="1" max="12" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Jam Ke Selesai</label>
                    <input type="number" id="edit_jam_ke_selesai" name="jam_ke_selesai" min="1" max="12" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Jam Mulai</label>
                    <input type="time" id="edit_jam_mulai" name="jam_mulai" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Jam Selesai</label>
                    <input type="time" id="edit_jam_selesai" name="jam_selesai" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Program Studi</label>
                    <input type="text" id="edit_prodi" name="prodi" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Kelas</label>
                    <input type="text" id="edit_kelas" name="kelas" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <button type="button" onclick="closeEditJadwalModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-xs hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleFormCard() {
        const card = document.getElementById('formJadwalCard');
        const btnText = document.getElementById('btnToggleText');
        if (card.classList.contains('hidden')) {
            card.classList.remove('hidden');
            btnText.textContent = 'Tutup Form';
        } else {
            card.classList.add('hidden');
            btnText.textContent = 'Tambah Jadwal Baru';
        }
    }

    function openEditJadwalModal(item) {
        const form = document.getElementById('editJadwalForm');
        form.action = `/jadwal/${item.id}`;
        document.getElementById('edit_mata_kuliah_id').value = item.mata_kuliah_id || '';
        document.getElementById('edit_dosen_id').value = item.dosen_id || '';
        document.getElementById('edit_ruangan_id').value = item.ruangan_id || '';
        document.getElementById('edit_hari').value = item.hari || 'Senin';
        document.getElementById('edit_jam_ke_mulai').value = item.jam_ke_mulai || 1;
        document.getElementById('edit_jam_ke_selesai').value = item.jam_ke_selesai || 2;
        document.getElementById('edit_jam_mulai').value = item.jam_mulai ? item.jam_mulai.substring(0, 5) : '08:00';
        document.getElementById('edit_jam_selesai').value = item.jam_selesai ? item.jam_selesai.substring(0, 5) : '10:00';
        document.getElementById('edit_prodi').value = item.prodi || '';
        document.getElementById('edit_kelas').value = item.kelas || '';
        document.getElementById('editJadwalModal').classList.remove('hidden');
    }

    function closeEditJadwalModal() {
        document.getElementById('editJadwalModal').classList.add('hidden');
    }
</script>
@endpush
