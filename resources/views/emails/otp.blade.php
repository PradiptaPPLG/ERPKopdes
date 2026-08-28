<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP ERP Kopdes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f5f5; color: #333; }
        .wrapper { max-width: 560px; margin: 32px auto; }
        .header  { background: linear-gradient(135deg, #cc0000 0%, #8b0000 100%); padding: 36px 40px; border-radius: 12px 12px 0 0; text-align: center; }
        .header-icon { width: 60px; height: 60px; background: rgba(255,255,255,0.15); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px; }
        .header h1 { color: #fff; font-size: 22px; font-weight: 700; letter-spacing: 0.5px; }
        .header p  { color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 4px; }
        .body    { background: #fff; padding: 36px 40px; border-left: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; }
        .greeting { font-size: 16px; color: #111827; margin-bottom: 16px; }
        .purpose-text { font-size: 14px; color: #6b7280; line-height: 1.6; margin-bottom: 28px; }
        .otp-box { background: linear-gradient(135deg, #fff5f5 0%, #fef2f2 100%); border: 2px dashed #cc0000; border-radius: 12px; padding: 28px; text-align: center; margin: 20px 0 28px; }
        .otp-label { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
        .otp-code { font-size: 42px; font-weight: 900; color: #cc0000; letter-spacing: 10px; font-family: 'Courier New', monospace; }
        .otp-expire { font-size: 12px; color: #ef4444; margin-top: 10px; font-weight: 600; }
        .warning-box { background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 4px; padding: 14px 16px; margin-bottom: 24px; }
        .warning-box p { font-size: 13px; color: #78350f; line-height: 1.5; }
        .warning-box strong { color: #92400e; }
        .footer  { background: #f9fafb; padding: 20px 40px; border-radius: 0 0 12px 12px; border: 1px solid #e5e7eb; border-top: none; text-align: center; }
        .footer p { font-size: 11px; color: #9ca3af; line-height: 1.6; }
        .footer strong { color: #6b7280; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="header-icon">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <h1>ERP Koperasi Desa</h1>
        <p>Sistem Manajemen Kehadiran Karyawan</p>
    </div>
    <div class="body">
        <p class="greeting">Halo, <strong>{{ $userName }}</strong>!</p>
        <p class="purpose-text">
            @if($purpose === 'change_password')
                Anda baru saja meminta untuk <strong>mengubah password</strong> akun ERP Kopdes Anda.
                Masukkan kode OTP berikut untuk melanjutkan proses verifikasi.
            @else
                Kami menerima permintaan <strong>reset password</strong> untuk akun ERP Kopdes Anda.
                Masukkan kode OTP berikut untuk memulai proses pemulihan akun Anda.
            @endif
        </p>

        <div class="otp-box">
            <div class="otp-label">Kode Verifikasi OTP</div>
            <div class="otp-code">{{ $otp }}</div>
            <div class="otp-expire">⏱ Berlaku selama 10 menit</div>
        </div>

        <div class="warning-box">
            <p>
                <strong>⚠ Peringatan Keamanan:</strong> Jangan bagikan kode ini kepada siapapun, termasuk petugas atau administrator sistem. Tim ERP Kopdes tidak pernah meminta kode OTP Anda.
            </p>
        </div>

        <p style="font-size:13px;color:#6b7280;line-height:1.6;">
            Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini. Akun Anda tetap aman dan tidak ada perubahan yang terjadi.
        </p>
    </div>
    <div class="footer">
        <p>Email ini dikirim otomatis dari sistem <strong>ERP Koperasi Desa</strong>.<br>
        Jangan balas email ini &mdash; email ini tidak terpantau.</p>
    </div>
</div>
</body>
</html>
