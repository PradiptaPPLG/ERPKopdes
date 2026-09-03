<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
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
            'kopdes',
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
            'name'           => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'recovery_email' => ['nullable', 'email', 'max:150'],
            'nik'            => ['nullable', 'string', 'size:16', Rule::unique('users', 'nik')->ignore($user->id)],
            'nip'            => ['nullable', 'string', 'max:20', Rule::unique('users', 'nip')->ignore($user->id)],
            'tempat_lahir'   => ['nullable', 'string', 'max:50'],
            'tanggal_lahir'  => ['nullable', 'date', 'before_or_equal:' . now()->subYears(17)->format('Y-m-d')],
            'jenis_kelamin'  => ['nullable', Rule::in(['L', 'P'])],
            'agama'          => ['nullable', 'string', 'max:20'],
            'alamat'         => ['nullable', 'string'],
            'no_hp'          => ['nullable', 'string', 'max:13'],
            'foto_profil'    => ['nullable', 'image', 'max:2048'],
            'id_card_theme'  => ['nullable', 'integer'],
        ], [
            'nik.size'                      => 'NIK harus 16 digit.',
            'email.unique'                  => 'Email sudah digunakan.',
            'nik.unique'                    => 'NIK sudah terdaftar.',
            'nip.unique'                    => 'NIP sudah terdaftar.',
            'recovery_email.email'          => 'Format email pemulihan tidak valid.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak valid. Usia karyawan minimal harus 17 tahun.',
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('foto-profil', 'public');
        }

        // Validate id_card_theme unlocking
        if (isset($data['id_card_theme'])) {
            $unlockedTiers = $user->unlocked_tiers;
            if (!in_array($data['id_card_theme'], $unlockedTiers)) {
                $data['id_card_theme'] = $user->id_card_theme ?: 1; // Revert to current if tampering
            }
        }

        $user->update($data);

        LogAktivitas::catat('edit_profile', 'Mengubah informasi profil mandiri');

        return redirect()->route('profile.show')
            ->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Show the device sessions list.
     */
    public function sessions()
    {
        $sessions = \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', auth()->id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                // Parsing user agent sederhana
                $userAgent = $session->user_agent;
                $browser = 'Browser Tidak Diketahui';
                $platform = 'Device Tidak Diketahui';

                if (preg_match('/windows|win32/i', $userAgent)) {
                    $platform = 'Windows';
                } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
                    $platform = 'Mac';
                } elseif (preg_match('/linux/i', $userAgent)) {
                    $platform = 'Linux';
                } elseif (preg_match('/android/i', $userAgent)) {
                    $platform = 'Android';
                } elseif (preg_match('/iphone/i', $userAgent)) {
                    $platform = 'iPhone';
                }

                if (preg_match('/chrome/i', $userAgent)) {
                    $browser = 'Chrome';
                } elseif (preg_match('/firefox/i', $userAgent)) {
                    $browser = 'Firefox';
                } elseif (preg_match('/safari/i', $userAgent)) {
                    $browser = 'Safari';
                } elseif (preg_match('/edge/i', $userAgent)) {
                    $browser = 'Edge';
                } elseif (preg_match('/opera/i', $userAgent)) {
                    $browser = 'Opera';
                }

                return (object) [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'is_current_device' => $session->id === request()->session()->getId(),
                    'browser' => $browser,
                    'platform' => $platform,
                    'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity),
                ];
            });

        return view('profile.sessions', compact('sessions'));
    }

    /**
     * Terminate the given session.
     */
    public function destroySession(Request $request, $id)
    {
        \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', auth()->id())
            ->where('id', $id)
            ->delete();

        LogAktivitas::catat('force_logout_device', 'Menghentikan sesi perangkat lain (' . $id . ')');

        return back()->with('success', 'Perangkat berhasil dikeluarkan.');
    }
}

