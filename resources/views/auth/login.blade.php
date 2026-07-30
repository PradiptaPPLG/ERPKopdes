@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div style="display:flex;width:960px;min-height:580px;background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.08),0 4px 12px rgba(0,0,0,0.04);overflow:hidden;margin:20px auto;">

    {{-- LEFT: Brand Panel --}}
    <div style="width:420px;background:#cc0000;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:60px 50px;position:relative;flex-shrink:0;">
        <div style="position:absolute;bottom:0;left:0;width:100%;height:4px;background:#fff;"></div>

        <div style="width:120px;height:120px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:28px;padding:18px;">
            <img src="{{ asset('images/logos/kopdes.png') }}" alt="Logo Kopdes"
                 style="width:100%;height:100%;object-fit:contain;"
                 onerror="this.style.display='none';this.parentElement.innerHTML='<span style=font-size:36px;font-weight:700;color:#cc0000>KOP</span>';">
        </div>

        <h1 style="font-size:22px;font-weight:700;color:#fff;text-align:center;line-height:1.3;margin-bottom:8px;letter-spacing:-0.3px;">
            Koperasi Desa<br>Maju Bersama
        </h1>
        <div style="width:50px;height:2px;background:rgba(255,255,255,0.5);margin:20px 0;"></div>
        <p style="font-size:13px;color:rgba(255,255,255,0.85);text-align:center;line-height:1.5;">
            Sistem Informasi Manajemen Terpadu
        </p>
        <p style="font-size:12px;color:rgba(255,255,255,0.7);text-align:center;margin-top:10px;line-height:1.6;">
            Melayani anggota dan masyarakat<br>dengan prinsip transparansi &amp; kekeluargaan
        </p>
    </div>

    {{-- RIGHT: Form Panel --}}
    <div style="flex:1;padding:60px 55px;display:flex;flex-direction:column;justify-content:center;">

        <div style="margin-bottom:32px;">
            <h2 style="font-size:20px;font-weight:700;color:#1a1a1a;margin-bottom:6px;letter-spacing:-0.2px;">Masuk ke Sistem</h2>
            <p style="font-size:13px;color:#666;">Silakan login menggunakan akun Anda</p>
        </div>

        {{-- Alerts --}}
        @if($errors->any())
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login.post') }}" id="loginForm">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label">Email <span class="required">*</span></label>
                <div style="position:relative;">
                    <span style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#999;pointer-events:none;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="form-control" style="padding-left:38px;"
                           placeholder="nama@kopdes.id" required autofocus>
                </div>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label">Password <span class="required">*</span></label>
                <div style="position:relative;">
                    <span style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#999;pointer-events:none;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </span>
                    <input type="password" name="password" id="password"
                           class="form-control" style="padding-left:38px;padding-right:40px;"
                           placeholder="Password" required>
                    <button type="button" id="togglePwd"
                            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#999;padding:0;"
                            onclick="togglePassword()">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Remember --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;font-size:12px;">
                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;color:#555;user-select:none;">
                    <input type="checkbox" name="remember" style="width:14px;height:14px;accent-color:#cc0000;"> Ingat saya
                </label>
                <a href="#" style="color:#cc0000;text-decoration:none;font-weight:600;"
                   onclick="alert('Hubungi Administrator:\n📞 081234567890\n📧 admin@kopdes.id'); return false;">
                    Lupa password?
                </a>
            </div>

            {{-- Submit --}}
            <button type="submit" id="btnLogin"
                    class="btn btn-primary" style="width:100%;justify-content:center;padding:11px;font-size:13px;">
                <span id="btnText">Masuk</span>
                <svg id="btnSpinner" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none;animation:spin 0.8s linear infinite;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>

        </form>

        <div style="text-align:center;margin-top:28px;font-size:11px;color:#999;">
            &copy; {{ date('Y') }} Koperasi Desa Maju Bersama &mdash; ERP v1.0
        </div>

        {{-- Demo hint (remove in production) --}}
        <div style="margin-top:16px;padding:12px;background:#f9fafb;border-radius:6px;border:1px dashed #e5e5e5;font-size:11px;color:#888;">
            <strong style="color:#555;">Demo login:</strong><br>
            admin@kopdes.id / admin123 &nbsp;|&nbsp;
            ketua@kopdes.id / ketua123<br>
            kasir1@kopdes.id / kasir123
        </div>
    </div>

</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
@media (max-width: 980px) {
    div[style*="width:960px"] { flex-direction:column; width:90%; max-width:420px; }
    div[style*="width:420px"] { width:100%; padding:35px 30px; }
    div[style*="flex:1;padding:60px"] { padding:35px 30px; }
}
</style>
<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    pwd.type = pwd.type === 'password' ? 'text' : 'password';
}
document.getElementById('loginForm').addEventListener('submit', function() {
    document.getElementById('btnText').style.opacity = '0';
    document.getElementById('btnSpinner').style.display = 'inline';
});
</script>
@endsection
