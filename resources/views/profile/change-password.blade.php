@extends('layouts.app')
@section('title', 'Ubah Password - Profil Saya')
@section('page-title', 'Ubah Password')
@section('breadcrumb', 'Profil › Keamanan › Ubah Password')

@section('content')
<div style="max-width:600px;margin:0 auto;display:flex;flex-direction:column;gap:20px;">

    @if(session('error'))
    <div style="padding:12px 16px;background:#fef2f2;border-left:4px solid #dc2626;border-radius:8px;font-size:13px;color:#991b1b;font-weight:600;">
        {{ session('error') }}
    </div>
    @endif

    {{-- Step 1: Choose method & send OTP (shown when OTP not yet sent) --}}
    @if(!$otpSent)
    <div class="card">
        <div class="card-header">
            <span class="card-title">Langkah 1: Pilih Metode Verifikasi</span>
        </div>
        <div class="card-body" style="padding:24px;">
            <p style="font-size:13px;color:#6b7280;line-height:1.6;margin-bottom:22px;">
                Sebelum mengubah password, kami perlu memverifikasi identitas Anda. Pilih ke mana kode OTP akan dikirim.
            </p>

            <form method="POST" action="{{ route('profile.password.otp.send') }}" id="methodForm">
                @csrf
                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:22px;">
                    <label style="display:flex;align-items:flex-start;gap:14px;padding:14px 16px;border:2px solid #e5e7eb;border-radius:10px;cursor:pointer;transition:all 0.2s;" class="opt-card" id="opt-primary" onclick="selectOpt('primary')">
                        <input type="radio" name="method" value="primary" style="display:none" checked>
                        <div style="width:38px;height:38px;border-radius:8px;background:#fff5f5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#cc0000" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#111827;">Email Utama Akun</div>
                            <div style="font-size:12px;color:#6b7280;margin-top:2px;">Kode OTP dikirim ke <strong>{{ $maskedPrimary }}</strong></div>
                        </div>
                    </label>

                    @if($maskedRecovery)
                    <label style="display:flex;align-items:flex-start;gap:14px;padding:14px 16px;border:2px solid #e5e7eb;border-radius:10px;cursor:pointer;transition:all 0.2s;" class="opt-card" id="opt-recovery" onclick="selectOpt('recovery')">
                        <input type="radio" name="method" value="recovery" style="display:none">
                        <div style="width:38px;height:38px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#0284c7" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#111827;">Email Pemulihan</div>
                            <div style="font-size:12px;color:#6b7280;margin-top:2px;">Kode OTP dikirim ke <strong>{{ $maskedRecovery }}</strong></div>
                        </div>
                    </label>
                    @else
                    <div style="display:flex;align-items:flex-start;gap:14px;padding:14px 16px;border:2px solid #f3f4f6;border-radius:10px;opacity:0.5;">
                        <div style="width:38px;height:38px;border-radius:8px;background:#f9fafb;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#9ca3af;">Email Pemulihan <span style="background:#f3f4f6;color:#9ca3af;font-size:10px;padding:2px 6px;border-radius:4px;margin-left:4px;">Belum diatur</span></div>
                            <div style="font-size:12px;color:#9ca3af;margin-top:2px;">Tambahkan email pemulihan di halaman edit profil.</div>
                        </div>
                    </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Kirim Kode OTP
                </button>
            </form>
        </div>
    </div>

    @else
    {{-- Step 2: OTP input + new password --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Langkah 2: Verifikasi OTP & Password Baru</span>
            <span class="badge badge-success">OTP Terkirim</span>
        </div>
        <div class="card-body" style="padding:24px;">
            <div style="background:#f0fdf4;border-left:4px solid #22c55e;border-radius:6px;padding:10px 14px;font-size:13px;color:#166534;margin-bottom:22px;">
                Kode OTP 6 digit telah dikirim ke <strong>{{ session('profile_otp_dest') }}</strong>. Berlaku 10 menit.
            </div>

            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                @if($errors->has('otp'))
                <div style="padding:10px 14px;background:#fef2f2;border-left:4px solid #dc2626;border-radius:6px;font-size:12px;color:#991b1b;margin-bottom:16px;">
                    {{ $errors->first('otp') }}
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label" style="text-transform:uppercase;letter-spacing:0.5px;font-size:11px;">Kode OTP (6 Digit)</label>
                    <div style="display:flex;gap:8px;justify-content:center;margin-bottom:4px;">
                        <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" id="d1" style="width:46px;height:52px;text-align:center;font-size:22px;font-weight:900;font-family:monospace;border:2px solid #d1d5db;border-radius:8px;color:#cc0000;outline:none;transition:all 0.2s;">
                        <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" id="d2" style="width:46px;height:52px;text-align:center;font-size:22px;font-weight:900;font-family:monospace;border:2px solid #d1d5db;border-radius:8px;color:#cc0000;outline:none;transition:all 0.2s;">
                        <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" id="d3" style="width:46px;height:52px;text-align:center;font-size:22px;font-weight:900;font-family:monospace;border:2px solid #d1d5db;border-radius:8px;color:#cc0000;outline:none;transition:all 0.2s;">
                        <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" id="d4" style="width:46px;height:52px;text-align:center;font-size:22px;font-weight:900;font-family:monospace;border:2px solid #d1d5db;border-radius:8px;color:#cc0000;outline:none;transition:all 0.2s;">
                        <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" id="d5" style="width:46px;height:52px;text-align:center;font-size:22px;font-weight:900;font-family:monospace;border:2px solid #d1d5db;border-radius:8px;color:#cc0000;outline:none;transition:all 0.2s;">
                        <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" id="d6" style="width:46px;height:52px;text-align:center;font-size:22px;font-weight:900;font-family:monospace;border:2px solid #d1d5db;border-radius:8px;color:#cc0000;outline:none;transition:all 0.2s;">
                    </div>
                    <input type="hidden" name="otp" id="otpHidden">
                </div>

                <hr style="border:none;border-top:1px solid #f3f4f6;margin:20px 0;">

                <div class="form-group">
                    <label class="form-label" style="text-transform:uppercase;letter-spacing:0.5px;font-size:11px;">Password Baru</label>
                    <div style="position:relative;">
                        <input type="password" name="password" id="pw" class="form-control" placeholder="Buat password kuat..." autocomplete="new-password" required>
                        <button type="button" onclick="togglePw('pw')" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;padding:0;">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <div style="height:4px;border-radius:2px;background:#f3f4f6;margin-top:6px;overflow:hidden;"><div style="height:100%;border-radius:2px;transition:width 0.3s,background 0.3s;width:0;" id="sBar"></div></div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2px 12px;background:#f9fafb;border-radius:6px;padding:8px 12px;margin-top:6px;">
                        <div class="rule" id="rule-len" style="display:flex;align-items:center;gap:4px;font-size:11px;color:#9ca3af;"><div style="width:5px;height:5px;border-radius:50%;background:#d1d5db;flex-shrink:0;" class="rdot"></div>Min 8 karakter</div>
                        <div class="rule" id="rule-upper" style="display:flex;align-items:center;gap:4px;font-size:11px;color:#9ca3af;"><div style="width:5px;height:5px;border-radius:50%;background:#d1d5db;flex-shrink:0;" class="rdot"></div>Huruf besar</div>
                        <div class="rule" id="rule-lower" style="display:flex;align-items:center;gap:4px;font-size:11px;color:#9ca3af;"><div style="width:5px;height:5px;border-radius:50%;background:#d1d5db;flex-shrink:0;" class="rdot"></div>Huruf kecil</div>
                        <div class="rule" id="rule-num" style="display:flex;align-items:center;gap:4px;font-size:11px;color:#9ca3af;"><div style="width:5px;height:5px;border-radius:50%;background:#d1d5db;flex-shrink:0;" class="rdot"></div>Angka</div>
                        <div class="rule" id="rule-sym" style="display:flex;align-items:center;gap:4px;font-size:11px;color:#9ca3af;"><div style="width:5px;height:5px;border-radius:50%;background:#d1d5db;flex-shrink:0;" class="rdot"></div>Simbol</div>
                    </div>
                    @error('password') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" style="text-transform:uppercase;letter-spacing:0.5px;font-size:11px;">Konfirmasi Password</label>
                    <div style="position:relative;">
                        <input type="password" name="password_confirmation" id="pwc" class="form-control" placeholder="Ulangi password baru...">
                        <button type="button" onclick="togglePw('pwc')" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;padding:0;">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px;" onclick="combineOtp()">
                    Simpan Password Baru
                </button>
            </form>

            <div style="text-align:center;margin-top:14px;">
                <a href="{{ route('profile.change-password') }}" style="font-size:12px;color:#cc0000;text-decoration:none;font-weight:500;">← Kirim ulang OTP</a>
            </div>
        </div>
    </div>
    @endif

    <div style="text-align:center;">
        <a href="{{ route('profile.edit') }}" style="font-size:13px;color:#6b7280;text-decoration:none;">← Kembali ke Edit Profil</a>
    </div>

</div>

<script>
function selectOpt(m) {
    document.querySelectorAll('.opt-card').forEach(el => { el.style.borderColor = '#e5e7eb'; el.style.background = '#fff'; });
    const el = document.getElementById('opt-' + m);
    if (el) { el.style.borderColor = '#cc0000'; el.style.background = '#fff5f5'; }
    const radio = document.querySelector(`input[value="${m}"]`);
    if (radio) radio.checked = true;
}
selectOpt('primary');

// OTP digit navigation
const digits = document.querySelectorAll('.otp-digit');
digits.forEach((inp, i) => {
    inp.addEventListener('focus', () => inp.style.borderColor = '#cc0000');
    inp.addEventListener('blur',  () => inp.style.borderColor = '#d1d5db');
    inp.addEventListener('input', () => {
        inp.value = inp.value.replace(/[^0-9]/g,'');
        if (inp.value && i < digits.length - 1) digits[i+1].focus();
    });
    inp.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !inp.value && i > 0) digits[i-1].focus();
    });
    inp.addEventListener('paste', e => {
        const p = e.clipboardData.getData('text').replace(/\D/g,'');
        if (p.length === 6) { p.split('').forEach((c,j)=>{digits[j].value=c;}); digits[5].focus(); e.preventDefault(); }
    });
});

