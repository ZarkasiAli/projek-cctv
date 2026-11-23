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

    public function index(Request $request)
    {
        $now = now();
        $currentTime = $now->format('H:i:s');
        $currentHari = $this->getIndonesianDay($now->format('l'));
        $todayDate = $now->format('Y-m-d');
        $formattedDate = $this->getIndonesianDate($now);

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

        $selectedLantai = $request->query('lantai');

        $allRuangan = Ruangan::all();
        $totalRuangan = $allRuangan->count();
        $totalDosen = MasterDosen::where('status_aktif', 1)->count();
        $totalMataKuliah = MasterMataKuliah::count();

        // Floor-filtered rooms for occupancy status matrix
        $ruanganList = $allRuangan;
        if ($request->filled('lantai') && $request->lantai !== 'all') {
            $ruanganList = $allRuangan->filter(function ($r) use ($selectedLantai) {
                return stripos($r->lokasi ?? '', $selectedLantai) !== false;
            });
        }

        // 1. Ongoing schedules right now
        $activeJadwal = Jadwal::with(['ruangan', 'dosen', 'mataKuliah', 'sesiKelas' => function ($q) use ($todayDate) {
                $q->whereDate('tanggal', $todayDate);
            }])
            ->where('hari', $currentHari)
            ->where('jam_mulai', '<=', $currentTime)
            ->where('jam_selesai', '>=', $currentTime)
            ->get();

        // 2. Verified sessions for ongoing classes right now (jam_mulai <= currentTime <= jam_selesai)
        $verifiedSessionsToday = SesiKelas::with(['jadwal.ruangan', 'jadwal.dosen', 'jadwal.mataKuliah'])
            ->whereDate('tanggal', $todayDate)
            ->where('status_verifikasi', 'terverifikasi')
            ->whereHas('jadwal', function ($q) use ($currentHari, $currentTime) {
                $q->where('hari', $currentHari)
                    ->where('jam_mulai', '<=', $currentTime)
                    ->where('jam_selesai', '>=', $currentTime);
            })
            ->get();

        $verifiedRoomIds = $verifiedSessionsToday->pluck('jadwal.ruangan_id')->filter()->unique()->toArray();

        // 3. Display schedules (hanya kelas yang sedang berlangsung saat ini)
        $displayJadwal = Jadwal::with(['ruangan', 'dosen', 'mataKuliah', 'sesiKelas' => function ($q) use ($todayDate) {
                $q->whereDate('tanggal', $todayDate);
            }])
            ->where('hari', $currentHari)
            ->where('jam_mulai', '<=', $currentTime)
            ->where('jam_selesai', '>=', $currentTime)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // Active room IDs = ongoing right now OR verified right now
        $activeRoomIds = array_unique(array_merge(
            $activeJadwal->pluck('ruangan_id')->toArray(),
            $verifiedRoomIds
        ));

        $kelasAktifCount = count($activeRoomIds);
        $ruanganKosongCount = max(0, $totalRuangan - $kelasAktifCount);

        // Room status matrix: HANYA tampilkan kelas yang sedang berlangsung (tidak menampilkan kelas/ruangan tidak terjadwal)
        $statusRuangan = $ruanganList->map(function ($r) use ($activeJadwal, $verifiedSessionsToday) {
            // Check verified session for this room right now
            $verifiedSession = $verifiedSessionsToday->first(function ($session) use ($r) {
                return $session->jadwal && $session->jadwal->ruangan_id == $r->id;
            });

            // Check ongoing class right now
            $ongoingLecture = $activeJadwal->firstWhere('ruangan_id', $r->id);

            $isVerified = ($verifiedSession !== null);
            $isOngoing = (!$isVerified && $ongoingLecture !== null);
            $isUsed = ($isVerified || $isOngoing);

            // Lecture to display: hanya tampil jika sesuai jamnya sekarang
            $lectureToShow = null;
            if ($isVerified && $verifiedSession->jadwal) {
                $lectureToShow = $verifiedSession->jadwal;
            } elseif ($isOngoing) {
                $lectureToShow = $ongoingLecture;
            }

            $statusText = 'Kosong';
            if ($isVerified) {
                $statusText = 'Terverifikasi';
            } elseif ($isOngoing) {
                $statusText = 'Sedang Digunakan';
            }

            return [
                'id' => $r->id,
                'kode' => $r->kode_ruangan ?? ('R' . str_pad($r->id, 2, '0', STR_PAD_LEFT)),
                'nama' => $r->nama,
                'lokasi' => $r->lokasi,
                'link_stream_cctv' => $r->link_stream_cctv,
                'is_used' => $isUsed,
                'is_verified' => $isVerified,
                'is_ongoing' => $isOngoing,
                'is_upcoming' => false,
                'status' => $statusText,
                'active_lecture' => $lectureToShow,
                'verified_session' => $verifiedSession,
            ];
        })
        ->filter(function ($r) {
            // Hanya tampilkan kelas yang sedang berlangsung (filter out kelas tidak terjadwal/kosong)
            return $r['is_used'] && $r['active_lecture'] !== null;
        })
        ->values();

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
            'verifiedTodayCount',
            'daftarLantai',
            'selectedLantai'
        ));
    }

    public function liveStatus()
    {
        $now = now();
        $currentTime = $now->format('H:i:s');
        $currentHari = $this->getIndonesianDay($now->format('l'));
        $todayDate = $now->format('Y-m-d');

        $ruanganList = Ruangan::all();
        $totalRuangan = $ruanganList->count();

        $activeJadwal = Jadwal::where('hari', $currentHari)
            ->where('jam_mulai', '<=', $currentTime)
            ->where('jam_selesai', '>=', $currentTime)
            ->get();

        $verifiedRoomIds = Jadwal::whereIn('id', function ($query) use ($todayDate) {
                $query->select('jadwal_id')
                    ->from('sesi_kelas')
                    ->whereDate('tanggal', $todayDate)
                    ->where('status_verifikasi', 'terverifikasi');
            })
            ->where('hari', $currentHari)
            ->where('jam_mulai', '<=', $currentTime)
            ->where('jam_selesai', '>=', $currentTime)
            ->pluck('ruangan_id')
            ->toArray();

        $activeRoomIds = array_unique(array_merge($activeJadwal->pluck('ruangan_id')->toArray(), $verifiedRoomIds));
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
