<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile.
     */
    public function show()
    {
        $karyawan = auth()->user();
        $karyawan->load([
            'shiftDefault',
            'absensi' => fn($q) => $q->latest()->take(10),
            'izinCuti' => fn($q) => $q->latest()->take(5)
        ]);

        return view('profile.show', compact('karyawan'));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $karyawan = auth()->user();
        return view('profile.edit', compact('karyawan'));
    }

    /**
     * Update the profile in storage.
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'password'      => ['nullable', 'min:6', 'confirmed'],
            'nik'           => ['nullable', 'string', 'size:16', Rule::unique('users', 'nik')->ignore($user->id)],
            'nip'           => ['nullable', 'string', 'max:20', Rule::unique('users', 'nip')->ignore($user->id)],
            'tempat_lahir'  => ['nullable', 'string', 'max:50'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
            'agama'         => ['nullable', 'string', 'max:20'],
            'alamat'        => ['nullable', 'string'],
            'no_hp'         => ['nullable', 'string', 'max:13'],
            'foto_profil'   => ['nullable', 'image', 'max:2048'],
        ], [
            'nik.size'     => 'NIK harus 16 digit.',
            'email.unique' => 'Email sudah digunakan.',
            'nik.unique'   => 'NIK sudah terdaftar.',
            'nip.unique'   => 'NIP sudah terdaftar.',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('foto-profil', 'public');
        }

        $user->update($data);

        LogAktivitas::catat('edit_profile', 'Mengubah informasi profil mandiri');

        return redirect()->route('profile.show')
            ->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
