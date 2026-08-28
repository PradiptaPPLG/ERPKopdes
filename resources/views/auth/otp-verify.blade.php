<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masukkan Kode OTP - ERP Kopdes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #fff; border-radius: 20px; box-shadow: 0 30px 80px rgba(0,0,0,0.4); width: 100%; max-width: 420px; overflow: hidden; }
        .card-header { background: linear-gradient(135deg, #cc0000 0%, #8b0000 100%); padding: 28px 36px; text-align: center; }
        .card-header h1 { color: #fff; font-size: 20px; font-weight: 800; }
        .card-header p { color: rgba(255,255,255,0.75); font-size: 12px; margin-top: 3px; }
        .step-bar { display: flex; gap: 0; border-bottom: 1px solid #f0f0f0; }
        .step-bar .step { flex: 1; padding: 10px 4px; text-align: center; font-size: 11px; font-weight: 600; color: #d1d5db; }
        .step-bar .step.active { color: #cc0000; border-bottom: 2px solid #cc0000; }
        .step-bar .step.done { color: #16a34a; }
        .card-body { padding: 30px 32px; }
        .desc { font-size: 13px; color: #6b7280; line-height: 1.6; margin-bottom: 28px; }
        .otp-inputs { display: flex; gap: 10px; justify-content: center; margin-bottom: 28px; }
        .otp-inputs input { width: 50px; height: 58px; text-align: center; font-size: 24px; font-weight: 800; font-family: 'Courier New', monospace; border: 2px solid #d1d5db; border-radius: 10px; outline: none; color: #cc0000; transition: all 0.2s; }
        .otp-inputs input:focus { border-color: #cc0000; box-shadow: 0 0 0 3px rgba(204,0,0,0.12); }
        input[name="otp"] { display: none; }
        .error-msg { font-size: 12px; color: #dc2626; margin-bottom: 16px; text-align: center; padding: 8px 12px; background: #fef2f2; border-radius: 6px; }
        .btn { display: block; width: 100%; padding: 13px; background: linear-gradient(135deg, #cc0000, #8b0000); color: #fff; font-size: 14px; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: all 0.2s; font-family: inherit; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(204,0,0,0.4); }
        .resend { text-align: center; margin-top: 18px; font-size: 12px; color: #9ca3af; }
        .resend a { color: #cc0000; font-weight: 600; text-decoration: none; }
        .timer { display: inline-block; font-weight: 700; color: #374151; }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <h1>Masukkan Kode OTP</h1>
        <p>ERP Koperasi Desa — Verifikasi Identitas</p>
    </div>
    <div class="step-bar">
        <div class="step done">1. Email</div>
        <div class="step done">2. Metode</div>
        <div class="step active">3. Verifikasi</div>
        <div class="step">4. Reset</div>
    </div>
    <div class="card-body">
        <p class="desc">
            Kami telah mengirimkan kode OTP 6 digit ke <strong>{{ $maskedDest }}</strong>.
            Kode berlaku selama <strong>10 menit</strong>. Periksa folder spam jika tidak muncul.
        </p>

        <form method="POST" action="{{ route('password.otp.check') }}" id="otpForm">
            @csrf

            @error('otp')
            <div class="error-msg">{{ $message }}</div>
            @enderror

            <div class="otp-inputs">
                <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" id="d1">
                <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" id="d2">
                <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" id="d3">
                <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" id="d4">
                <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" id="d5">
                <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" id="d6">
            </div>
            <input type="hidden" name="otp" id="otpHidden">

            <button type="submit" class="btn" id="submitBtn" onclick="combineOtp()">Verifikasi Kode →</button>
        </form>

        <div class="resend">
            <span id="timerText">Kirim ulang dalam <span class="timer" id="countdown">10:00</span></span>
            <span id="resendLink" style="display:none;"><a href="{{ route('password.forgot') }}">Kirim ulang kode OTP</a></span>
        </div>
    </div>
</div>
<script>
// Countdown timer
let total = 600;
const countdownEl = document.getElementById('countdown');
const timerText = document.getElementById('timerText');
const resendLink = document.getElementById('resendLink');
const timer = setInterval(() => {
    total--;
    if (total <= 0) {
        clearInterval(timer);
        timerText.style.display = 'none';
        resendLink.style.display = 'inline';
        return;
    }
    const m = String(Math.floor(total / 60)).padStart(2, '0');
    const s = String(total % 60).padStart(2, '0');
    countdownEl.textContent = `${m}:${s}`;
}, 1000);

// OTP digit navigation
const digits = document.querySelectorAll('.otp-digit');
digits.forEach((input, i) => {
    input.addEventListener('input', () => {
        input.value = input.value.replace(/[^0-9]/g, '');
        if (input.value && i < digits.length - 1) digits[i + 1].focus();
    });
    input.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !input.value && i > 0) digits[i - 1].focus();
    });
    input.addEventListener('paste', e => {
        const pasted = e.clipboardData.getData('text').replace(/\D/g, '');
        if (pasted.length === 6) {
            pasted.split('').forEach((c, j) => { digits[j].value = c; });
            digits[5].focus();
            e.preventDefault();
        }
    });
});

function combineOtp() {
    document.getElementById('otpHidden').value = Array.from(digits).map(d => d.value).join('');
}

digits[0].focus();
</script>
</body>
</html>
