<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\MasterDosen;
use App\Models\MasterMataKuliah;
use App\Models\Ruangan;
use App\Models\SesiKelas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $ruanganList = Ruangan::orderBy('nama')->get();
        $dosenList = MasterDosen::orderBy('nama')->get();
        $mataKuliahList = MasterMataKuliah::orderBy('nama_mata_kuliah')->get();

        $query = SesiKelas::with(['jadwal.ruangan', 'jadwal.dosen', 'jadwal.mataKuliah', 'verifikator']);

        // Date range filter
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Room filter
        if ($request->filled('ruangan_id') && $request->ruangan_id !== 'all') {
            $query->whereHas('jadwal', function ($q) use ($request) {
                $q->where('ruangan_id', $request->ruangan_id);
            });
        }

        // Dosen filter
        if ($request->filled('dosen_id') && $request->dosen_id !== 'all') {
            $query->whereHas('jadwal', function ($q) use ($request) {
                $q->where('dosen_id', $request->dosen_id);
            });
        }

        // Mata Kuliah filter
        if ($request->filled('mata_kuliah_id') && $request->mata_kuliah_id !== 'all') {
            $query->whereHas('jadwal', function ($q) use ($request) {
                $q->where('mata_kuliah_id', $request->mata_kuliah_id);
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status_verifikasi', $request->status);
        }

        $sesiList = $query->orderBy('tanggal', 'desc')->orderBy('jadwal_id', 'asc')->get();

        $totalSesi = $sesiList->count();
        $totalTerverifikasi = $sesiList->where('status_verifikasi', 'terverifikasi')->count();
        $totalBelum = $totalSesi - $totalTerverifikasi;
        $persentase = $totalSesi > 0 ? round(($totalTerverifikasi / $totalSesi) * 100, 1) : 0;

        $selectedTanggalDari = $request->input('tanggal_dari', '');
        $selectedTanggalSampai = $request->input('tanggal_sampai', '');
        $selectedRuangan = $request->input('ruangan_id', 'all');
        $selectedDosen = $request->input('dosen_id', 'all');
        $selectedMK = $request->input('mata_kuliah_id', 'all');
        $selectedStatus = $request->input('status', 'all');

        $isPrint = $request->boolean('print');

        return view('report.index', compact(
            'ruanganList',
            'dosenList',
            'mataKuliahList',
            'sesiList',
            'totalSesi',
            'totalTerverifikasi',
            'totalBelum',
            'persentase',
            'selectedTanggalDari',
            'selectedTanggalSampai',
            'selectedRuangan',
            'selectedDosen',
            'selectedMK',
            'selectedStatus',
            'isPrint'
        ));
    }

    public function export(Request $request)
    {
        return $this->index($request->merge(['print' => true]));
    }
}
