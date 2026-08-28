<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - ERP Kopdes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #fff; border-radius: 20px; box-shadow: 0 30px 80px rgba(0,0,0,0.4); width: 100%; max-width: 480px; overflow: hidden; }
        .card-header { background: linear-gradient(135deg, #cc0000 0%, #8b0000 100%); padding: 28px 36px; }
        .card-header h1 { color: #fff; font-size: 20px; font-weight: 800; }
        .card-header p { color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 4px; line-height: 1.5; }
        .badge { display: inline-block; background: rgba(255,220,0,0.25); color: #fde68a; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; margin-bottom: 10px; }
        .card-body { padding: 30px 36px; }
        .info-box { background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 6px; padding: 12px 14px; font-size: 13px; color: #78350f; margin-bottom: 22px; line-height: 1.5; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px; }
        .input-wrap { position: relative; }
        input[type="password"], input[type="text"] { width: 100%; padding: 10px 40px 10px 13px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px; font-family: inherit; outline: none; transition: all 0.2s; color: #111827; }
        input:focus { border-color: #cc0000; box-shadow: 0 0 0 3px rgba(204,0,0,0.1); }
        .toggle-pw { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #9ca3af; background: none; border: none; }
        .strength-bar { height: 4px; border-radius: 2px; background: #f3f4f6; margin-top: 6px; overflow: hidden; }
        .strength-fill { height: 100%; border-radius: 2px; transition: width 0.3s, background 0.3s; width: 0; }
        .rules-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 16px; background: #f9fafb; border-radius: 8px; padding: 10px 14px; margin: 6px 0 14px; }
        .rule { display: flex; align-items: center; gap: 5px; font-size: 11px; color: #9ca3af; transition: color 0.2s; }
        .rule.valid { color: #16a34a; }
        .rule .dot { width: 5px; height: 5px; border-radius: 50%; background: #d1d5db; flex-shrink: 0; transition: background 0.2s; }
        .rule.valid .dot { background: #16a34a; }
        .divider { border: none; border-top: 1px solid #f3f4f6; margin: 20px 0; }
        .btn { display: block; width: 100%; padding: 12px; font-size: 14px; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: all 0.2s; font-family: inherit; }
        .btn-primary { background: linear-gradient(135deg, #cc0000, #8b0000); color: #fff; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(204,0,0,0.4); }
        .btn-secondary { background: #f3f4f6; color: #374151; margin-top: 8px; }
        .btn-secondary:hover { background: #e5e7eb; }
        .error-list { background: #fef2f2; border-left: 4px solid #dc2626; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; }
        .error-list li { font-size: 12px; color: #991b1b; margin-left: 14px; }
        .method-section { display: none; }
        .method-section.active { display: block; }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <div class="badge">🔒 Wajib Ganti Password</div>
        <h1>Buat Password Baru</h1>
        <p>Akun Anda memerlukan password baru yang kuat sebelum dapat menggunakan sistem.</p>
    </div>
    <div class="card-body">
        @if($errors->any())
        <ul class="error-list">
            @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
        @endif

        <div class="info-box">
            ⚡ Gunakan password yang kuat dengan kombinasi huruf, angka, dan simbol agar akun Anda terlindungi.
        </div>

        <form method="POST" action="{{ route('password.force.update') }}">
            @csrf
            <div class="form-group">
                <label>Email Pemulihan (Opsional)</label>
                <input type="email" name="recovery_email"
                    value="{{ old('recovery_email', auth()->user()->recovery_email) }}"
                    placeholder="email.pemulihan@contoh.com">
                <p style="font-size:11px;color:#9ca3af;margin-top:4px;">Digunakan jika Anda lupa password dan membutuhkan OTP alternatif.</p>
            </div>

            <hr class="divider">

            <div class="form-group">
                <label>Password Baru</label>
                <div class="input-wrap">
                    <input type="password" name="password" id="pw" placeholder="Buat password kuat..." autocomplete="new-password" required>
                    <button type="button" class="toggle-pw" onclick="togglePw('pw')">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <div class="strength-bar"><div class="strength-fill" id="sBar"></div></div>
                <div class="rules-grid">
                    <div class="rule" id="rule-len"><div class="dot"></div>Min 8 karakter</div>
                    <div class="rule" id="rule-upper"><div class="dot"></div>Huruf besar (A-Z)</div>
                    <div class="rule" id="rule-lower"><div class="dot"></div>Huruf kecil (a-z)</div>
                    <div class="rule" id="rule-num"><div class="dot"></div>Angka (0-9)</div>
                    <div class="rule" id="rule-sym"><div class="dot"></div>Simbol (@#$! dll)</div>
                </div>
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <div class="input-wrap">
                    <input type="password" name="password_confirmation" id="pwc" placeholder="Ulangi password baru..." required>
                    <button type="button" class="toggle-pw" onclick="togglePw('pwc')">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan dan Masuk ke Dashboard →</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="margin-top:8px;">
            @csrf
            <button type="submit" class="btn btn-secondary">Batalkan dan Logout</button>
        </form>
    </div>
</div>
<script>
function togglePw(id) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}
document.getElementById('pw').addEventListener('input', function () {
    const v = this.value;
    const rules = {
        'rule-len': v.length >= 8, 'rule-upper': /[A-Z]/.test(v),
        'rule-lower': /[a-z]/.test(v), 'rule-num': /[0-9]/.test(v),
        'rule-sym': /[^A-Za-z0-9]/.test(v),
    };
    let score = 0;
    Object.entries(rules).forEach(([id, ok]) => {
        document.getElementById(id)?.classList.toggle('valid', ok);
        if (ok) score++;
    });
    const bar = document.getElementById('sBar');
    const colors = ['', '#ef4444','#f97316','#f59e0b','#22c55e','#16a34a'];
    bar.style.width = (score/5*100)+'%';
    bar.style.background = colors[score] || '#f3f4f6';
});
</script>
</body>
</html>
