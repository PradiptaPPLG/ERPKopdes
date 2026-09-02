<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IzinCutiController;
use App\Http\Controllers\JadwalShiftController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KopdesController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

// ── Public routes ──────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/login/qr', [LoginController::class, 'loginQr'])->name('login.qr');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rute Publik untuk Otentikasi Dua Faktor (2FA)
Route::post('/login/check-2fa', [\App\Http\Controllers\Auth\TwoFactorController::class, 'check2fa'])->name('login.check-2fa');
Route::get('/login/2fa', [\App\Http\Controllers\Auth\TwoFactorController::class, 'showChallenge'])->name('login.2fa');
Route::post('/login/2fa', [\App\Http\Controllers\Auth\TwoFactorController::class, 'verifyChallenge'])->name('login.2fa.post');

// ── Forgot Password (OTP flow) — Publik ──────────────────────────
Route::get('/password/forgot',          [ForgotPasswordController::class, 'showEmailForm'])->name('password.forgot');
Route::post('/password/forgot',         [ForgotPasswordController::class, 'submitEmail'])->name('password.forgot.submit');
Route::post('/password/otp/send',       [ForgotPasswordController::class, 'sendOtp'])->name('password.otp.send');
Route::get('/password/otp/verify',      [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp.verify');
Route::post('/password/otp/verify',     [ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.check');
Route::get('/password/reset',           [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/password/reset',          [ForgotPasswordController::class, 'resetPassword'])->name('password.reset.submit');

// ── Authenticated routes ───────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/panduan',   [DashboardController::class, 'panduan'])->name('panduan');

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
        Route::post('/karyawan/import-csv',    [KaryawanController::class, 'importCsv'])->name('karyawan.import-csv');
        Route::get('/karyawan/template-csv',   [KaryawanController::class, 'templateCsv'])->name('karyawan.template-csv');
        Route::resource('karyawan', KaryawanController::class);

        // Kopdes
        Route::resource('kopdes', KopdesController::class)->parameters([
            'kopdes' => 'kopde'
        ]);

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

    // ── Notifications ──────────────────────────────────────────
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');

    // ── Forced first-login password change (middleware exempt) ───
    Route::get('/password/force-change',    [ForgotPasswordController::class, 'showForceChangeForm'])->name('password.force.change');
    Route::post('/password/force-change',   [ForgotPasswordController::class, 'forceChangePassword'])->name('password.force.update');

    // ── Profile ────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // ── Profile: Ubah Password via OTP ──────────────────────────
    Route::get('/profile/change-password',       [ForgotPasswordController::class, 'showProfileChangeForm'])->name('profile.change-password');
    Route::post('/profile/change-password/otp',  [ForgotPasswordController::class, 'profileSendOtp'])->name('profile.password.otp.send');
    Route::post('/profile/change-password',      [ForgotPasswordController::class, 'profileUpdatePassword'])->name('profile.password.update');

    // ── Profile Device Sessions Manager ─────────────────────────
    Route::get('/profile/sessions', [ProfileController::class, 'sessions'])->name('profile.sessions');
    Route::delete('/profile/sessions/{id}', [ProfileController::class, 'destroySession'])->name('profile.sessions.destroy');

    // ── Profile 2FA Setup ───────────────────────────────────────
    Route::get('/profile/2fa/setup', [\App\Http\Controllers\Auth\TwoFactorController::class, 'setup'])->name('profile.2fa.setup');
    Route::post('/profile/2fa/confirm', [\App\Http\Controllers\Auth\TwoFactorController::class, 'confirm'])->name('profile.2fa.confirm');
    Route::post('/profile/2fa/disable', [\App\Http\Controllers\Auth\TwoFactorController::class, 'disable'])->name('profile.2fa.disable');

    // ── Admin-Only Activity Logs Dashboard & Exporter ───────────
    Route::middleware('role:admin,ketua,sekretaris')->group(function () {
        Route::get('/admin/logs', [LaporanController::class, 'activityLogs'])->name('admin.logs');
        Route::get('/admin/logs/export', [LaporanController::class, 'exportActivityLogs'])->name('admin.logs.export');
    });
});

