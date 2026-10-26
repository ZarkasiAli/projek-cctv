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
        $ruanganList = Ruangan::all();
        $selectedRoomId = $request->input('ruangan_id', optional($ruanganList->first())->id ?? 1);
        $selectedRoom = Ruangan::find($selectedRoomId) ?? $ruanganList->first();

        $viewMode = $request->input('mode', 'single'); // 'single' or 'grid'

        $now = now();
        $currentTime = $now->format('H:i:s');
        $currentHari = $this->getIndonesianDay($now->format('l'));
        $todayDate = $now->format('Y-m-d');
        $formattedDate = $this->getIndonesianFormattedDate($now);

        // Find active schedule for the selected room right now or for today
        $activeJadwal = null;
        if ($selectedRoom) {
            $activeJadwal = Jadwal::with(['dosen', 'mataKuliah', 'ruangan'])
                ->where('ruangan_id', $selectedRoom->id)
                ->where('hari', $currentHari)
                ->where('jam_mulai', '<=', $currentTime)
                ->where('jam_selesai', '>=', $currentTime)
                ->first();

            // If none is strictly active at this second, get the first schedule for today or any featured schedule
            if (!$activeJadwal) {
                $activeJadwal = Jadwal::with(['dosen', 'mataKuliah', 'ruangan'])
                    ->where('ruangan_id', $selectedRoom->id)
                    ->where('hari', $currentHari)
                    ->first();
            }

            if (!$activeJadwal) {
                $activeJadwal = Jadwal::with(['dosen', 'mataKuliah', 'ruangan'])
                    ->where('ruangan_id', $selectedRoom->id)
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

        // Also prepare room feeds with their schedules for grid mode
        $roomFeeds = $ruanganList->map(function ($r) use ($currentHari, $currentTime) {
            $sch = Jadwal::with(['dosen', 'mataKuliah'])
                ->where('ruangan_id', $r->id)
                ->where('hari', $currentHari)
                ->first() ?? Jadwal::with(['dosen', 'mataKuliah'])->where('ruangan_id', $r->id)->first();

            return [
                'ruangan' => $r,
                'jadwal' => $sch,
            ];
        });

        return view('cctv.index', compact(
            'ruanganList',
            'selectedRoom',
            'activeJadwal',
            'sesi',
            'now',
            'formattedDate',
            'allUsers',
            'viewMode',
            'roomFeeds'
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
