<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $request->validate([
            'email'                => ['required', 'email'],
            'password'             => ['required'],
            'g-recaptcha-response' => ['required'],
        ], [
            'email.required'                => 'Email harus diisi.',
            'email.email'                   => 'Format email tidak valid.',
            'password.required'             => 'Password harus diisi.',
            'g-recaptcha-response.required' => 'Verifikasi captcha wajib diisi.',
        ]);

        // Verifikasi token reCAPTCHA ke API Google
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

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
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

        $request->session()->regenerate();

        LogAktivitas::catat('login', "User {$user->name} ({$user->jabatan}) login ke sistem.");

        return redirect()->intended(route('dashboard'));
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