function combineOtp() {
    const hidden = document.getElementById('otpHidden');
    if (hidden) hidden.value = Array.from(digits).map(d=>d.value).join('');
}

// Password strength
const pw = document.getElementById('pw');
if (pw) {
    pw.addEventListener('input', function() {
        const v = this.value;
        const rules = {
            'rule-len': v.length >= 8, 'rule-upper': /[A-Z]/.test(v),
            'rule-lower': /[a-z]/.test(v), 'rule-num': /[0-9]/.test(v),
            'rule-sym': /[^A-Za-z0-9]/.test(v),
        };
        let score = 0;
        Object.entries(rules).forEach(([id, ok]) => {
            const el = document.getElementById(id);
            if (el) {
                el.style.color = ok ? '#16a34a' : '#9ca3af';
                const dot = el.querySelector('.rdot');
                if (dot) dot.style.background = ok ? '#16a34a' : '#d1d5db';
                if (ok) score++;
            }
        });
        const bar = document.getElementById('sBar');
        if (bar) {
            bar.style.width = (score/5*100)+'%';
            const colors = ['','#ef4444','#f97316','#f59e0b','#22c55e','#16a34a'];
            bar.style.background = colors[score] || '#f3f4f6';
        }
    });
}

function togglePw(id) {
    const el = document.getElementById(id);
    if (el) el.type = el.type === 'password' ? 'text' : 'password';
}
</script>
@endsection
