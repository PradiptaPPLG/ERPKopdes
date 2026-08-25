<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    /**
     * API untuk memeriksa apakah email mengaktifkan 2FA (Bypass Captcha)
     */
    public function check2fa(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'has_2fa' => $user ? $user->hasTwoFactorEnabled() : false
        ]);
    }

    /**
     * Tampilkan halaman tantangan 2FA setelah password terverifikasi
     */
    public function showChallenge()
    {
        if (!session()->has('auth.2fa.user_id')) {
            return redirect()->route('login');
        }

        return view('auth.2fa_challenge');
    }

    /**
     * Proses verifikasi OTP 2FA saat login
     */
    public function verifyChallenge(Request $request)
    {
        if (!session()->has('auth.2fa.user_id')) {
            return redirect()->route('login');
        }

        $request->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'Kode OTP harus diisi.',
            'code.digits'   => 'Kode OTP harus berupa 6 digit angka.',
        ]);

        $userId = session('auth.2fa.user_id');
        $user = User::findOrFail($userId);

        $google2fa = new Google2FA();
        
        // Dekripsi secret key dari DB
        $secret = Crypt::decryptString($user->two_factor_secret);
        
        $isValid = $google2fa->verifyKey($secret, $request->code, 8);

        if (!$isValid) {
            return back()->withErrors(['code' => 'Kode OTP yang Anda masukkan salah atau sudah kedaluwarsa.']);
        }

        // OTP Valid, loginkan user secara penuh
        Auth::login($user, session('auth.2fa.remember', false));

        // Hapus penampung session 2FA
        session()->forget(['auth.2fa.user_id', 'auth.2fa.remember']);

        $request->session()->regenerate();

        // One Session Cache
        Cache::put('user_session_' . $user->id, $request->session()->getId());

        LogAktivitas::catat('login', "User {$user->name} ({$user->jabatan}) login penuh dengan 2FA.");

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Setup 2FA: Tampilkan QR Code & Secret Key
     */
    public function setup()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.show')->with('warning', '2FA Anda sudah aktif.');
        }

        $google2fa = new Google2FA();

        // Generate secret key baru jika belum ada di session (supaya tidak berubah saat refresh halaman sebelum konfirmasi)
        if (!session()->has('auth.2fa_setup_secret')) {
            $secretKey = $google2fa->generateSecretKey();
            session(['auth.2fa_setup_secret' => $secretKey]);
        } else {
            $secretKey = session('auth.2fa_setup_secret');
        }

        // Generate URL QR Code
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name', 'ERP Kopdes'),
            $user->email,
            $secretKey
        );

        // Render QR Code menjadi SVG menggunakan BaconQrCode
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        return view('profile.2fa_setup', compact('secretKey', 'qrCodeSvg'));
    }

    /**
     * Konfirmasi kode OTP pertama untuk mengaktifkan 2FA
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'Kode OTP wajib diisi untuk konfirmasi.',
            'code.digits'   => 'Kode OTP harus berupa 6 digit angka.',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!session()->has('auth.2fa_setup_secret')) {
            return redirect()->route('profile.show')->with('error', 'Sesi setup kedaluwarsa. Silakan ulangi.');
        }

        $secret = session('auth.2fa_setup_secret');
        $google2fa = new Google2FA();

        $isValid = $google2fa->verifyKey($secret, $request->code, 8);

        if (!$isValid) {
            return back()->withErrors(['code' => 'Kode OTP salah. Silakan periksa kembali aplikasi Google Authenticator Anda.']);
        }

        // Simpan secret key terenkripsi dan set tanggal konfirmasi
        $user->update([
            'two_factor_secret' => Crypt::encryptString($secret),
            'two_factor_confirmed_at' => now(),
        ]);

        session()->forget('auth.2fa_setup_secret');

        LogAktivitas::catat('enable_2fa', 'Mengaktifkan otentikasi dua faktor (2FA) mandiri.');

        return redirect()->route('profile.show')->with('success', 'Otentikasi Dua Faktor (2FA) berhasil diaktifkan!');
    }

    /**
     * Nonaktifkan 2FA
     */
    public function disable(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.show')->with('warning', '2FA Anda belum aktif.');
        }

        $user->update([
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
        ]);

        LogAktivitas::catat('disable_2fa', 'Menonaktifkan otentikasi dua faktor (2FA) mandiri.');

        return redirect()->route('profile.show')->with('success', 'Otentikasi Dua Faktor (2FA) berhasil dinonaktifkan.');
    }
}
