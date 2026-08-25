@extends('layouts.auth')
@section('title', 'Verifikasi 2FA')

@section('content')
<div style="display:flex;align-items:stretch;width:500px;min-height:480px;background:#fff;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.08);overflow:hidden;margin:40px auto;flex-direction:column;">
    
    {{-- Header Banner --}}
    <div style="background:#cc0000;padding:40px 30px;text-align:center;color:#fff;position:relative;">
        <div style="width:70px;height:70px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;box-shadow:0 8px 24px rgba(0,0,0,0.1);">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h2 style="font-size:18px;font-weight:800;margin:0 0 4px;letter-spacing:-0.2px;">Keamanan Dua Langkah</h2>
        <p style="font-size:12px;color:rgba(255,255,255,0.8);margin:0;">Otentikasi Dua Faktor (2FA) Aktif</p>
    </div>

    {{-- Form Body --}}
    <div style="padding:40px 35px;display:flex;flex-direction:column;flex:1;justify-content:center;">
        
        <p style="font-size:13px;color:#475569;line-height:1.6;text-align:center;margin-bottom:28px;">
            Buka aplikasi <strong>Google Authenticator</strong> atau aplikasi TOTP lainnya di ponsel Anda untuk melihat kode verifikasi 6 digit saat ini.
        </p>

        {{-- Alerts --}}
        @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:20px;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('login.2fa.post') }}" id="challengeForm">
            @csrf
            
            {{-- OTP Input Group --}}
            <div class="form-group" style="margin-bottom:24px;text-align:center;">
                <label class="form-label" style="text-align:center;display:block;margin-bottom:12px;font-weight:700;color:#1e293b;letter-spacing:0.5px;">MASUKKAN 6 DIGIT KODE OTP</label>
                
                <input type="text" name="code" id="otpCode" 
                       class="form-control" 
                       style="font-size:24px;font-weight:700;letter-spacing:12px;text-align:center;padding:12px;border:2px solid #cbd5e1;border-radius:8px;max-width:240px;margin:0 auto;text-transform:uppercase;"
                       maxlength="6" autocomplete="one-time-code" required autofocus placeholder="000000">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:13px;background:#cc0000;border:none;">
                Verifikasi & Masuk
            </button>
        </form>

        <div style="text-align:center;margin-top:28px;">
            <a href="{{ route('login') }}" style="font-size:12px;color:#64748b;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Halaman Login
            </a>
        </div>
    </div>
</div>

<script>
    // Validasi agar hanya angka yang bisa dimasukkan
    document.getElementById('otpCode').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endsection
