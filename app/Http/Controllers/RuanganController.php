<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruangan::withCount('jadwal');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_ruangan', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('lantai') && $request->lantai !== 'all') {
            $lantai = $request->lantai;
            $query->where(function ($q) use ($lantai) {
                $q->where('lokasi', $lantai)
                  ->orWhere('lokasi', 'like', "%{$lantai}%");
            });
        }

        $ruangan = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        $totalRuangan = Ruangan::count();

        // Get sorted unique list of floors
        $daftarLantai = Ruangan::whereNotNull('lokasi')
            ->where('lokasi', '!=', '')
            ->pluck('lokasi')
            ->unique()
            ->sort(function ($a, $b) {
                preg_match('/\d+/', $a, $mA);
                preg_match('/\d+/', $b, $mB);
                $numA = isset($mA[0]) ? (int) $mA[0] : 0;
                $numB = isset($mB[0]) ? (int) $mB[0] : 0;
                if ($numA === $numB) {
                    return strcmp($a, $b);
                }
                return $numA <=> $numB;
            })
            ->values();

        return view('ruangan.index', compact('ruangan', 'totalRuangan', 'daftarLantai'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'kode_ruangan' => 'nullable|string|max:4|unique:ruangan,kode_ruangan',
            'lokasi' => 'required|string|max:100',
            'link_stream_cctv' => 'required|string|max:255',
        ]);

        if (empty($validated['kode_ruangan'])) {
            $nextId = (Ruangan::max('id') ?? 0) + 1;
            $validated['kode_ruangan'] = 'R' . str_pad($nextId, 2, '0', STR_PAD_LEFT);
        }

        Ruangan::create($validated);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'kode_ruangan' => 'nullable|string|max:4|unique:ruangan,kode_ruangan,' . $id,
            'lokasi' => 'required|string|max:100',
            'link_stream_cctv' => 'required|string|max:255',
        ]);

        $ruangan->update($validated);

        return redirect()->route('ruangan.index')->with('success', 'Data ruangan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);

        if ($ruangan->jadwal()->count() > 0) {
            return back()->withErrors([
                'delete_error' => "Ruangan '{$ruangan->nama}' tidak dapat dihapus karena masih digunakan dalam {$ruangan->jadwal()->count()} jadwal kuliah.",
            ]);
        }

        $ruangan->delete();

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus!');
    }
}
