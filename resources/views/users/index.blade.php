@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-violet-50 text-violet-700 border border-violet-200 text-[11px] font-bold uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-violet-600 animate-pulse"></span>
                <span>Super Admin</span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kelola User</h2>
            <p class="text-xs sm:text-sm text-slate-500">Manajemen akun sistem, role, dan akses pengguna.</p>
        </div>

        <button
            type="button"
            onclick="document.getElementById('createUserModal').classList.remove('hidden')"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white font-bold text-xs shadow-lg shadow-violet-500/20 transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah User</span>
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">User</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Email</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Role</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-violet-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">{{ $user->nama }}</p>
                                        <p class="text-[11px] text-slate-500">ID: {{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $role = strtolower($user->role);
                                @endphp
                                @if($role === 'superadmin')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-violet-100 text-violet-800 border border-violet-300 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                        </svg>
                                        <span>Super Admin</span>
                                    </span>
                                @elseif($role === 'admin')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="3"></circle>
                                            <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"></path>
                                        </svg>
                                        <span>Admin</span>
                                    </span>
                                @elseif($role === 'manajemen')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 00-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 010 7.75"></path>
                                        </svg>
                                        <span>Manajemen</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $user->role }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        onclick="fillEditForm(@json($user->toArray()))"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[11px] font-bold"
                                    >
                                        Edit
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user {{ addslashes($user->nama) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 rounded-lg border border-red-200 bg-red-50 text-red-700 hover:bg-red-100 text-[11px] font-bold">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="createUserModal" class="fixed inset-0 z-50 hidden bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-xl shadow-2xl border border-slate-200/80">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Tambah User</h3>
                <p class="text-[11px] text-slate-500">Buat akun baru dan atur role akses.</p>
            </div>
            <button type="button" onclick="document.getElementById('createUserModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 text-xl">&times;</button>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-violet-200 focus:border-violet-500" placeholder="Masukkan nama user">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">Email</label>
                    <input type="email" name="email" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-violet-200 focus:border-violet-500" placeholder="user@monitoring.com">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">Password</label>
                    <input type="password" name="password" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-violet-200 focus:border-violet-500" placeholder="Minimal 8 karakter">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-violet-200 focus:border-violet-500" placeholder="Ulangi password">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">Role</label>
                    <select name="role" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-violet-200 focus:border-violet-500">
                        <option value="admin">Admin</option>
                        <option value="manajemen">Manajemen</option>
                        <option value="superadmin">Super Admin</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('createUserModal').classList.add('hidden')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs">Batal</button>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white font-bold text-xs shadow-lg shadow-violet-500/20">Simpan User</button>
            </div>
        </form>
    </div>
</div>

<div id="editUserModal" class="fixed inset-0 z-50 hidden bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-xl shadow-2xl border border-slate-200/80">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Edit User</h3>
                <p class="text-[11px] text-slate-500">Perbarui data akun dan role.</p>
            </div>
            <button type="button" onclick="document.getElementById('editUserModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 text-xl">&times;</button>
        </div>

        <form id="editUserForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
                    <input id="edit_nama" type="text" name="nama" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-violet-200 focus:border-violet-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">Email</label>
                    <input id="edit_email" type="email" name="email" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-violet-200 focus:border-violet-500">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">Password Baru</label>
                    <input type="password" name="password" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-violet-200 focus:border-violet-500" placeholder="Kosongkan jika tidak diubah">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-violet-200 focus:border-violet-500" placeholder="Ulangi password">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">Role</label>
                    <select id="edit_role" name="role" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-violet-200 focus:border-violet-500">
                        <option value="admin">Admin</option>
                        <option value="manajemen">Manajemen</option>
                        <option value="superadmin">Super Admin</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('editUserModal').classList.add('hidden')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs">Batal</button>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white font-bold text-xs shadow-lg shadow-violet-500/20">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    function fillEditForm(user) {
        const modal = document.getElementById('editUserModal');
        const form = document.getElementById('editUserForm');

        document.getElementById('edit_nama').value = user.nama || '';
        document.getElementById('edit_email').value = user.email || '';
        document.getElementById('edit_role').value = user.role || 'admin';

        form.action = '{{ url("/users") }}/' + user.id;
        modal.classList.remove('hidden');
    }
</script>
@endsection
