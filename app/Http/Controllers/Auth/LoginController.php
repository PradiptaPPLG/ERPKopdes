<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Pengecekan awal: apakah user ada dan mengaktifkan 2FA
        $userToCheck = \App\Models\User::where('email', $request->input('email'))->first();
        $has2fa = $userToCheck ? $userToCheck->hasTwoFactorEnabled() : false;

        // Validasi input
        $rules = [
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ];

        // Hanya wajibkan captcha jika user TIDAK memiliki 2FA
        if (!$has2fa) {
            $rules['g-recaptcha-response'] = ['required'];
        }

        $messages = [
            'email.required'                => 'Email harus diisi.',
            'email.email'                   => 'Format email tidak valid.',
            'password.required'             => 'Password harus diisi.',
            'g-recaptcha-response.required' => 'Verifikasi captcha wajib diisi.',
        ];

        $request->validate($rules, $messages);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$minutes} menit.",
            ]);
        }

        // Verifikasi token reCAPTCHA ke API Google (hanya jika tidak memakai 2FA)
        if (!$has2fa) {
            $secretKey = config('services.recaptcha.secret_key');
            $response  = $request->input('g-recaptcha-response');

            $verification = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $response,
                'remoteip' => $request->ip(),
            ]);

            if (!$verification->json('success')) {
                throw ValidationException::withMessages([
                    'email' => 'Verifikasi captcha gagal. Silakan coba lagi.',
                ]);
            }
        }

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        // Cek kecocokan password dan status user tanpa mengautentikasi session penuh dulu jika ada 2FA
        if ($has2fa) {
            // Validasi kredensial secara manual menggunakan Auth::validate()
            if (!Auth::validate($credentials)) {
                $this->hitWithProgressiveDelay($throttleKey);
                throw ValidationException::withMessages([
                    'email' => 'Email atau password salah.',
                ]);
            }

            $user = \App\Models\User::where('email', $credentials['email'])->first();

            if ($user->status === 'nonaktif') {
                throw ValidationException::withMessages([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                ]);
            }

            RateLimiter::clear($throttleKey);
            Cache::forget('rl_step:' . $throttleKey);

            // Simpan ID user ke session sementara untuk 2FA Challenge
            session([
                'auth.2fa.user_id' => $user->id,
                'auth.2fa.remember' => $remember
            ]);

            // Arahkan ke halaman tantangan kode 2FA
            return redirect()->route('login.2fa');
        }

        // Alur login normal jika tidak ada 2FA
        if (!Auth::attempt($credentials, $remember)) {
            $this->hitWithProgressiveDelay($throttleKey);
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $user = Auth::user();

        if ($user->status === 'nonaktif') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        Cache::forget('rl_step:' . $throttleKey);

        $request->session()->regenerate();

        // Simpan ID sesi baru di Cache untuk memvalidasi login tunggal (One Session)
        Cache::put('user_session_' . $user->id, $request->session()->getId());

        LogAktivitas::catat('login', "User {$user->name} ({$user->jabatan}) login ke sistem.");

        return redirect()->intended(route('dashboard'));
    }

    private function hitWithProgressiveDelay(string $key)
    {
        $stepKey = 'rl_step:' . $key;
        $currentStep = (int) Cache::get($stepKey, 0);

        // Urutan delay: 1, 3, 5, 8, 12, 24, 30 menit
        $delays = [1, 3, 5, 8, 12, 24, 30];
        
        $delayMinutes = isset($delays[$currentStep]) ? $delays[$currentStep] : 30;
        $decaySeconds = $delayMinutes * 60;

        RateLimiter::hit($key, $decaySeconds);

        // Update step untuk kesalahan berikutnya
        Cache::put($stepKey, $currentStep + 1, now()->addDay());
    }

    public function loginQr(Request $request)
    {
        $payload = $request->input('qr_payload');
        
        if (!$payload) {
            return response()->json(['success' => false, 'message' => 'QR Code tidak terdeteksi.']);
        }

        $parts = explode('|', $payload);
        if (count($parts) !== 3 || $parts[0] !== 'qrlogin') {
            return response()->json(['success' => false, 'message' => 'Format QR Code tidak valid.']);
        }

        $userId = $parts[1];
        $hash = $parts[2];

        $user = \App\Models\User::find($userId);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.']);
        }

        if ($user->status === 'nonaktif') {
            return response()->json(['success' => false, 'message' => 'Akun Anda telah dinonaktifkan.']);
        }

        // Verify Hash
        $secret = config('app.key');
        $expectedHash = hash_hmac('sha256', $user->nik ?? $user->email, $secret);

        if (!hash_equals($expectedHash, $hash)) {
            return response()->json(['success' => false, 'message' => 'QR Code tidak valid atau telah dimanipulasi.']);
        }

        // Bypass everything and log in
        Auth::login($user);

        $request->session()->regenerate();
        Cache::put('user_session_' . $user->id, $request->session()->getId());
        LogAktivitas::catat('login', "User {$user->name} ({$user->jabatan}) login ke sistem via QR Code.");

        return response()->json(['success' => true, 'redirect' => route('dashboard')]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            LogAktivitas::catat('logout', "User {$user->name} logout dari sistem.");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
