<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::withCount('users')->latest()->get();
        return view('shift.index', compact('shifts'));
    }

    public function create()
    {
        return view('shift.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_shift'                    => ['required', 'string', 'max:20'],
            'kode_warna'                    => ['required', 'string', 'size:7'],
            'jam_mulai'                     => ['required', 'date_format:H:i'],
            'jam_selesai'                   => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'toleransi_keterlambatan_menit' => ['required', 'integer', 'min:0', 'max:60'],
            'deskripsi'                     => ['nullable', 'string'],
        ], [
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        // Auto-calculate durasi
        $mulai    = \Carbon\Carbon::parse($data['jam_mulai']);
        $selesai  = \Carbon\Carbon::parse($data['jam_selesai']);
        $data['durasi_menit'] = $mulai->diffInMinutes($selesai);

        Shift::create($data);

        return redirect()->route('shift.index')
            ->with('success', "Shift {$data['nama_shift']} berhasil ditambahkan.");
    }

    public function edit(Shift $shift)
    {
        return view('shift.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $data = $request->validate([
            'nama_shift'                    => ['required', 'string', 'max:20'],
            'kode_warna'                    => ['required', 'string', 'size:7'],
            'jam_mulai'                     => ['required', 'date_format:H:i'],
            'jam_selesai'                   => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'toleransi_keterlambatan_menit' => ['required', 'integer', 'min:0', 'max:60'],
            'deskripsi'                     => ['nullable', 'string'],
        ]);

        $mulai    = \Carbon\Carbon::parse($data['jam_mulai']);
        $selesai  = \Carbon\Carbon::parse($data['jam_selesai']);
        $data['durasi_menit'] = $mulai->diffInMinutes($selesai);

        $shift->update($data);

        return redirect()->route('shift.index')
            ->with('success', "Shift {$shift->nama_shift} berhasil diperbarui.");
    }

    public function destroy(Shift $shift)
    {
        if ($shift->users()->count() > 0) {
            return back()->with('error', 'Shift tidak dapat dihapus karena masih digunakan oleh karyawan.');
        }

        $shift->delete();
        return redirect()->route('shift.index')
            ->with('success', 'Shift berhasil dihapus.');
    }
}
