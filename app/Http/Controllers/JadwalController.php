<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\MasterDosen;
use App\Models\MasterMataKuliah;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $ruangan = Ruangan::orderBy('nama', 'asc')->get();
        $dosenList = MasterDosen::where('status_aktif', 1)->orderBy('nama', 'asc')->get();
        $mataKuliahList = MasterMataKuliah::orderBy('nama_mata_kuliah', 'asc')->get();

        $query = Jadwal::with(['ruangan', 'dosen', 'mataKuliah']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('prodi', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhereHas('mataKuliah', function ($mq) use ($search) {
                      $mq->where('nama_mata_kuliah', 'like', "%{$search}%")
                         ->orWhere('kode_mata_kuliah', 'like', "%{$search}%");
                  })
                  ->orWhereHas('dosen', function ($dq) use ($search) {
                      $dq->where('nama', 'like', "%{$search}%")
                         ->orWhere('nip', 'like', "%{$search}%");
                  })
                  ->orWhereHas('ruangan', function ($rq) use ($search) {
                      $rq->where('nama', 'like', "%{$search}%")
                         ->orWhere('kode_ruangan', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('hari') && $request->hari !== 'all') {
            $query->where('hari', $request->hari);
        }

        if ($request->filled('ruangan_id') && $request->ruangan_id !== 'all') {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        $jadwalList = $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_ke_mulai', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->paginate(10)
            ->withQueryString();

        $totalJadwal = Jadwal::count();

        return view('jadwal.index', compact('ruangan', 'dosenList', 'mataKuliahList', 'jadwalList', 'totalJadwal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id',
            'dosen_id' => 'required|exists:master_dosen,id',
            'mata_kuliah_id' => 'required|exists:master_mata_kuliah,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_ke_mulai' => 'required|integer|min:1|max:12',
            'jam_ke_selesai' => 'required|integer|min:1|max:12|gte:jam_ke_mulai',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'prodi' => 'required|string|max:50',
            'kelas' => 'required|string|max:50',
        ]);

        // Check for room schedule conflict on the same day
        $conflict = Jadwal::with(['mataKuliah', 'ruangan'])
            ->where('ruangan_id', $validated['ruangan_id'])
            ->where('hari', $validated['hari'])
            ->where(function ($q) use ($validated) {
                $q->where(function ($inner) use ($validated) {
                    $inner->where('jam_mulai', '<', $validated['jam_selesai'])
                          ->where('jam_selesai', '>', $validated['jam_mulai']);
                });
            })
            ->first();

        if ($conflict) {
            $mkName = $conflict->mataKuliah->nama_mata_kuliah ?? 'Mata Kuliah Lain';
            return back()->withInput()->withErrors([
                'jam_mulai' => "Konflik Jadwal: Ruangan sudah digunakan untuk '{$mkName}' ({$conflict->jam_range}) pada hari {$conflict->hari}.",
            ]);
        }

        Jadwal::create($validated);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal perkuliahan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validated = $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id',
            'dosen_id' => 'required|exists:master_dosen,id',
            'mata_kuliah_id' => 'required|exists:master_mata_kuliah,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_ke_mulai' => 'required|integer|min:1|max:12',
            'jam_ke_selesai' => 'required|integer|min:1|max:12|gte:jam_ke_mulai',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'prodi' => 'required|string|max:50',
            'kelas' => 'required|string|max:50',
        ]);

        // Check for room conflict excluding current schedule
        $conflict = Jadwal::with('mataKuliah')
            ->where('id', '!=', $id)
            ->where('ruangan_id', $validated['ruangan_id'])
            ->where('hari', $validated['hari'])
            ->where(function ($q) use ($validated) {
                $q->where(function ($inner) use ($validated) {
                    $inner->where('jam_mulai', '<', $validated['jam_selesai'])
                          ->where('jam_selesai', '>', $validated['jam_mulai']);
                });
            })
            ->first();

        if ($conflict) {
            $mkName = $conflict->mataKuliah->nama_mata_kuliah ?? 'Mata Kuliah Lain';
            return back()->withInput()->withErrors([
                'jam_mulai' => "Konflik Jadwal: Ruangan sudah digunakan untuk '{$mkName}' ({$conflict->jam_range}) pada hari {$conflict->hari}.",
            ]);
        }

        $jadwal->update($validated);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal perkuliahan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        if ($jadwal->sesiKelas()->count() > 0) {
            $jadwal->sesiKelas()->delete();
        }

        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal perkuliahan berhasil dihapus!');
    }
}
