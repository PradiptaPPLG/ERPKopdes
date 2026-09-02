<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordController extends Controller
{
    // ── Step 1: Show email lookup form ───────────────────────────
    public function showEmailForm()
    {
        return view('auth.login', ['recoveryStep' => 'email']);
    }

    // ── Step 2: Verify the email, show OTP option chooser ────────
    public function submitEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'Email tidak ditemukan dalam sistem.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Mask emails for display
        $maskedPrimary  = $this->maskEmail($user->email);
        $maskedRecovery = $user->recovery_email
            ? $this->maskEmail($user->recovery_email)
            : null;

        // Store user ID in session for next steps
        session(['otp_user_id' => $user->id]);

        return view('auth.login', [
            'recoveryStep' => 'method',
            'maskedPrimary' => $maskedPrimary,
            'maskedRecovery' => $maskedRecovery,
            'user' => $user
        ]);
    }

    // ── Step 3: Send OTP to chosen destination ───────────────────
    public function sendOtp(Request $request)
    {
        $request->validate([
            'method' => ['required', 'in:primary,recovery'],
        ]);

        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('password.forgot')->with('error', 'Sesi tidak valid. Mulai ulang proses.');
        }

        $user = User::findOrFail($userId);

        if ($request->method === 'recovery') {
            if (!$user->recovery_email) {
                return back()->with('error', 'Akun ini tidak memiliki email pemulihan yang terdaftar.');
            }
            $destination = $user->recovery_email;
        } else {
            $destination = $user->email;
        }

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store in cache for 10 minutes
        Cache::put("otp_reset_{$userId}", [
            'code'      => $otp,
            'method'    => $request->method,
            'purpose'   => 'forgot_password',
        ], now()->addMinutes(10));

        // Send mail
        try {
            Mail::to($destination)->send(new SendOtpMail($otp, $user->name, 'forgot_password'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email OTP. Periksa konfigurasi email server. Error: ' . $e->getMessage());
        }

        session(['otp_method' => $request->method, 'otp_dest_masked' => $this->maskEmail($destination)]);

        return redirect()->route('password.otp.verify');
    }

    // ── Step 4: Show OTP verification form ───────────────────────
    public function showOtpForm()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('password.forgot');
        }

        $maskedDest = session('otp_dest_masked', '***@***.***');
        return view('auth.login', [
            'recoveryStep' => 'otp',
            'maskedDest' => $maskedDest
        ]);
    }

    // ── Step 5: Verify OTP code ───────────────────────────────────
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('password.forgot')->with('error', 'Sesi tidak valid. Mulai ulang.');
        }

        $cached = Cache::get("otp_reset_{$userId}");

        if (!$cached || $cached['code'] !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        // Mark OTP as verified
        Cache::forget("otp_reset_{$userId}");
        session(['otp_verified' => true, 'otp_reset_for' => $userId]);

        return redirect()->route('password.reset.form');
    }

    // ── Step 6: Show new password form ───────────────────────────
    public function showResetForm()
    {
        if (!session('otp_verified') || !session('otp_reset_for')) {
            return redirect()->route('password.forgot');
        }

        return view('auth.login', ['recoveryStep' => 'reset']);
    }

    // ── Step 7: Save new password ─────────────────────────────────
    public function resetPassword(Request $request)
    {
        if (!session('otp_verified') || !session('otp_reset_for')) {
            return redirect()->route('password.forgot')->with('error', 'Sesi tidak valid.');
        }

        $request->validate([
            'password' => [
                'required', 'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'password.min'        => 'Password minimal 8 karakter.',
            'password.mixed'      => 'Password harus mengandung huruf besar dan kecil.',
            'password.letters'    => 'Password harus mengandung setidaknya satu huruf.',
            'password.numbers'    => 'Password harus mengandung setidaknya satu angka.',
            'password.symbols'    => 'Password harus mengandung setidaknya satu simbol (contoh: @, #, $, !).',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
        ]);

        $userId = session('otp_reset_for');
        $user   = User::findOrFail($userId);

        $user->update([
            'password'             => Hash::make($request->password),
            'need_password_change' => false,
        ]);

        // Clear all OTP session data
        session()->forget(['otp_user_id', 'otp_method', 'otp_dest_masked', 'otp_verified', 'otp_reset_for']);

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }

    // ── Forced first-login password change ───────────────────────
    public function showForceChangeForm()
    {
        return view('auth.force-change-password');
    }

    public function forceChangePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required', 'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.mixed'     => 'Password harus mengandung huruf besar dan kecil.',
            'password.letters'   => 'Password harus mengandung setidaknya satu huruf.',
            'password.numbers'   => 'Password harus mengandung setidaknya satu angka.',
            'password.symbols'   => 'Password harus mengandung setidaknya satu simbol (contoh: @, #, $, !).',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = auth()->user();

        $user->update([
            'password'             => Hash::make($request->password),
            'need_password_change' => false,
            'recovery_email'       => $request->filled('recovery_email')
                ? $request->recovery_email
                : $user->recovery_email,
        ]);

        return redirect()->route('dashboard')->with('success', 'Password berhasil diperbarui! Selamat menggunakan ERP Kopdes.');
    }

    // ── Profile: request OTP to change password ───────────────────
    public function profileSendOtp(Request $request)
    {
        $request->validate([
            'method' => ['required', 'in:primary,recovery'],
        ]);

        $user = auth()->user();

        if ($request->method === 'recovery') {
            if (!$user->recovery_email) {
                return back()->with('error', 'Anda belum memiliki email pemulihan. Tambahkan dulu di pengaturan profil.');
            }
            $destination = $user->recovery_email;
        } else {
            $destination = $user->email;
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put("otp_change_{$user->id}", [
            'code'    => $otp,
            'purpose' => 'change_password',
        ], now()->addMinutes(10));

        try {
            Mail::to($destination)->send(new SendOtpMail($otp, $user->name, 'change_password'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email OTP: ' . $e->getMessage());
        }

        session(['profile_otp_sent' => true, 'profile_otp_dest' => $this->maskEmail($destination)]);

        return redirect()->route('profile.change-password');
    }

    // ── Profile: show change password form (after OTP sent) ──────
    public function showProfileChangeForm()
    {
        $user         = auth()->user();
        $otpSent      = session('profile_otp_sent');
        $maskedPrimary  = $this->maskEmail($user->email);
        $maskedRecovery = $user->recovery_email ? $this->maskEmail($user->recovery_email) : null;

        return view('profile.change-password', compact('user', 'otpSent', 'maskedPrimary', 'maskedRecovery'));
    }

    // ── Profile: verify OTP and save new password ─────────────────
    public function profileUpdatePassword(Request $request)
    {
        $request->validate([
            'otp'      => ['required', 'digits:6'],
            'password' => [
                'required', 'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'otp.required'       => 'Kode OTP wajib diisi.',
            'otp.digits'         => 'Kode OTP harus 6 digit angka.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.mixed'     => 'Password harus mengandung huruf besar dan kecil.',
            'password.letters'   => 'Password harus mengandung setidaknya satu huruf.',
            'password.numbers'   => 'Password harus mengandung setidaknya satu angka.',
            'password.symbols'   => 'Password harus mengandung setidaknya satu simbol.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user   = auth()->user();
        $cached = Cache::get("otp_change_{$user->id}");

        if (!$cached || $cached['code'] !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.'])->withInput();
        }

        Cache::forget("otp_change_{$user->id}");

        $user->update([
            'password'             => Hash::make($request->password),
            'need_password_change' => false,
        ]);

        session()->forget(['profile_otp_sent', 'profile_otp_dest']);

        return redirect()->route('profile.edit')->with('success', 'Password berhasil diubah!');
    }

    // ── Helper: Mask email for display ───────────────────────────
    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email);
        $maskedLocal = substr($local, 0, 2) . str_repeat('*', max(0, strlen($local) - 2));
        return "{$maskedLocal}@{$domain}";
    }
}
