<?php

namespace App\Http\Controllers;

use App\Models\IzinCuti;
use App\Models\LogAktivitas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class IzinCutiController extends Controller
{
    public function index(Request $request)
    {
        $query = IzinCuti::with(['user', 'approver'])->latest();

        // Non-admin only sees their own
        if (!auth()->user()->canApprove()) {
            $query->where('user_id', auth()->id());
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $izinCuti = $query->paginate(15)->withQueryString();
        $karyawan = User::where('status', 'aktif')->orderBy('name')->get();

        return view('izin.index', compact('izinCuti', 'karyawan'));
    }

    public function create()
    {
        return view('izin.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis'           => ['required', Rule::in(['cuti_tahunan','sakit','izin_pribadi','dinas_luar'])],
            'tanggal_mulai'   => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan'          => ['required', 'string', 'min:10'],
            'lampiran'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ], [
            'tanggal_mulai.after_or_equal'  => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'alasan.min'                    => 'Alasan minimal 10 karakter.',
        ]);

        $data['user_id'] = auth()->id();
        $data['status']  = 'pending';

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('lampiran-izin', 'public');
        }

        $izin = IzinCuti::create($data);

        LogAktivitas::catat('ajukan_izin', "Mengajukan {$izin->jenisLabel()} mulai {$izin->tanggal_mulai->format('d M Y')}");

        return redirect()->route('izin.index')
            ->with('success', 'Permohonan izin berhasil diajukan dan menunggu persetujuan.');
    }

    public function show(IzinCuti $izin)
    {
        $izin->load(['user', 'approver']);
        return view('izin.show', compact('izin'));
    }

    /**
     * Admin approve / reject
     */
    public function approve(Request $request, IzinCuti $izin)
    {
        $request->validate([
            'status'          => ['required', 'in:disetujui,ditolak'],
            'catatan_approver' => ['nullable', 'string'],
        ]);

        $izin->update([
            'status'           => $request->status,
            'approver_id'      => auth()->id(),
            'catatan_approver' => $request->catatan_approver,
        ]);

        $aksi = $request->status === 'disetujui' ? 'approve_izin' : 'tolak_izin';
        LogAktivitas::catat($aksi, "Memproses izin {$izin->user->name}: {$izin->statusLabel()}");

        return back()->with('success', "Permohonan izin berhasil {$izin->statusLabel()}.");
    }

    public function destroy(IzinCuti $izin)
    {
        if ($izin->status !== 'pending') {
            return back()->with('error', 'Hanya permohonan pending yang bisa dibatalkan.');
        }

        if ($izin->user_id !== auth()->id() && !auth()->user()->canApprove()) {
            abort(403);
        }

        if ($izin->lampiran) {
            Storage::disk('public')->delete($izin->lampiran);
        }

        $izin->delete();

        return redirect()->route('izin.index')->with('success', 'Permohonan izin dibatalkan.');
    }
}
