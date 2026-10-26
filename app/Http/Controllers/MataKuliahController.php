<?php

namespace App\Http\Controllers;

use App\Models\MasterMataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterMataKuliah::withCount('jadwal');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_mata_kuliah', 'like', "%{$search}%")
                  ->orWhere('kode_mata_kuliah', 'like', "%{$search}%");
            });
        }

        $mataKuliahList = $query->orderBy('kode_mata_kuliah', 'asc')->paginate(10)->withQueryString();
        $totalMataKuliah = MasterMataKuliah::count();

        return view('mata_kuliah.index', compact('mataKuliahList', 'totalMataKuliah'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mata_kuliah' => 'required|string|max:20|unique:master_mata_kuliah,kode_mata_kuliah',
            'nama_mata_kuliah' => 'required|string|max:100',
        ]);

        MasterMataKuliah::create($validated);

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $mk = MasterMataKuliah::findOrFail($id);

        $validated = $request->validate([
            'kode_mata_kuliah' => 'required|string|max:20|unique:master_mata_kuliah,kode_mata_kuliah,' . $id,
            'nama_mata_kuliah' => 'required|string|max:100',
        ]);

        $mk->update($validated);

        return redirect()->route('mata-kuliah.index')->with('success', 'Data mata kuliah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $mk = MasterMataKuliah::findOrFail($id);

        if ($mk->jadwal()->count() > 0) {
            return back()->withErrors([
                'delete_error' => "Mata kuliah '{$mk->nama_mata_kuliah}' tidak dapat dihapus karena masih terikat pada {$mk->jadwal()->count()} jadwal kuliah.",
            ]);
        }

        $mk->delete();

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil dihapus!');
    }
}
