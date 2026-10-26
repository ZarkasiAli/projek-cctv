@extends('layouts.app')

@section('title', 'Manajemen Ruangan')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Ruangan Kelas</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola data ruangan, kode ruangan, lokasi, dan URL RTSP / HTTP CCTV.</p>
        </div>

        <button onclick="openAddRoomModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs sm:text-sm shadow-md shadow-blue-500/20 transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah Ruangan</span>
        </button>
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
        
        <!-- Search and count bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-1">
            <form action="{{ route('ruangan.index') }}" method="GET" class="w-full sm:w-80">
                <div class="relative">
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
                        placeholder="Cari nama, kode (R101), atau lokasi..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                    >
                </div>
            </form>

            <div class="text-xs text-slate-500 font-medium">
                Total: <span class="font-bold text-slate-800">{{ $totalRuangan }}</span> Ruangan Terdaftar
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200/80 text-slate-400 font-bold text-[11px] uppercase tracking-wider">
                        <th class="pb-3.5 px-3 w-12 text-center">No</th>
                        <th class="pb-3.5 px-4">Kode</th>
                        <th class="pb-3.5 px-4">Nama Ruangan</th>
                        <th class="pb-3.5 px-4">Lokasi Gedung / Lantai</th>
                        <th class="pb-3.5 px-4">Link Stream CCTV</th>
                        <th class="pb-3.5 px-3 text-center">Jadwal Terdaftar</th>
                        <th class="pb-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($ruangan as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-3 text-center text-slate-400 font-medium text-xs">
                                {{ $ruangan->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-blue-50 text-blue-700 border border-blue-200/60">
                                    {{ $item->kode_ruangan ?? ('R' . str_pad($item->id, 2, '0', STR_PAD_LEFT)) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-800">
                                {{ $item->nama }}
                            </td>
                            <td class="py-4 px-4 text-slate-600 font-medium">
                                {{ $item->lokasi }}
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2 max-w-[220px]">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                    <a href="{{ route('cctv.index', ['ruangan_id' => $item->id]) }}" class="text-blue-600 hover:text-blue-800 hover:underline font-mono text-xs truncate" title="{{ $item->link_stream_cctv }}">
                                        {{ $item->link_stream_cctv }}
                                    </a>
                                </div>
                            </td>
                            <td class="py-4 px-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $item->jadwal_count }} Jadwal
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a 
                                        href="{{ route('cctv.index', ['ruangan_id' => $item->id]) }}" 
                                        class="p-2 rounded-xl text-blue-600 hover:bg-blue-50 transition-colors"
                                        title="Pantau Live CCTV"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                        </svg>
                                    </a>

                                    <button 
                                        type="button" 
                                        onclick="openEditRoomModal({{ json_encode($item) }})" 
                                        class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                        title="Edit Data Ruangan"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>

                                    <form action="{{ route('ruangan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Ruangan">
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
                            <td colspan="7" class="py-10 text-center text-slate-400 text-xs">
                                Tidak ada data ruangan yang sesuai pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-slate-100">
            {{ $ruangan->links() }}
        </div>
    </div>

</div>

<!-- MODAL TAMBAH RUANGAN -->
<div id="addRoomModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-md w-full mx-4 shadow-2xl border border-slate-100 transform transition-all">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-5">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Tambah Ruangan Baru</h3>
                <p class="text-xs text-slate-400">Input informasi ruangan dan URL stream CCTV</p>
            </div>
            <button onclick="closeAddRoomModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold">&times;</button>
        </div>

        <form action="{{ route('ruangan.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Ruangan</label>
                <input 
                    type="text" 
                    name="nama" 
                    required 
                    placeholder="Contoh: Lab RPL 1, Ruang 301" 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                    Kode Ruangan <span class="text-[10px] text-slate-400 font-normal lowercase">(maks. 4 karakter unik)</span>
                </label>
                <input 
                    type="text" 
                    name="kode_ruangan" 
                    maxlength="4"
                    placeholder="Contoh: R101, L01" 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono font-bold uppercase text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Lokasi / Gedung</label>
                <input 
                    type="text" 
                    name="lokasi" 
                    required 
                    placeholder="Contoh: Gedung A Lantai 1" 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Link Stream CCTV (RTSP / HTTP)</label>
                <input 
                    type="text" 
                    name="link_stream_cctv" 
                    required
                    placeholder="Contoh: http://localhost/cctv/lab-rpl-1" 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <button type="button" onclick="closeAddRoomModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-xs hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all">
                    Simpan Ruangan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT RUANGAN -->
<div id="editRoomModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-md w-full mx-4 shadow-2xl border border-slate-100 transform transition-all">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-5">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Edit Data Ruangan</h3>
                <p class="text-xs text-slate-400">Perbarui informasi ruangan dan URL stream CCTV</p>
            </div>
            <button onclick="closeEditRoomModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold">&times;</button>
        </div>

        <form id="editRoomForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Ruangan</label>
                <input 
                    type="text" 
                    id="edit_nama" 
                    name="nama" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                    Kode Ruangan <span class="text-[10px] text-slate-400 font-normal lowercase">(maks. 4 karakter unik)</span>
                </label>
                <input 
                    type="text" 
                    id="edit_kode_ruangan" 
                    name="kode_ruangan" 
                    maxlength="4"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono font-bold uppercase text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Lokasi / Gedung</label>
                <input 
                    type="text" 
                    id="edit_lokasi" 
                    name="lokasi" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Link Stream CCTV</label>
                <input 
                    type="text" 
                    id="edit_link_stream_cctv" 
                    name="link_stream_cctv" 
                    required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <button type="button" onclick="closeEditRoomModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-xs hover:bg-slate-50 transition-colors">
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
    function openAddRoomModal() {
        document.getElementById('addRoomModal').classList.remove('hidden');
    }
    function closeAddRoomModal() {
        document.getElementById('addRoomModal').classList.add('hidden');
    }

    function openEditRoomModal(item) {
        const form = document.getElementById('editRoomForm');
        form.action = `/ruangan/${item.id}`;
        document.getElementById('edit_nama').value = item.nama || '';
        document.getElementById('edit_kode_ruangan').value = item.kode_ruangan || '';
        document.getElementById('edit_lokasi').value = item.lokasi || '';
        document.getElementById('edit_link_stream_cctv').value = item.link_stream_cctv || '';
        document.getElementById('editRoomModal').classList.remove('hidden');
    }
    function closeEditRoomModal() {
        document.getElementById('editRoomModal').classList.add('hidden');
    }
</script>
@endpush
