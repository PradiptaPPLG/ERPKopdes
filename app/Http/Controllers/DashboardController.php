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

        // Deteksi apakah user bertindak sebagai Manager Kopdes Cabang tertentu
        $managedKopdes = \App\Models\Kopdes::where('manager_id', $user->id)->first();
        $isBranchManager = !is_null($managedKopdes);

        // ── Summary cards (Dengan filter Manager) ───────────────────
        $karyawanQuery = User::where('status', '!=', 'nonaktif');
        $absensiQuery = Absensi::whereDate('tanggal', $today);
        $izinQuery = IzinCuti::where('status', 'pending');

        if ($isBranchManager) {
            $karyawanQuery->where('kopdes_id', $managedKopdes->id);
            $absensiQuery->whereHas('user', fn($q) => $q->where('kopdes_id', $managedKopdes->id));
            $izinQuery->whereHas('user', fn($q) => $q->where('kopdes_id', $managedKopdes->id));
        }

        $totalKaryawan = $karyawanQuery->count();
        
        $hadirHariIni  = (clone $absensiQuery)
            ->whereIn('status_kehadiran', ['hadir', 'terlambat', 'sangat_terlambat'])->count();
            
        $terlambatHariIni = (clone $absensiQuery)
            ->whereIn('status_kehadiran', ['terlambat', 'sangat_terlambat'])->count();
            
        $sangatTerlambatHariIni = (clone $absensiQuery)
            ->where('status_kehadiran', 'sangat_terlambat')->count();
            
        $izinPending   = $izinQuery->count();

        // ── Today's attendance list ───────────────────────────────
        $absensiListQuery = Absensi::with(['user', 'jadwal.shift'])
            ->whereDate('tanggal', $today);

        if ($isBranchManager) {
            $absensiListQuery->whereHas('user', fn($q) => $q->where('kopdes_id', $managedKopdes->id));
        }

        $absensiHariIni = $absensiListQuery->latest()->take(8)->get();

        // ── Heatmap data (current month) ─────────────────────────
        $heatmapQuery = Absensi::selectRaw('tanggal,
                COUNT(*) as total,
                SUM(status_kehadiran IN ("hadir","terlambat","sangat_terlambat")) as hadir,
                SUM(status_kehadiran = "terlambat") as terlambat,
                SUM(status_kehadiran = "sangat_terlambat") as sangat_terlambat,
                SUM(status_kehadiran = "alpa") as alpa')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month);

        if ($isBranchManager) {
            $heatmapQuery->whereHas('user', fn($q) => $q->where('kopdes_id', $managedKopdes->id));
        } elseif (! $user->isAdmin()) {
            $heatmapQuery->where('user_id', $user->id);
        }

        $heatmapData = $heatmapQuery
            ->groupBy('tanggal')
            ->get()
            ->keyBy(fn($r) => Carbon::parse($r->tanggal)->format('Y-m-d'));

        // ── Pending izin list ─────────────────────────────────────
        $izinTerbaruQuery = IzinCuti::with('user')->where('status', 'pending');

        if ($isBranchManager) {
            $izinTerbaruQuery->whereHas('user', fn($q) => $q->where('kopdes_id', $managedKopdes->id));
        }

        $izinTerbaru = $izinTerbaruQuery->latest()->take(5)->get();

        // ── User's own attendance today ───────────────────────────
        $myAbsensi = $user->absensiHariIni();
        $myJadwal  = JadwalShift::with('shift')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        // ── Admin Analytical Maps & Charts Data ───────────────────
        $kopdesList = collect();
        $provinsiList = collect();
        $chartKaryawanProvinsi = collect();
        $chartKaryawanKopdes = collect();
        $chartKaryawanAktif = collect();
        $chartKehadiranGlobal = collect();

        if ($user->isAdmin() || $user->canApprove() || $isBranchManager) {
            if ($user->isAdmin()) {
                $kopdesList = \App\Models\Kopdes::with(['users' => fn($q) => $q->where('status', '!=', 'nonaktif')])->get();
                
                $provinsiList = \App\Models\Kopdes::select('provinsi', DB::raw('count(*) as count'))
                    ->groupBy('provinsi')
                    ->get();

                $chartKaryawanProvinsi = DB::table('users')
                    ->join('kopdes', 'users.kopdes_id', '=', 'kopdes.id')
                    ->where('users.status', '!=', 'nonaktif')
                    ->select('kopdes.provinsi', DB::raw('count(users.id) as count'))
                    ->groupBy('kopdes.provinsi')
                    ->get();

                $chartKaryawanKopdes = DB::table('users')
                    ->join('kopdes', 'users.kopdes_id', '=', 'kopdes.id')
                    ->where('users.status', '!=', 'nonaktif')
                    ->select('kopdes.nama', DB::raw('count(users.id) as count'))
                    ->groupBy('kopdes.nama')
                    ->get();
            }

            // Chart Karyawan Teraktif di Cabang atau Global
            $chartAktifQuery = DB::table('absensi')
                ->join('users', 'absensi.user_id', '=', 'users.id')
                ->whereYear('absensi.tanggal', $year)
                ->whereMonth('absensi.tanggal', $month)
                ->where('absensi.status_kehadiran', 'hadir')
                ->select('users.name', DB::raw('count(absensi.id) as total_tepat_waktu'));

            if ($isBranchManager) {
                $chartAktifQuery->where('users.kopdes_id', $managedKopdes->id);
            }

            $chartKaryawanAktif = $chartAktifQuery
                ->groupBy('users.id', 'users.name')
                ->orderBy('total_tepat_waktu', 'desc')
                ->take(5)
                ->get();

            // Chart Kehadiran Global vs Cabang
            $chartKehadiranQuery = DB::table('absensi')
                ->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->select('status_kehadiran', DB::raw('count(*) as count'));

            if ($isBranchManager) {
                $chartKehadiranQuery->join('users', 'absensi.user_id', '=', 'users.id')
                    ->where('users.kopdes_id', $managedKopdes->id);
            }

            $chartKehadiranGlobal = $chartKehadiranQuery
                ->groupBy('status_kehadiran')
                ->get();
        }

        return view('dashboard.index', compact(
            'totalKaryawan', 'hadirHariIni', 'terlambatHariIni', 'sangatTerlambatHariIni',
            'izinPending', 'absensiHariIni', 'heatmapData',
            'izinTerbaru', 'myAbsensi', 'myJadwal', 'today', 'month', 'year',
            'kopdesList', 'provinsiList', 'isBranchManager', 'managedKopdes',
            'chartKaryawanProvinsi', 'chartKaryawanKopdes', 'chartKaryawanAktif', 'chartKehadiranGlobal'
        ));
    }

    public function panduan()
    {
        return view('panduan.index');
    }
}
