<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalShift;
use App\Models\LogAktivitas;
use App\Models\Shift;
use App\Models\TandaTanganAbsensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::with(['user', 'jadwal.shift'])->latest('tanggal');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            $query->whereDate('tanggal', today());
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status_kehadiran', $request->status);
        }

        // If not admin/ketua, only show own
        if (!auth()->user()->canApprove()) {
            $query->where('user_id', auth()->id());
        }

        $absensi  = $query->paginate(15)->withQueryString();
        $karyawan = User::where('status', 'aktif')->orderBy('name')->get();

        return view('absensi.index', compact('absensi', 'karyawan'));
    }

    /**
     * Show absen masuk / pulang form
     */
    public function create()
    {
        $user      = auth()->user();
        $today     = today();
        $absensi   = $user->absensiHariIni();
        $jadwal    = JadwalShift::with('shift')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        return view('absensi.create', compact('user', 'today', 'absensi', 'jadwal'));
    }

    /**
     * Record absen masuk
     */
    public function absenMasuk(Request $request)
    {
        $user  = auth()->user();
        $today = today();

        // Check if already clocked in
        if ($user->absensiHariIni()?->jam_masuk) {
            return back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        $request->validate([
            'latitude'  => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'lokasi'    => ['nullable', 'string', 'max:255'],
            'ttd_masuk' => ['required', 'string'],
        ]);

        // Verifikasi Radius Geofence (Haversine Formula)
        $kopdes = $user->kopdes;
        if ($kopdes && !is_null($kopdes->latitude) && !is_null($kopdes->longitude)) {
            $jarak = $this->calculateHaversineDistance(
                $request->latitude, 
                $request->longitude, 
                $kopdes->latitude, 
                $kopdes->longitude
            );
            
            if ($jarak > $kopdes->radius_meter) {
                $jarakBulat = round($jarak, 1);
                return back()->with('error', "Absen ditolak. Jarak Anda terlalu jauh dari kantor Kopdes ({$jarakBulat} meter dari radius {$kopdes->radius_meter} meter).");
            }
        }

        // Get or create jadwal
        $jadwal = JadwalShift::firstOrCreate(
            ['user_id' => $user->id, 'tanggal' => $today],
            [
                'shift_id'   => $user->shift_default_id ?? Shift::first()->id,
                'status'     => 'terjadwal',
                'created_by' => $user->id,
            ]
        );

        // Determine lateness : 3-tier: hadir / terlambat / sangat_terlambat
        $shift    = $jadwal->shift ?? Shift::find($user->shift_default_id) ?? Shift::first();
        $jamMulai = Carbon::parse($today->format('Y-m-d') . ' ' . $shift->jam_mulai);
        $now      = Carbon::now();

        // Minutes late (negative or 0 = on time)
        $terlambatMenit  = max(0, (int) $jamMulai->diffInMinutes($now, false) * -1);
        // diffInMinutes(absolute=false): negative means $now is AFTER $jamMulai
        // So if $now is after $jamMulai, $jamMulai->diffInMinutes($now, false) is negative
        // Recalculate properly:
        $terlambatMenit  = $now->gt($jamMulai) ? (int) $jamMulai->diffInMinutes($now) : 0;

        if ($terlambatMenit === 0) {
            $statusKehadiran = 'hadir';
        } elseif ($terlambatMenit <= 30) {
            $statusKehadiran = 'terlambat';        // orange: 1-30 menit
        } else {
            $statusKehadiran = 'sangat_terlambat'; // red: 31+ menit
        }


        $absensi = Absensi::updateOrCreate(
            ['user_id' => $user->id, 'tanggal' => $today],
            [
                'jadwal_id'          => $jadwal->id,
                'jam_masuk'          => $now->format('H:i:s'),
                'latitude_masuk'     => $request->latitude,
                'longitude_masuk'    => $request->longitude,
                'lokasi_masuk'       => $request->lokasi ?? 'Tidak diketahui',
                'status_kehadiran'   => $statusKehadiran,
                'keterlambatan_menit' => $terlambatMenit,
                'metode_absen_masuk' => 'manual',
            ]
        );

        // Save signature
        TandaTanganAbsensi::updateOrCreate(
            ['absensi_id' => $absensi->id],
            ['user_id' => $user->id, 'ttd_masuk' => $request->ttd_masuk]
        );

        $jadwal->update(['status' => 'hadir']);

        LogAktivitas::catat('absen_masuk', "Absen masuk pukul {$now->format('H:i')} di {$absensi->lokasi_masuk}");

        return back()->with('success', 'Absen masuk berhasil dicatat pukul ' . $now->format('H:i'));
    }

    /**
     * Record absen pulang
     */
    public function absenPulang(Request $request)
    {
        $user    = auth()->user();
        $absensi = $user->absensiHariIni();

        if (!$absensi || !$absensi->jam_masuk) {
            return back()->with('error', 'Anda belum melakukan absen masuk hari ini.');
        }

        if ($absensi->jam_pulang) {
            return back()->with('error', 'Anda sudah melakukan absen pulang hari ini.');
        }

        $request->validate([
            'latitude'   => ['required', 'numeric'],
            'longitude'  => ['required', 'numeric'],
            'lokasi'     => ['nullable', 'string', 'max:255'],
            'ttd_pulang' => ['required', 'string'],
        ]);

        // Verifikasi Radius Geofence (Haversine Formula)
        $kopdes = $user->kopdes;
        if ($kopdes && !is_null($kopdes->latitude) && !is_null($kopdes->longitude)) {
            $jarak = $this->calculateHaversineDistance(
                $request->latitude, 
                $request->longitude, 
                $kopdes->latitude, 
                $kopdes->longitude
            );
            
            if ($jarak > $kopdes->radius_meter) {
                $jarakBulat = round($jarak, 1);
                return back()->with('error', "Absen ditolak. Jarak Anda terlalu jauh dari kantor Kopdes ({$jarakBulat} meter dari radius {$kopdes->radius_meter} meter).");
            }
        }

        $now = Carbon::now();

        $absensi->update([
            'jam_pulang'          => $now->format('H:i:s'),
            'latitude_pulang'     => $request->latitude,
            'longitude_pulang'    => $request->longitude,
            'lokasi_pulang'       => $request->lokasi ?? 'Tidak diketahui',
            'metode_absen_pulang' => 'manual',
        ]);

        // Update signature
        TandaTanganAbsensi::updateOrCreate(
            ['absensi_id' => $absensi->id],
            ['ttd_pulang' => $request->ttd_pulang]
        );

        LogAktivitas::catat('absen_pulang', "Absen pulang pukul {$now->format('H:i')} di {$absensi->lokasi_pulang}");

        return back()->with('success', 'Absen pulang berhasil dicatat pukul ' . $now->format('H:i'));
    }

    /**
     * Helper to calculate distance in meters using Haversine formula.
     */
    private function calculateHaversineDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000; // in meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // distance in meters
    }

    public function show(Absensi $absensi)
    {
        $absensi->load(['user', 'jadwal.shift', 'tandaTangan.verifikator']);
        return view('absensi.show', compact('absensi'));
    }

    /**
     * Admin: verify signature
     */
    public function verifikasiTtd(Request $request, Absensi $absensi)
    {
        $request->validate([
            'status_verifikasi'   => ['required', 'in:terverifikasi,ditolak'],
            'catatan_verifikasi'  => ['nullable', 'string'],
        ]);

        TandaTanganAbsensi::updateOrCreate(
            ['absensi_id' => $absensi->id],
            [
                'status_verifikasi'  => $request->status_verifikasi,
                'verifikator_id'     => auth()->id(),
                'catatan_verifikasi' => $request->catatan_verifikasi,
            ]
        );

        return back()->with('success', 'Status verifikasi tanda tangan berhasil diperbarui.');
    }
}
