<?php

namespace App\Http\Controllers;

use App\Models\JadwalShift;
use App\Models\LogAktivitas;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalShiftController extends Controller
{
    public function index(Request $request)
    {
        // Default to current month
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $tanggalAwal  = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

        $karyawan = User::where('status', 'aktif')->orderBy('name')->get();

        // Build calendar data: [user_id => [date => jadwal]]
        $jadwals = JadwalShift::with(['shift', 'user', 'absensi'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->get()
            ->groupBy('user_id');

        $daysInMonth = $tanggalAwal->daysInMonth;

        return view('jadwal.index', compact(
            'karyawan', 'jadwals', 'bulan', 'tahun',
            'tanggalAwal', 'tanggalAkhir', 'daysInMonth'
        ));
    }

    public function create()
    {
        $karyawan = User::where('status', 'aktif')->orderBy('name')->get();
        $shifts   = Shift::all();
        return view('jadwal.create', compact('karyawan', 'shifts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_ids'   => ['required', 'array', 'min:1'],
            'user_ids.*' => ['exists:users,id'],
            'shift_id'   => ['required', 'exists:shifts,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'skip_weekend'   => ['sometimes', 'boolean'],
        ]);

        $mulai   = Carbon::parse($data['tanggal_mulai']);
        $selesai = Carbon::parse($data['tanggal_selesai']);
        $created = 0;
        $skipped = 0;

        while ($mulai->lte($selesai)) {
            if ($data['skip_weekend'] ?? false) {
                if ($mulai->isWeekend()) {
                    $mulai->addDay();
                    continue;
                }
            }

            foreach ($data['user_ids'] as $userId) {
                $exists = JadwalShift::where('user_id', $userId)
                    ->where('tanggal', $mulai->format('Y-m-d'))
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                JadwalShift::create([
                    'user_id'    => $userId,
                    'shift_id'   => $data['shift_id'],
                    'tanggal'    => $mulai->format('Y-m-d'),
                    'status'     => 'terjadwal',
                    'created_by' => auth()->id(),
                ]);
                $created++;
            }

            $mulai->addDay();
        }

        LogAktivitas::catat('buat_jadwal', "Membuat {$created} jadwal shift (lewati: {$skipped})");

        return redirect()->route('jadwal.index')
            ->with('success', "{$created} jadwal berhasil dibuat" . ($skipped ? ", {$skipped} sudah ada dilewati." : '.'));
    }

    public function destroy(JadwalShift $jadwal)
    {
        if ($jadwal->absensi) {
            return back()->with('error', 'Jadwal tidak bisa dihapus karena sudah memiliki data absensi.');
        }

        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
