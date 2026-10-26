<?php

namespace App\Http\Controllers;

use App\Models\MasterDosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterDosen::withCount('jadwal');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $isAktif = $request->status === 'aktif' ? 1 : 0;
            $query->where('status_aktif', $isAktif);
        }

        $dosenList = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();

        $totalDosen = MasterDosen::count();
        $totalAktif = MasterDosen::where('status_aktif', 1)->count();
        $totalNonAktif = $totalDosen - $totalAktif;

        return view('dosen.index', compact('dosenList', 'totalDosen', 'totalAktif', 'totalNonAktif'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:30|unique:master_dosen,nip',
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'status_aktif' => 'nullable|boolean',
        ]);

        $validated['status_aktif'] = $request->has('status_aktif') ? 1 : 0;

        MasterDosen::create($validated);

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $dosen = MasterDosen::findOrFail($id);

        $validated = $request->validate([
            'nip' => 'required|string|max:30|unique:master_dosen,nip,' . $id,
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'status_aktif' => 'nullable|boolean',
        ]);

        $validated['status_aktif'] = $request->has('status_aktif') ? 1 : 0;

        $dosen->update($validated);

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        $dosen = MasterDosen::findOrFail($id);
        $dosen->status_aktif = !$dosen->status_aktif;
        $dosen->save();

        $statusText = $dosen->status_aktif ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('dosen.index')->with('success', "Status dosen {$dosen->nama} berhasil {$statusText}!");
    }

    public function destroy($id)
    {
        $dosen = MasterDosen::findOrFail($id);

        if ($dosen->jadwal()->count() > 0) {
            return back()->withErrors([
                'delete_error' => "Dosen '{$dosen->nama}' tidak dapat dihapus karena masih terikat pada {$dosen->jadwal()->count()} jadwal kuliah.",
            ]);
        }

        $dosen->delete();

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil dihapus!');
    }
}
