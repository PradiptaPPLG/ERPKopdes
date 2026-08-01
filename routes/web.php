<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IzinCutiController;
use App\Http\Controllers\JadwalShiftController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

// ── Public routes ──────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Authenticated routes ───────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Absensi ────────────────────────────────────────────────
    Route::prefix('absensi')->name('absensi.')->group(function () {
        // All authenticated users: view list
        Route::get('/', [AbsensiController::class, 'index'])->name('index');

        // Staff only: clock in / clock out form (admin/ketua CANNOT absen)
        // ⚠️ Harus di atas /{absensi} agar "form" tidak tertangkap wildcard
        Route::middleware('role:karyawan,bendahara,kasir,petugas_toko,sekretaris')->group(function () {
            Route::get('/form',    [AbsensiController::class, 'create'])->name('create');
            Route::post('/masuk',  [AbsensiController::class, 'absenMasuk'])->name('masuk');
            Route::post('/pulang', [AbsensiController::class, 'absenPulang'])->name('pulang');
        });

        // Wildcard routes : harus di bawah route statis
        Route::get('/{absensi}', [AbsensiController::class, 'show'])->name('show');
        Route::post('/{absensi}/verifikasi', [AbsensiController::class, 'verifikasiTtd'])
            ->name('verifikasi')
            ->middleware('role:admin,ketua,sekretaris');
    });

    // ── Izin & Cuti (all authenticated users) ──────────────────
    Route::prefix('izin')->name('izin.')->group(function () {
        Route::get('/',             [IzinCutiController::class, 'index'])->name('index');
        Route::get('/tambah',       [IzinCutiController::class, 'create'])->name('create');
        Route::post('/',            [IzinCutiController::class, 'store'])->name('store');
        Route::get('/{izin}',       [IzinCutiController::class, 'show'])->name('show');
        Route::post('/{izin}/approve', [IzinCutiController::class, 'approve'])
            ->name('approve')
            ->middleware('role:admin,ketua,sekretaris');
        Route::delete('/{izin}',    [IzinCutiController::class, 'destroy'])->name('destroy');
    });

    // ── Admin-only routes ───────────────────────────────────────
    Route::middleware('role:admin,ketua,sekretaris')->group(function () {

        // Karyawan
        Route::resource('karyawan', KaryawanController::class);

        // Shift
        Route::resource('shift', ShiftController::class)->except(['show']);

        // Jadwal Shift
        Route::prefix('jadwal')->name('jadwal.')->group(function () {
            Route::get('/',         [JadwalShiftController::class, 'index'])->name('index');
            Route::get('/tambah',   [JadwalShiftController::class, 'create'])->name('create');
            Route::post('/',        [JadwalShiftController::class, 'store'])->name('store');
            Route::delete('/{jadwal}', [JadwalShiftController::class, 'destroy'])->name('destroy');
        });

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });
});
