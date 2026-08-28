<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Metode Verifikasi - ERP Kopdes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #fff; border-radius: 20px; box-shadow: 0 30px 80px rgba(0,0,0,0.4); width: 100%; max-width: 460px; overflow: hidden; }
        .card-header { background: linear-gradient(135deg, #cc0000 0%, #8b0000 100%); padding: 28px 36px; text-align: center; }
        .card-header h1 { color: #fff; font-size: 20px; font-weight: 800; }
        .card-header p { color: rgba(255,255,255,0.75); font-size: 12px; margin-top: 3px; }
        .step-bar { display: flex; gap: 0; border-bottom: 1px solid #f0f0f0; }
        .step-bar .step { flex: 1; padding: 10px 4px; text-align: center; font-size: 11px; font-weight: 600; color: #d1d5db; }
        .step-bar .step.active { color: #cc0000; border-bottom: 2px solid #cc0000; }
        .card-body { padding: 28px 32px; }
        .desc { font-size: 13px; color: #6b7280; margin-bottom: 22px; line-height: 1.6; }
        .method-option { display: flex; align-items: flex-start; gap: 14px; padding: 16px 18px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.2s; margin-bottom: 12px; }
        .method-option:hover, .method-option.selected { border-color: #cc0000; background: #fff5f5; }
        .method-option input[type="radio"] { display: none; }
        .method-icon { width: 40px; height: 40px; border-radius: 10px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background 0.2s; }
        .method-option.selected .method-icon { background: #fef2f2; }
        .method-label { flex: 1; }
        .method-label strong { display: block; font-size: 14px; color: #111827; margin-bottom: 2px; }
        .method-label span { font-size: 12px; color: #6b7280; }
        .method-label .email-masked { font-family: monospace; color: #374151; font-weight: 600; }
        .badge-disabled { background: #f3f4f6; color: #9ca3af; font-size: 10px; padding: 2px 6px; border-radius: 4px; margin-left: 6px; }
        .btn { display: block; width: 100%; padding: 13px; background: linear-gradient(135deg, #cc0000, #8b0000); color: #fff; font-size: 14px; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: all 0.2s; font-family: inherit; margin-top: 20px; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(204,0,0,0.4); }
        .back-link { text-align: center; margin-top: 16px; }
        .back-link a { font-size: 13px; color: #cc0000; text-decoration: none; font-weight: 500; }
        .check-icon { display: none; width: 18px; height: 18px; }
        .method-option.selected .check-icon { display: block; }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <h1>Pilih Metode Verifikasi</h1>
        <p>ERP Koperasi Desa — Pemulihan Akun</p>
    </div>
    <div class="step-bar">
        <div class="step">1. Email</div>
        <div class="step active">2. Metode OTP</div>
        <div class="step">3. Verifikasi</div>
        <div class="step">4. Reset</div>
    </div>
    <div class="card-body">
        @if(session('error'))
        <div style="padding:12px 14px;background:#fef2f2;border-left:4px solid #dc2626;border-radius:8px;font-size:13px;color:#991b1b;margin-bottom:16px;">{{ session('error') }}</div>
        @endif

        <p class="desc">Pilih ke mana kami harus mengirimkan kode OTP 6 digit untuk verifikasi identitas Anda.</p>

        <form method="POST" action="{{ route('password.otp.send') }}" id="methodForm">
            @csrf
            <label class="method-option" id="opt-primary" onclick="selectMethod('primary')">
                <input type="radio" name="method" value="primary" checked>
                <div class="method-icon">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#cc0000" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="method-label">
                    <strong>Opsi 1: Email Utama Akun</strong>
                    <span>Kirim kode ke <span class="email-masked">{{ $maskedPrimary }}</span></span>
                </div>
                <svg class="check-icon" viewBox="0 0 24 24" fill="#cc0000">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </label>

            @if($maskedRecovery)
            <label class="method-option" id="opt-recovery" onclick="selectMethod('recovery')">
                <input type="radio" name="method" value="recovery">
                <div class="method-icon">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#0284c7" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="method-label">
                    <strong>Opsi 2: Email Pemulihan</strong>
                    <span>Kirim kode ke <span class="email-masked">{{ $maskedRecovery }}</span></span>
                </div>
                <svg class="check-icon" viewBox="0 0 24 24" fill="#cc0000">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </label>
            @else
            <div class="method-option" style="opacity:0.5;cursor:not-allowed;">
                <div class="method-icon">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="method-label">
                    <strong>Opsi 2: Email Pemulihan <span class="badge-disabled">Belum diatur</span></strong>
                    <span>Email pemulihan belum terdaftar untuk akun ini.</span>
                </div>
            </div>
            @endif

            <button type="submit" class="btn">Kirim Kode OTP →</button>
        </form>

        <div class="back-link">
            <a href="{{ route('password.forgot') }}">← Ganti email akun</a>
        </div>
    </div>
</div>
<script>
function selectMethod(m) {
    document.getElementById('opt-primary')?.classList.remove('selected');
    document.getElementById('opt-recovery')?.classList.remove('selected');
    document.getElementById('opt-' + m)?.classList.add('selected');
    document.querySelector(`input[value="${m}"]`).checked = true;
}
selectMethod('primary');
</script>
</body>
</html>
