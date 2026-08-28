<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Baru - ERP Kopdes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #fff; border-radius: 20px; box-shadow: 0 30px 80px rgba(0,0,0,0.4); width: 100%; max-width: 440px; overflow: hidden; }
        .card-header { background: linear-gradient(135deg, #cc0000 0%, #8b0000 100%); padding: 28px 36px; text-align: center; }
        .card-header h1 { color: #fff; font-size: 20px; font-weight: 800; }
        .card-header p { color: rgba(255,255,255,0.75); font-size: 12px; margin-top: 3px; }
        .step-bar { display: flex; gap: 0; border-bottom: 1px solid #f0f0f0; }
        .step-bar .step { flex: 1; padding: 10px 4px; text-align: center; font-size: 11px; font-weight: 600; color: #d1d5db; }
        .step-bar .step.active { color: #cc0000; border-bottom: 2px solid #cc0000; }
        .step-bar .step.done { color: #16a34a; }
        .card-body { padding: 28px 32px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .input-wrap { position: relative; }
        input[type="password"], input[type="text"] { width: 100%; padding: 11px 40px 11px 14px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px; font-family: inherit; outline: none; transition: all 0.2s; color: #111827; }
        input:focus { border-color: #cc0000; box-shadow: 0 0 0 3px rgba(204,0,0,0.1); }
        .toggle-pw { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #9ca3af; background: none; border: none; padding: 0; }
        .strength-bar { height: 4px; border-radius: 2px; background: #f3f4f6; margin-top: 8px; overflow: hidden; }
        .strength-fill { height: 100%; border-radius: 2px; transition: width 0.3s, background 0.3s; width: 0; }
        .strength-text { font-size: 11px; margin-top: 4px; }
        .rules { background: #f9fafb; border-radius: 8px; padding: 12px 14px; margin-bottom: 20px; }
        .rules p { font-size: 11px; color: #6b7280; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .rule { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #9ca3af; margin-bottom: 4px; transition: color 0.2s; }
        .rule.valid { color: #16a34a; }
        .rule .dot { width: 6px; height: 6px; border-radius: 50%; background: #d1d5db; flex-shrink: 0; transition: background 0.2s; }
        .rule.valid .dot { background: #16a34a; }
        .btn { display: block; width: 100%; padding: 13px; background: linear-gradient(135deg, #cc0000, #8b0000); color: #fff; font-size: 14px; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: all 0.2s; font-family: inherit; margin-top: 8px; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(204,0,0,0.4); }
        .error-list { background: #fef2f2; border-left: 4px solid #dc2626; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; }
        .error-list li { font-size: 12px; color: #991b1b; margin-left: 14px; }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <h1>Buat Password Baru</h1>
        <p>ERP Koperasi Desa — Reset Password</p>
    </div>
    <div class="step-bar">
        <div class="step done">1. Email</div>
        <div class="step done">2. Metode</div>
        <div class="step done">3. Verifikasi</div>
        <div class="step active">4. Reset</div>
    </div>
    <div class="card-body">
        @if($errors->any())
        <ul class="error-list">
            @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
        @endif

        <div class="rules">
            <p>Ketentuan Password</p>
            <div class="rule" id="rule-len"><div class="dot"></div>Minimal 8 karakter</div>
            <div class="rule" id="rule-upper"><div class="dot"></div>Huruf kapital (A-Z)</div>
            <div class="rule" id="rule-lower"><div class="dot"></div>Huruf kecil (a-z)</div>
            <div class="rule" id="rule-num"><div class="dot"></div>Angka (0-9)</div>
            <div class="rule" id="rule-sym"><div class="dot"></div>Simbol (@, #, $, !, dll)</div>
        </div>

        <form method="POST" action="{{ route('password.reset.submit') }}">
            @csrf
            <div class="form-group">
                <label>Password Baru</label>
                <div class="input-wrap">
                    <input type="password" name="password" id="pw" placeholder="Buat password kuat..." autocomplete="new-password" required>
                    <button type="button" class="toggle-pw" onclick="togglePw('pw', this)">
                        <svg id="pw-eye" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <div class="strength-bar"><div class="strength-fill" id="sBar"></div></div>
                <div class="strength-text" id="sText"></div>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <div class="input-wrap">
                    <input type="password" name="password_confirmation" id="pwc" placeholder="Ulangi password..." required>
                    <button type="button" class="toggle-pw" onclick="togglePw('pwc', this)">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn">Simpan Password Baru →</button>
        </form>
    </div>
</div>
<script>
function togglePw(id, btn) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}

document.getElementById('pw').addEventListener('input', function () {
    const v = this.value;
    const rules = {
        'rule-len':   v.length >= 8,
        'rule-upper': /[A-Z]/.test(v),
        'rule-lower': /[a-z]/.test(v),
        'rule-num':   /[0-9]/.test(v),
        'rule-sym':   /[^A-Za-z0-9]/.test(v),
    };
    let score = 0;
    Object.entries(rules).forEach(([id, ok]) => {
        document.getElementById(id)?.classList.toggle('valid', ok);
        if (ok) score++;
    });
    const bar = document.getElementById('sBar');
    const txt = document.getElementById('sText');
    const pct = (score / 5) * 100;
    bar.style.width = pct + '%';
    if (score < 2)      { bar.style.background = '#ef4444'; txt.textContent = 'Sangat Lemah'; txt.style.color = '#ef4444'; }
    else if (score < 3) { bar.style.background = '#f97316'; txt.textContent = 'Lemah'; txt.style.color = '#f97316'; }
    else if (score < 4) { bar.style.background = '#f59e0b'; txt.textContent = 'Cukup'; txt.style.color = '#f59e0b'; }
    else if (score < 5) { bar.style.background = '#22c55e'; txt.textContent = 'Kuat'; txt.style.color = '#22c55e'; }
    else                { bar.style.background = '#16a34a'; txt.textContent = '✓ Sangat Kuat'; txt.style.color = '#16a34a'; }
});
</script>
</body>
</html>
