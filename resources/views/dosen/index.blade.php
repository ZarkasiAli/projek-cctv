@extends('layouts.app')

@section('title', 'Master Dosen')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Master Data Dosen</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola data dosen pengampu, NIP, nomor kontak WhatsApp, dan status aktif.</p>
        </div>

        <button onclick="openAddDosenModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs sm:text-sm shadow-md shadow-blue-500/20 transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah Dosen</span>
        </button>
    </div>

    <!-- STAT SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Dosen</p>
                <p class="text-xl font-extrabold text-slate-800">{{ $totalDosen }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Dosen Aktif</p>
                <p class="text-xl font-extrabold text-emerald-700">{{ $totalAktif }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Non-Aktif</p>
                <p class="text-xl font-extrabold text-slate-600">{{ $totalNonAktif }}</p>
            </div>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
        
        <!-- Filter bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-1">
            <form action="{{ route('dosen.index') }}" method="GET" class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-2.5">
                <div class="relative w-full sm:w-72">
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
                        placeholder="Cari nama dosen, NIP, no hp..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    >
                </div>

                <select name="status" onchange="this.form.submit()" class="w-full sm:w-36 px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif Saja</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </form>

            <div class="text-xs text-slate-500 font-medium">
                Menampilkan <span class="font-bold text-slate-800">{{ $dosenList->total() }}</span> dosen
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200/80 text-slate-400 font-bold text-[11px] uppercase tracking-wider">
                        <th class="pb-3.5 px-3 w-12 text-center">No</th>
                        <th class="pb-3.5 px-4">Nama Dosen</th>
                        <th class="pb-3.5 px-4">NIP</th>
                        <th class="pb-3.5 px-4">No. Handphone / WhatsApp</th>
                        <th class="pb-3.5 px-4 text-center">Status</th>
                        <th class="pb-3.5 px-3 text-center">Jadwal Mengajar</th>
                        <th class="pb-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($dosenList as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-3 text-center text-slate-400 font-medium text-xs">
                                {{ $dosenList->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($item->nama, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-slate-800">{{ $item->nama }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 font-mono text-slate-600 font-medium text-xs">
                                {{ $item->nip }}
                            </td>
                            <td class="py-4 px-4">
                                @if($item->no_hp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->no_hp) }}" target="_blank" class="inline-flex items-center gap-1.5 font-mono text-xs text-emerald-600 hover:text-emerald-700 hover:underline">
                                        <span>📱</span>
                                        <span>{{ $item->no_hp }}</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                <form action="{{ route('dosen.toggle', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    @if($item->status_aktif)
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/70 hover:bg-emerald-100 transition-colors" title="Klik untuk nonaktifkan">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Aktif</span>
                                        </button>
                                    @else
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200 transition-colors" title="Klik untuk aktifkan">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            <span>Non-Aktif</span>
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="py-4 px-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $item->jadwal_count }} Kelas
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <button 
                                        type="button" 
                                        onclick="openEditDosenModal({{ json_encode($item) }})" 
                                        class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                        title="Edit Data Dosen"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>

                                    <form action="{{ route('dosen.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dosen ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Dosen">
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
                                Tidak ada data dosen yang sesuai pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-slate-100">
            {{ $dosenList->links() }}
        </div>
    </div>

</div>

<!-- MODAL TAMBAH DOSEN -->
<div id="addDosenModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-md w-full mx-4 shadow-2xl border border-slate-100 transform transition-all">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-5">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Tambah Dosen Baru</h3>
                <p class="text-xs text-slate-400">Input NIP, nama lengkap, dan nomor kontak dosen</p>
            </div>
            <button onclick="closeAddDosenModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold">&times;</button>
        </div>

        <form action="{{ route('dosen.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Lengkap Dosen</label>
                <input 
                    type="text" 
                    name="nama" 
                    required 
                    placeholder="Contoh: Budi Santoso, M.Kom" 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">NIP (Nomor Induk Pegawai)</label>
                <input 
                    type="text" 
                    name="nip" 
                    required 
                    placeholder="Contoh: 198001012010011001" 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">No. Handphone / WhatsApp</label>
                <input 
                    type="text" 
                    name="no_hp" 
                    placeholder="Contoh: 081234567890" 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="add_status_aktif" name="status_aktif" value="1" checked class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                <label for="add_status_aktif" class="text-xs font-bold text-slate-700">Status Dosen Aktif Mengajar</label>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <button type="button" onclick="closeAddDosenModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-xs hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all">
                    Simpan Dosen
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT DOSEN -->
<div id="editDosenModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-md w-full mx-4 shadow-2xl border border-slate-100 transform transition-all">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-5">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Edit Data Dosen</h3>
                <p class="text-xs text-slate-400">Perbarui profil dosen pengampu</p>
            </div>
            <button onclick="closeEditDosenModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold">&times;</button>
        </div>

        <form id="editDosenForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Lengkap Dosen</label>
                <input 
                    type="text" 
                    id="edit_nama" 
                    name="nama" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">NIP</label>
                <input 
                    type="text" 
                    id="edit_nip" 
                    name="nip" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">No. Handphone / WhatsApp</label>
                <input 
                    type="text" 
                    id="edit_no_hp" 
                    name="no_hp" 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="edit_status_aktif" name="status_aktif" value="1" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                <label for="edit_status_aktif" class="text-xs font-bold text-slate-700">Status Dosen Aktif Mengajar</label>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <button type="button" onclick="closeEditDosenModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-xs hover:bg-slate-50 transition-colors">
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
    function openAddDosenModal() {
        document.getElementById('addDosenModal').classList.remove('hidden');
    }
    function closeAddDosenModal() {
        document.getElementById('addDosenModal').classList.add('hidden');
    }

    function openEditDosenModal(item) {
        const form = document.getElementById('editDosenForm');
        form.action = `/dosen/${item.id}`;
        document.getElementById('edit_nama').value = item.nama || '';
        document.getElementById('edit_nip').value = item.nip || '';
        document.getElementById('edit_no_hp').value = item.no_hp || '';
        document.getElementById('edit_status_aktif').checked = Boolean(item.status_aktif);
        document.getElementById('editDosenModal').classList.remove('hidden');
    }
    function closeEditDosenModal() {
        document.getElementById('editDosenModal').classList.add('hidden');
    }
</script>
@endpush
