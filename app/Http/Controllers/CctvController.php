<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Ruangan;
use App\Models\SesiKelas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CctvController extends Controller
{
    private function isPlayableCctvStream(?string $streamUrl): bool
    {
        if (blank($streamUrl)) {
            return false;
        }

        $normalized = trim($streamUrl);

        if (str_contains($normalized, 'localhost/cctv') || str_contains($normalized, '127.0.0.1/cctv')) {
            return false;
        }

        $supportedPatterns = [
            '/\.m3u8(?:\?.*)?$/i',
            '/\.(mp4|webm|ogg|mov|m4v)(?:\?.*)?$/i',
        ];

        foreach ($supportedPatterns as $pattern) {
            if (preg_match($pattern, $normalized)) {
                return true;
            }
        }

        return false;
    }

    private function getIndonesianFormattedDate(Carbon $date): string
    {
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];
        return "{$date->format('d')} {$months[$date->month]} {$date->format('Y')}";
    }

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

    public function index(Request $request)
    {
        $allRuangan = Ruangan::all();

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

        $selectedLantai = $request->input('lantai');
        $ruanganList = $allRuangan;
        if ($request->filled('lantai') && $request->lantai !== 'all') {
            $ruanganList = $allRuangan->filter(function ($r) use ($selectedLantai) {
                return stripos($r->lokasi ?? '', $selectedLantai) !== false;
            })->values();
        }

        $selectedRoomId = $request->input('ruangan_id');
        if ($selectedRoomId && $ruanganList->contains('id', $selectedRoomId)) {
            $selectedRoom = $ruanganList->firstWhere('id', $selectedRoomId);
        } else {
            $selectedRoom = $ruanganList->first() ?? $allRuangan->first();
        }
        $isSelectedRoomPlayable = $selectedRoom ? $this->isPlayableCctvStream($selectedRoom->link_stream_cctv ?? null) : false;

        $viewMode = $request->input('mode', 'single'); // 'single' or 'grid'

        $now = now();
        $currentTime = $now->format('H:i:s');
        $currentHari = $this->getIndonesianDay($now->format('l'));
        $todayDate = $now->format('Y-m-d');
        $formattedDate = $this->getIndonesianFormattedDate($now);

        // Find active schedule for the selected room right now or upcoming today
        $activeJadwal = null;
        if ($selectedRoom) {
            $activeJadwal = Jadwal::with(['dosen', 'mataKuliah', 'ruangan'])
                ->where('ruangan_id', $selectedRoom->id)
                ->where('hari', $currentHari)
                ->where('jam_mulai', '<=', $currentTime)
                ->where('jam_selesai', '>=', $currentTime)
                ->first();

            // If none is strictly active, get next upcoming schedule today that has not passed yet
            if (!$activeJadwal) {
                $activeJadwal = Jadwal::with(['dosen', 'mataKuliah', 'ruangan'])
                    ->where('ruangan_id', $selectedRoom->id)
                    ->where('hari', $currentHari)
                    ->where('jam_mulai', '>', $currentTime)
                    ->orderBy('jam_mulai', 'asc')
                    ->first();
            }
        }

        // Find or prepare session for verification
        $sesi = null;
        if ($activeJadwal) {
            $sesi = SesiKelas::where('jadwal_id', $activeJadwal->id)
                ->whereDate('tanggal', $todayDate)
                ->first();

            if (!$sesi) {
                $sesi = SesiKelas::where('jadwal_id', $activeJadwal->id)->latest('tanggal')->first();
            }
        }

        $allUsers = User::all();

        // Also prepare room feeds with their schedules for grid mode (sesuai jamnya)
        $roomFeeds = $ruanganList->map(function ($r) use ($currentHari, $currentTime) {
            $sch = Jadwal::with(['dosen', 'mataKuliah'])
                ->where('ruangan_id', $r->id)
                ->where('hari', $currentHari)
                ->where('jam_mulai', '<=', $currentTime)
                ->where('jam_selesai', '>=', $currentTime)
                ->first();

            if (!$sch) {
                $sch = Jadwal::with(['dosen', 'mataKuliah'])
                    ->where('ruangan_id', $r->id)
                    ->where('hari', $currentHari)
                    ->where('jam_mulai', '>', $currentTime)
                    ->orderBy('jam_mulai', 'asc')
                    ->first();
            }

            return [
                'ruangan' => $r,
                'jadwal' => $sch,
                'stream_url' => $r->link_stream_cctv ?? null,
                'is_playable' => $this->isPlayableCctvStream($r->link_stream_cctv ?? null),
            ];
        });

        return view('cctv.index', compact(
            'ruanganList',
            'selectedRoom',
            'isSelectedRoomPlayable',
            'activeJadwal',
            'sesi',
            'now',
            'formattedDate',
            'allUsers',
            'viewMode',
            'roomFeeds',
            'daftarLantai',
            'selectedLantai'
        ));
    }

    public function verifikasi(Request $request)
    {
        $validated = $request->validate([
            'sesi_id' => 'nullable|exists:sesi_kelas,id',
            'jadwal_id' => 'required|exists:jadwal,id',
            'catatan' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $now = now();
        $userId = $validated['user_id'] ?? (Auth::id() ?? 1);

        if (!empty($validated['sesi_id'])) {
            $sesi = SesiKelas::find($validated['sesi_id']);
        } else {
            $sesi = SesiKelas::where('jadwal_id', $validated['jadwal_id'])
                ->whereDate('tanggal', $now->format('Y-m-d'))
                ->first();

            if (!$sesi) {
                $sesi = SesiKelas::create([
                    'jadwal_id' => $validated['jadwal_id'],
                    'tanggal' => $now->format('Y-m-d'),
                    'status_verifikasi' => 'belum',
                ]);
            }
        }

        $sesi->status_verifikasi = 'terverifikasi';
        $sesi->diverifikasi_oleh = $userId;
        $sesi->diverifikasi_pada = $now;
        $sesi->save();

        $jadwal = Jadwal::with(['mataKuliah', 'ruangan', 'dosen'])->find($validated['jadwal_id']);
        $mkName = $jadwal->mataKuliah->nama_mata_kuliah ?? 'Mata Kuliah';
        $dosenName = $jadwal->dosen->nama ?? 'Dosen';
        $roomName = $jadwal->ruangan->nama ?? 'Ruangan';

        return back()->with('verified_success', [
            'title' => 'Verifikasi Berhasil!',
            'message' => "Kelas {$mkName} oleh {$dosenName} di {$roomName} berhasil diverifikasi sebagai berjalan.",
            'waktu' => $now->format('d/m/Y H:i:s'),
            'catatan' => $validated['catatan'] ?? 'Terverifikasi sesuai jadwal perkuliahan',
        ]);
    }
}
