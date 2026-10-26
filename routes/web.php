<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CctvController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\SesiKelasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Main Application Routes
Route::middleware(['web'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard-status', [DashboardController::class, 'liveStatus'])->name('dashboard.api');

    // Manajemen Ruangan & CCTV
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::post('/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
    Route::put('/ruangan/{id}', [RuanganController::class, 'update'])->name('ruangan.update');
    Route::delete('/ruangan/{id}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');

    // Master Dosen
    Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
    Route::post('/dosen', [DosenController::class, 'store'])->name('dosen.store');
    Route::put('/dosen/{id}', [DosenController::class, 'update'])->name('dosen.update');
    Route::patch('/dosen/{id}/toggle-status', [DosenController::class, 'toggleStatus'])->name('dosen.toggle');
    Route::delete('/dosen/{id}', [DosenController::class, 'destroy'])->name('dosen.destroy');

    // Master Mata Kuliah
    Route::get('/mata-kuliah', [MataKuliahController::class, 'index'])->name('mata-kuliah.index');
    Route::post('/mata-kuliah', [MataKuliahController::class, 'store'])->name('mata-kuliah.store');
    Route::put('/mata-kuliah/{id}', [MataKuliahController::class, 'update'])->name('mata-kuliah.update');
    Route::delete('/mata-kuliah/{id}', [MataKuliahController::class, 'destroy'])->name('mata-kuliah.destroy');

    // Input Jadwal Kuliah
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

    // Generate Sesi Kelas
    Route::get('/generate-sesi', [SesiKelasController::class, 'index'])->name('sesi.index');
    Route::post('/generate-sesi', [SesiKelasController::class, 'generate'])->name('sesi.generate');

    // Monitoring CCTV
    Route::get('/cctv', [CctvController::class, 'index'])->name('cctv.index');
    Route::post('/cctv/verifikasi', [CctvController::class, 'verifikasi'])->name('cctv.verifikasi');

    // Report
    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::get('/report/export', [ReportController::class, 'export'])->name('report.export');
});
