<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private function ensureSuperAdmin(): void
    {
        if (! Auth::check()) {
            abort(redirect()->route('login')->withErrors([
                'auth_error' => 'Silakan login terlebih dahulu sebagai Super Admin untuk mengakses kelola user.',
            ]));
        }

        if (! Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Fitur Kelola User hanya dapat diakses oleh role Super Admin.');
        }
    }

    public function index()
    {
        $this->ensureSuperAdmin();

        $users = User::orderBy('nama')->get();

        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:superadmin,admin,manajemen',
        ]);

        User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'User baru berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => 'required|in:superadmin,admin,manajemen',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->fill([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->ensureSuperAdmin();

        if ($user->id === Auth::id()) {
            return back()->withErrors([
                'delete_error' => 'Anda tidak dapat menghapus akun Anda sendiri.',
            ]);
        }

        if ($user->isSuperAdmin() && User::where('role', 'superadmin')->count() <= 1) {
            return back()->withErrors([
                'delete_error' => 'Minimal harus ada satu akun Super Admin.',
            ]);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
