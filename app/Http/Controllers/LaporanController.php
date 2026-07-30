<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan   = (int) $request->input('bulan', now()->month);
        $tahun   = (int) $request->input('tahun', now()->year);
        $userId  = $request->input('user_id');

        $tanggalAwal  = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

        $karyawan = User::where('status', '!=', 'nonaktif')->orderBy('name')->get();

        // ── Heatmap Data ──────────────────────────────────────────
        // Per-day summary for the whole koperasi (or filtered user)
        $heatmapQuery = Absensi::selectRaw(
            'tanggal,
             COUNT(*) as total_absen,
             SUM(status_kehadiran IN ("hadir","terlambat","sangat_terlambat")) as hadir,
             SUM(status_kehadiran = "terlambat") as terlambat,
             SUM(status_kehadiran = "sangat_terlambat") as sangat_terlambat,
             SUM(status_kehadiran = "alpa") as alpa,
             SUM(status_kehadiran = "izin") as izin,
             SUM(status_kehadiran = "sakit") as sakit'
        )->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

        if ($userId) {
            $heatmapQuery->where('user_id', $userId);
        }

        $heatmapData = $heatmapQuery->groupBy('tanggal')->get()
            ->keyBy(fn($r) => Carbon::parse($r->tanggal)->format('Y-m-d'));

        // ── Per-employee summary ──────────────────────────────────
        $ringkasanQuery = Absensi::selectRaw(
            'user_id,
             COUNT(*) as total_hari,
             SUM(status_kehadiran IN ("hadir","terlambat","sangat_terlambat")) as hadir,
             SUM(status_kehadiran = "terlambat") as terlambat,
             SUM(status_kehadiran = "sangat_terlambat") as sangat_terlambat,
             SUM(status_kehadiran = "alpa") as alpa,
             SUM(status_kehadiran = "izin") as izin,
             SUM(status_kehadiran = "sakit") as sakit,
             SUM(keterlambatan_menit) as total_terlambat_menit'
        )->with('user')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

        if ($userId) {
            $ringkasanQuery->where('user_id', $userId);
        }

        $ringkasan = $ringkasanQuery->groupBy('user_id')->get();

        $daysInMonth  = $tanggalAwal->daysInMonth;
        $bulanNama    = $tanggalAwal->translatedFormat('F Y');

        return view('laporan.index', compact(
            'heatmapData', 'ringkasan', 'karyawan',
            'bulan', 'tahun', 'bulanNama', 'daysInMonth',
            'tanggalAwal', 'tanggalAkhir', 'userId'
        ));
    }
}
