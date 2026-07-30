<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('shiftDefault')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q
                ->where('name', 'like', "%{$s}%")
                ->orWhere('nip', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%")
                ->orWhere('no_hp', 'like', "%{$s}%")
            );
        }

        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $karyawan = $query->paginate(10)->withQueryString();

        return view('karyawan.index', compact('karyawan'));
    }

    public function create()
    {
        $shifts = Shift::all();
        return view('karyawan.create', compact('shifts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'        => ['required', 'min:6', 'confirmed'],
            'nik'             => ['nullable', 'string', 'size:16', 'unique:users,nik'],
            'nip'             => ['nullable', 'string', 'max:20', 'unique:users,nip'],
            'tempat_lahir'    => ['nullable', 'string', 'max:50'],
            'tanggal_lahir'   => ['nullable', 'date'],
            'jenis_kelamin'   => ['nullable', Rule::in(['L', 'P'])],
            'agama'           => ['nullable', 'string', 'max:20'],
            'alamat'          => ['nullable', 'string'],
            'no_hp'           => ['nullable', 'string', 'max:13'],
            'jabatan'         => ['required', Rule::in(['admin','ketua','sekretaris','bendahara','kasir','petugas_toko'])],
            'status'          => ['required', Rule::in(['aktif','nonaktif','cuti'])],
            'shift_default_id' => ['nullable', 'exists:shifts,id'],
            'foto_profil'     => ['nullable', 'image', 'max:2048'],
        ], [
            'nik.size'   => 'NIK harus 16 digit.',
            'email.unique' => 'Email sudah digunakan.',
            'nik.unique' => 'NIK sudah terdaftar.',
        ]);

        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = $request->file('foto_profil')
                ->store('foto-profil', 'public');
        }

        $karyawan = User::create($data);

        LogAktivitas::catat(
            'tambah_karyawan',
            "Menambahkan karyawan baru: {$karyawan->name} ({$karyawan->jabatan})"
        );

        return redirect()->route('karyawan.index')
            ->with('success', "Karyawan {$karyawan->name} berhasil ditambahkan.");
    }

    public function show(User $karyawan)
    {
        $karyawan->load(['shiftDefault', 'absensi' => fn($q) => $q->latest()->take(10), 'izinCuti' => fn($q) => $q->latest()->take(5)]);
        return view('karyawan.show', compact('karyawan'));
    }

    public function edit(User $karyawan)
    {
        $shifts = Shift::all();
        return view('karyawan.edit', compact('karyawan', 'shifts'));
    }

    public function update(Request $request, User $karyawan)
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($karyawan->id)],
            'password'        => ['nullable', 'min:6', 'confirmed'],
            'nik'             => ['nullable', 'string', 'size:16', Rule::unique('users', 'nik')->ignore($karyawan->id)],
            'nip'             => ['nullable', 'string', 'max:20', Rule::unique('users', 'nip')->ignore($karyawan->id)],
            'tempat_lahir'    => ['nullable', 'string', 'max:50'],
            'tanggal_lahir'   => ['nullable', 'date'],
            'jenis_kelamin'   => ['nullable', Rule::in(['L', 'P'])],
            'agama'           => ['nullable', 'string', 'max:20'],
            'alamat'          => ['nullable', 'string'],
            'no_hp'           => ['nullable', 'string', 'max:13'],
            'jabatan'         => ['required', Rule::in(['admin','ketua','sekretaris','bendahara','kasir','petugas_toko'])],
            'status'          => ['required', Rule::in(['aktif','nonaktif','cuti'])],
            'shift_default_id' => ['nullable', 'exists:shifts,id'],
            'foto_profil'     => ['nullable', 'image', 'max:2048'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('foto_profil')) {
            if ($karyawan->foto_profil) {
                Storage::disk('public')->delete($karyawan->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')
                ->store('foto-profil', 'public');
        }

        $karyawan->update($data);

        LogAktivitas::catat(
            'edit_karyawan',
            "Mengubah data karyawan: {$karyawan->name}"
        );

        return redirect()->route('karyawan.show', $karyawan)
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(User $karyawan)
    {
        if ($karyawan->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $name = $karyawan->name;

        if ($karyawan->foto_profil) {
            Storage::disk('public')->delete($karyawan->foto_profil);
        }

        $karyawan->delete();

        LogAktivitas::catat('hapus_karyawan', "Menghapus karyawan: {$name}");

        return redirect()->route('karyawan.index')
            ->with('success', "Karyawan {$name} berhasil dihapus.");
    }
}
