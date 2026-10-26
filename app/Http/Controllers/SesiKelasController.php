<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\SesiKelas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SesiKelasController extends Controller
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

    private function getIndonesianFormattedDate(Carbon $date): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return "{$date->format('d')} {$months[$date->month]} {$date->format('Y')}";
    }

    public function index(Request $request)
    {
        // Default to today or the latest session date in DB
        $defaultDate = now()->format('Y-m-d');
        if (!SesiKelas::whereDate('tanggal', $defaultDate)->exists()) {
            $latestInDb = SesiKelas::latest('tanggal')->value('tanggal');
            if ($latestInDb) {
                $defaultDate = Carbon::parse($latestInDb)->format('Y-m-d');
            }
        }

        $selectedDate = $request->input('tanggal', $defaultDate);
        $parsedDate = Carbon::parse($selectedDate);
        $formattedDate = $this->getIndonesianFormattedDate($parsedDate);
        $dayOfWeek = $this->getIndonesianDay($parsedDate->format('l'));

        $sesiList = SesiKelas::with(['jadwal.ruangan', 'jadwal.dosen', 'jadwal.mataKuliah', 'verifikator'])
            ->whereDate('tanggal', $selectedDate)
            ->get();

        $totalSesi = $sesiList->count();
        $totalTerverifikasi = $sesiList->where('status_verifikasi', 'terverifikasi')->count();
        $totalBelum = $totalSesi - $totalTerverifikasi;

        // Quick date shortcuts
        $todayDate = now()->format('Y-m-d');
        $yesterdayDate = now()->subDay()->format('Y-m-d');
        $tomorrowDate = now()->addDay()->format('Y-m-d');

        return view('sesi.index', compact(
            'selectedDate',
            'formattedDate',
            'dayOfWeek',
            'sesiList',
            'totalSesi',
            'totalTerverifikasi',
            'totalBelum',
            'todayDate',
            'yesterdayDate',
            'tomorrowDate'
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $selectedDate = $request->input('tanggal');
        $parsedDate = Carbon::parse($selectedDate);
        $dayOfWeek = $this->getIndonesianDay($parsedDate->format('l'));

        // Find all schedules matching day of week
        $schedules = Jadwal::where('hari', $dayOfWeek)->get();

        if ($schedules->isEmpty()) {
            // If no schedules match this specific day, allow user to generate all schedules for demonstration
            $schedules = Jadwal::all();
        }

        $createdCount = 0;
        foreach ($schedules as $schedule) {
            $exists = SesiKelas::where('jadwal_id', $schedule->id)
                ->whereDate('tanggal', $selectedDate)
                ->exists();

            if (!$exists) {
                SesiKelas::create([
                    'jadwal_id' => $schedule->id,
                    'tanggal' => $selectedDate,
                    'status_verifikasi' => 'belum',
                ]);
                $createdCount++;
            }
        }

        $formatted = $this->getIndonesianFormattedDate($parsedDate);
        if ($createdCount > 0) {
            return redirect()->route('sesi.index', ['tanggal' => $selectedDate])
                ->with('success', "Berhasil men-generate {$createdCount} sesi kelas untuk hari {$dayOfWeek}, {$formatted}!");
        } else {
            return redirect()->route('sesi.index', ['tanggal' => $selectedDate])
                ->with('success', "Semua sesi kelas ({$schedules->count()} kelas) untuk tanggal {$formatted} sudah tersedia.");
        }
    }
}
