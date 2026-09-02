@extends('layouts.app')
@section('title', 'Ganti Password Wajib')

@section('content')
<style>
    .force-pw-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .force-pw-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        width: 100%;
        max-width: 480px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .force-pw-header {
        background: #f8fafc;
        padding: 24px 30px;
        border-bottom: 1px solid #e2e8f0;
    }
    .force-pw-header h1 {
        color: #0f172a;
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }
    .force-pw-header p {
        color: #64748b;
        font-size: 13px;
        margin-top: 6px;
        line-height: 1.5;
    }
    .force-pw-body {
        padding: 24px 30px;
    }
    .fpw-info {
        background: #eff6ff;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 13px;
        color: #1e40af;
        margin-bottom: 20px;
        line-height: 1.5;
    }
    .fpw-group {
        margin-bottom: 16px;
    }
    .fpw-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
    }
    .fpw-input-wrap {
        position: relative;
    }
    .fpw-input-wrap input {
        width: 100%;
        padding: 10px 40px 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: all 0.2s;
        color: #0f172a;
    }
    .fpw-input-wrap input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }
    .fpw-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #94a3b8;
        background: none;
        border: none;
    }
    .fpw-strength {
        height: 4px;
        border-radius: 2px;
        background: #f1f5f9;
        margin-top: 8px;
        overflow: hidden;
    }
    .fpw-fill {
        height: 100%;
        border-radius: 2px;
        transition: width 0.3s, background 0.3s;
        width: 0;
    }
    .fpw-rules {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px 12px;
        background: #f8fafc;
        border-radius: 8px;
        padding: 12px 14px;
        margin: 8px 0 16px;
        border: 1px solid #f1f5f9;
    }
    .fpw-rule {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: #94a3b8;
    }
    .fpw-rule.valid { color: #10b981; }
    .fpw-rule .dot { width: 4px; height: 4px; border-radius: 50%; background: #cbd5e1; flex-shrink: 0; }
    .fpw-rule.valid .dot { background: #10b981; }
    .fpw-btn {
        display: block;
        width: 100%;
        padding: 12px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }
    .fpw-btn-primary {
        background: #0f172a;
        color: #fff;
    }
    .fpw-btn-primary:hover {
        background: #1e293b;
    }
    .fpw-btn-secondary {
        background: #f1f5f9;
        color: #475569;
        margin-top: 10px;
    }
    .fpw-btn-secondary:hover {
        background: #e2e8f0;
    }
    .fpw-errors {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 16px;
    }
    .fpw-errors li {
        font-size: 12px;
        color: #b91c1c;
        margin-left: 14px;
    }
</style>

<div class="force-pw-overlay">
    <div class="force-pw-card">
        <div class="force-pw-header">
            <h1>Buat Password Baru</h1>
            <p>Akun Anda memerlukan password baru yang kuat sebelum dapat melanjutkan ke sistem.</p>
        </div>
        <div class="force-pw-body">
            @if($errors->any())
            <ul class="fpw-errors">
                @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
                @endforeach
            </ul>
            @endif

            <div class="fpw-info">
                Gunakan kombinasi huruf, angka, dan simbol untuk memastikan akun Anda terlindungi dengan baik.
            </div>

            <form method="POST" action="{{ route('password.force.update') }}">
                @csrf
                <div class="fpw-group">
                    <label>Email Pemulihan (Opsional)</label>
                    <div class="fpw-input-wrap">
                        <input type="email" name="recovery_email" value="{{ old('recovery_email', auth()->user()->recovery_email) }}" placeholder="email.pemulihan@contoh.com">
                    </div>
                    <p style="font-size:11px;color:#94a3b8;margin-top:6px;">Alternatif penerima OTP saat lupa password.</p>
                </div>

                <div style="height:1px;background:#e2e8f0;margin:20px 0;"></div>

                <div class="fpw-group">
                    <label>Password Baru</label>
                    <div class="fpw-input-wrap">
                        <input type="password" name="password" id="pw" placeholder="Buat password kuat..." autocomplete="new-password" required>
                        <button type="button" class="fpw-toggle" onclick="togglePw('pw')">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <div class="fpw-strength"><div class="fpw-fill" id="sBar"></div></div>
                    <div class="fpw-rules">
                        <div class="fpw-rule" id="rule-len"><div class="dot"></div>Min 8 karakter</div>
                        <div class="fpw-rule" id="rule-upper"><div class="dot"></div>Huruf besar (A-Z)</div>
                        <div class="fpw-rule" id="rule-lower"><div class="dot"></div>Huruf kecil (a-z)</div>
                        <div class="fpw-rule" id="rule-num"><div class="dot"></div>Angka (0-9)</div>
                        <div class="fpw-rule" id="rule-sym"><div class="dot"></div>Simbol (@#$!)</div>
                    </div>
                </div>

                <div class="fpw-group">
                    <label>Konfirmasi Password</label>
                    <div class="fpw-input-wrap">
                        <input type="password" name="password_confirmation" id="pwc" placeholder="Ulangi password baru..." required>
                        <button type="button" class="fpw-toggle" onclick="togglePw('pwc')">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="fpw-btn fpw-btn-primary">Simpan dan Masuk ke Dashboard</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="fpw-btn fpw-btn-secondary">Batalkan dan Logout</button>
            </form>
        </div>
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
        const el = document.getElementById(id);
        if (el) el.classList.toggle('valid', ok);
        if (ok) score++;
    });
    const bar = document.getElementById('sBar');
    const colors = ['', '#ef4444','#f97316','#f59e0b','#10b981','#10b981'];
    if (bar) {
        bar.style.width = (score/5*100)+'%';
        bar.style.background = colors[score] || '#f1f5f9';
    }
});
</script>
@endsection
