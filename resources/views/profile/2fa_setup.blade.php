@extends('layouts.app')
@section('title', 'Setup 2FA')
@section('page-title', 'Setup Keamanan 2FA')
@section('breadcrumb', 'Profil / Setup 2FA')

@section('content')
<div style="max-width:650px;margin:0 auto;">
    
    <div class="card" style="border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,0.04);border:1px solid #e2e8f0;background:#fff;overflow:hidden;margin-bottom:24px;">
        <div class="card-header" style="background:linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);border-bottom:1px solid #e2e8f0;padding:20px 24px;">
            <h3 style="font-size:15px;color:#0f172a;font-weight:800;margin:0;display:flex;align-items:center;gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#cc0000" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Aktifkan Otentikasi Dua Faktor (2FA)
            </h3>
        </div>
        
        <div class="card-body" style="padding:32px 24px;">
            
            {{-- Langkah-langkah setup --}}
            <div style="display:flex;flex-direction:column;gap:20px;margin-bottom:32px;">
                
                <div style="display:flex;gap:16px;align-items:flex-start;">
                    <div style="background:#cc0000;color:#fff;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0;">1</div>
                    <div>
                        <h4 style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 4px;">Unduh Aplikasi Authenticator</h4>
                        <p style="font-size:12px;color:#64748b;line-height:1.5;margin:0;">
                            Unduh aplikasi Google Authenticator, Microsoft Authenticator, atau Authy di ponsel cerdas Anda dari App Store atau Google Play Store.
                        </p>
                    </div>
                </div>

                <div style="display:flex;gap:16px;align-items:flex-start;">
                    <div style="background:#cc0000;color:#fff;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0;">2</div>
                    <div>
                        <h4 style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 4px;">Pindai QR Code</h4>
                        <p style="font-size:12px;color:#64748b;line-height:1.5;margin:0 0 16px;">
                            Buka aplikasi authenticator Anda, pilih opsi "Scan QR Code" atau "Pindai Kode Batang", lalu arahkan kamera ponsel ke QR Code di bawah ini.
                        </p>
                        
                        {{-- QR Code Display --}}
                        <div style="display:flex;flex-direction:column;align-items:center;background:#f8fafc;padding:24px;border-radius:12px;border:1px dashed #cbd5e1;max-width:260px;margin:12px auto;text-align:center;">
                            <div style="background:#fff;padding:12px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.05);margin-bottom:12px;">
                                {!! $qrCodeSvg !!}
                            </div>
                            
                            {{-- Alternatif manual key --}}
                            <span style="font-size:10px;color:#64748b;text-transform:uppercase;font-weight:600;letter-spacing:0.5px;display:block;margin-bottom:4px;">Kunci Manual (Jika QR gagal)</span>
                            <code style="font-size:12px;font-weight:700;background:#e2e8f0;padding:4px 8px;border-radius:4px;color:#0f172a;letter-spacing:1px;font-family:monospace;word-break:break-all;">{{ $secretKey }}</code>
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:16px;align-items:flex-start;">
                    <div style="background:#cc0000;color:#fff;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0;">3</div>
                    <div>
                        <h4 style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 4px;">Konfirmasi Kode Verifikasi</h4>
                        <p style="font-size:12px;color:#64748b;line-height:1.5;margin:0 0 16px;">
                            Masukkan 6 digit kode yang sekarang ditampilkan di aplikasi authenticator Anda untuk mengonfirmasi bahwa penyiapan telah berhasil.
                        </p>

                        {{-- Alerts --}}
                        @if($errors->any())
                        <div class="alert alert-error" style="margin-bottom:16px;font-size:12px;padding:8px 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('profile.2fa.confirm') }}">
                            @csrf
                            <div style="display:flex;gap:10px;align-items:center;max-width:320px;">
                                <input type="text" name="code" id="verificationCode"
                                       class="form-control" placeholder="000 000"
                                       maxlength="6" required style="font-size:16px;font-weight:700;letter-spacing:2px;text-align:center;padding:10px;border-radius:6px;border:1px solid #cbd5e1;">
                                <button type="submit" class="btn btn-primary" style="padding:10px 16px;font-size:12px;background:#cc0000;border:none;flex-shrink:0;">
                                    Aktifkan 2FA
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div style="border-top:1px solid #e2e8f0;padding-top:20px;text-align:right;">
                <a href="{{ route('profile.show') }}" class="btn btn-secondary" style="font-size:12px;border-radius:6px;">
                    Batal
                </a>
            </div>

        </div>
    </div>

</div>

<script>
    // Hanya izinkan input angka
    document.getElementById('verificationCode').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endsection
