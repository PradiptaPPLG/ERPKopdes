<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - ERP Kopdes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #fff; border-radius: 20px; box-shadow: 0 30px 80px rgba(0,0,0,0.4); width: 100%; max-width: 440px; overflow: hidden; }
        .card-header { background: linear-gradient(135deg, #cc0000 0%, #8b0000 100%); padding: 32px 36px 28px; text-align: center; }
        .card-header .icon { width: 64px; height: 64px; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; }
        .card-header h1 { color: #fff; font-size: 22px; font-weight: 800; }
        .card-header p { color: rgba(255,255,255,0.75); font-size: 13px; margin-top: 4px; }
        .card-body { padding: 32px 36px; }
        .alert { padding: 12px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; }
        .alert-error { background: #fef2f2; border-left: 4px solid #dc2626; color: #991b1b; }
        .alert-success { background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        input { width: 100%; padding: 11px 14px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px; font-family: inherit; outline: none; transition: all 0.2s; color: #111827; }
        input:focus { border-color: #cc0000; box-shadow: 0 0 0 3px rgba(204,0,0,0.1); }
        .btn { display: block; width: 100%; padding: 13px; background: linear-gradient(135deg, #cc0000, #8b0000); color: #fff; font-size: 14px; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: all 0.2s; font-family: inherit; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(204,0,0,0.4); }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { font-size: 13px; color: #cc0000; text-decoration: none; font-weight: 500; }
        .back-link a:hover { text-decoration: underline; }
        .desc { font-size: 13px; color: #6b7280; line-height: 1.6; margin-bottom: 24px; }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <div class="icon">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>
        <h1>Lupa Password?</h1>
        <p>ERP Koperasi Desa — Pemulihan Akun</p>
    </div>
    <div class="card-body">
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <p class="desc">Masukkan alamat email akun Anda. Kami akan mengirimkan kode OTP verifikasi untuk memulai proses reset password.</p>

        <form method="POST" action="{{ route('password.forgot.submit') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Akun ERP</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    placeholder="email@karyawan.id" required autofocus>
                @error('email')
                <p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn">Lanjutkan →</button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">← Kembali ke halaman login</a>
        </div>
    </div>
</div>
</body>
</html>
