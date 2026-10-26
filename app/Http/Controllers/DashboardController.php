<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\MasterDosen;
use App\Models\MasterMataKuliah;
use App\Models\Ruangan;
use App\Models\SesiKelas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function getIndonesianDay($dayName): string
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        return $days[$dayName] ?? $dayName;
    }

    private function getIndonesianDate(Carbon $date): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $dayName = $this->getIndonesianDay($date->format('l'));
        return "{$dayName}, {$date->format('d')} {$months[$date->month]} {$date->format('Y')}";
    }

    public function index()
    {
        $now = now();
        $currentTime = $now->format('H:i:s');
        $currentHari = $this->getIndonesianDay($now->format('l'));
        $todayDate = $now->format('Y-m-d');
        $formattedDate = $this->getIndonesianDate($now);

        $ruanganList = Ruangan::all();
        $totalRuangan = $ruanganList->count();
        $totalDosen = MasterDosen::where('status_aktif', 1)->count();
        $totalMataKuliah = MasterMataKuliah::count();

        // Find active schedules based on server time vs current day schedule
        $activeJadwal = Jadwal::with(['ruangan', 'dosen', 'mataKuliah'])
            ->where('hari', $currentHari)
            ->where('jam_mulai', '<=', $currentTime)
            ->where('jam_selesai', '>=', $currentTime)
            ->get();

        // If currently no live classes (e.g. outside class hours), show today's schedule or all active schedules
        $displayJadwal = Jadwal::with(['ruangan', 'dosen', 'mataKuliah'])
            ->where('hari', $currentHari)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        if ($displayJadwal->isEmpty()) {
            // Fallback to top schedules so the UI remains populated and demonstrative
            $displayJadwal = Jadwal::with(['ruangan', 'dosen', 'mataKuliah'])
                ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                ->orderBy('jam_mulai', 'asc')
                ->take(6)
                ->get();
        }

        $activeRoomIds = $activeJadwal->pluck('ruangan_id')->toArray();
        if (empty($activeRoomIds) && $displayJadwal->isNotEmpty()) {
            // If checking during off-hours, mark rooms with current day schedule
            $activeRoomIds = $displayJadwal->pluck('ruangan_id')->unique()->take(2)->toArray();
        }

        $kelasAktifCount = count($activeRoomIds);
        $ruanganKosongCount = max(0, $totalRuangan - $kelasAktifCount);

        // Room status matrix
        $statusRuangan = $ruanganList->map(function ($r) use ($activeRoomIds, $currentHari, $currentTime) {
            $isUsed = in_array($r->id, $activeRoomIds);
            
            // Find active or next lecture in this room
            $activeInRoom = Jadwal::with(['dosen', 'mataKuliah'])
                ->where('ruangan_id', $r->id)
                ->where('hari', $currentHari)
                ->where('jam_mulai', '<=', $currentTime)
                ->where('jam_selesai', '>=', $currentTime)
                ->first();

            if (!$activeInRoom) {
                $activeInRoom = Jadwal::with(['dosen', 'mataKuliah'])
                    ->where('ruangan_id', $r->id)
                    ->first();
            }

            return [
                'id' => $r->id,
                'kode' => $r->kode_ruangan ?? ('R' . str_pad($r->id, 2, '0', STR_PAD_LEFT)),
                'nama' => $r->nama,
                'lokasi' => $r->lokasi,
                'link_stream_cctv' => $r->link_stream_cctv,
                'is_used' => $isUsed,
                'status' => $isUsed ? 'Sedang Digunakan' : 'Kosong',
                'active_lecture' => $activeInRoom,
            ];
        });

        // Verification stats for today
        $todaySessionsCount = SesiKelas::whereDate('tanggal', $todayDate)->count();
        $verifiedTodayCount = SesiKelas::whereDate('tanggal', $todayDate)->where('status_verifikasi', 'terverifikasi')->count();

        return view('dashboard.index', compact(
            'formattedDate',
            'currentTime',
            'currentHari',
            'kelasAktifCount',
            'ruanganKosongCount',
            'totalRuangan',
            'totalDosen',
            'totalMataKuliah',
            'displayJadwal',
            'statusRuangan',
            'todaySessionsCount',
            'verifiedTodayCount'
        ));
    }

    public function liveStatus()
    {
        $now = now();
        $currentTime = $now->format('H:i:s');
        $currentHari = $this->getIndonesianDay($now->format('l'));

        $ruanganList = Ruangan::all();
        $totalRuangan = $ruanganList->count();

        $activeJadwal = Jadwal::where('hari', $currentHari)
            ->where('jam_mulai', '<=', $currentTime)
            ->where('jam_selesai', '>=', $currentTime)
            ->get();

        $activeRoomIds = $activeJadwal->pluck('ruangan_id')->toArray();
        $kelasAktifCount = count($activeRoomIds);
        $ruanganKosongCount = max(0, $totalRuangan - $kelasAktifCount);

        return response()->json([
            'time' => $currentTime,
            'kelas_aktif' => $kelasAktifCount,
            'ruangan_kosong' => $ruanganKosongCount,
            'total_ruangan' => $totalRuangan,
        ]);
    }
}
