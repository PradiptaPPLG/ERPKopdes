<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\IzinCuti;
use App\Models\JadwalShift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today   = Carbon::today();
        $month   = $today->month;
        $year    = $today->year;
        $user    = auth()->user();

        // ── Summary cards ─────────────────────────────────────────
        $totalKaryawan = User::where('status', '!=', 'nonaktif')->count();
        $hadirHariIni  = Absensi::whereDate('tanggal', $today)
            ->whereIn('status_kehadiran', ['hadir', 'terlambat', 'sangat_terlambat'])->count();
        $terlambatHariIni = Absensi::whereDate('tanggal', $today)
            ->whereIn('status_kehadiran', ['terlambat', 'sangat_terlambat'])->count();
        $sangatTerlambatHariIni = Absensi::whereDate('tanggal', $today)
            ->where('status_kehadiran', 'sangat_terlambat')->count();
        $izinPending   = IzinCuti::where('status', 'pending')->count();

        // ── Today's attendance list ───────────────────────────────
        $absensiHariIni = Absensi::with(['user', 'jadwal.shift'])
            ->whereDate('tanggal', $today)
            ->latest()
            ->take(8)
            ->get();

        // ── Heatmap data (current month) ─────────────────────────
        // Admin: agregat semua karyawan. Karyawan: hanya data milik sendiri.
        $heatmapQuery = Absensi::selectRaw('tanggal,
                COUNT(*) as total,
                SUM(status_kehadiran IN ("hadir","terlambat","sangat_terlambat")) as hadir,
                SUM(status_kehadiran = "terlambat") as terlambat,
                SUM(status_kehadiran = "sangat_terlambat") as sangat_terlambat,
                SUM(status_kehadiran = "alpa") as alpa')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month);

        if (! $user->isAdmin()) {
            $heatmapQuery->where('user_id', $user->id);
        }

        $heatmapData = $heatmapQuery
            ->groupBy('tanggal')
            ->get()
            ->keyBy(fn($r) => Carbon::parse($r->tanggal)->format('Y-m-d'));

        // ── Pending izin list ─────────────────────────────────────
        $izinTerbaru = IzinCuti::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // ── User's own attendance today ───────────────────────────
        $myAbsensi = $user->absensiHariIni();
        $myJadwal  = JadwalShift::with('shift')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        return view('dashboard.index', compact(
            'totalKaryawan', 'hadirHariIni', 'terlambatHariIni', 'sangatTerlambatHariIni',
            'izinPending', 'absensiHariIni', 'heatmapData',
            'izinTerbaru', 'myAbsensi', 'myJadwal', 'today', 'month', 'year'
        ));
    }

    public function panduan()
    {
        return view('panduan.index');
    }
}
