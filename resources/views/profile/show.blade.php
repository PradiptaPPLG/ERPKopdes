@extends('layouts.app')
@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('breadcrumb', 'Profil Saya')

@section('content')
<div style="display:grid;grid-template-columns:320px 1fr;gap:24px;align-items:start;">

    {{-- Left Card: ID Card Style --}}
    <div class="card" style="border-radius:24px;box-shadow:0 10px 30px rgba(14,116,144,0.06);border:1px solid #dbeafe;background:#fff;overflow:hidden;text-align:center;padding:0;">
        
        {{-- Top Dynamic Gamified Header with ID Card Slot --}}
        <div style="height:135px;{!! $karyawan->card_theme_style !!};position:relative;border-radius:0 0 50% 50% / 0 0 20px 20px;display:flex;justify-content:center;align-items:flex-start;overflow:hidden;">
            {{-- ID Card Notch/Slot --}}
            <div style="width:55px;height:12px;background:#f5f5f5;border-radius:6px;margin-top:14px;border:1px solid rgba(255,255,255,0.25);box-shadow:inset 0 2px 4px rgba(0,0,0,0.12);"></div>
        </div>

        {{-- Avatar overlapping the U-curve --}}
        <div style="position:relative;display:inline-block;margin:-55px auto 16px;z-index:10;">
            @if($karyawan->foto_profil)
            <img src="{{ Storage::url($karyawan->foto_profil) }}" style="width:110px;height:110px;border-radius:50%;object-fit:cover;border:5px solid #fff;box-shadow:0 6px 16px rgba(14,116,144,0.12);">
            @else
            <div style="background:linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);color:#0284c7;width:110px;height:110px;border-radius:50%;font-size:38px;font-weight:800;display:flex;align-items:center;justify-content:center;border:5px solid #fff;box-shadow:0 6px 16px rgba(14,116,144,0.12);">
                {{ strtoupper(substr($karyawan->name,0,1)) }}
            </div>
            @endif
        </div>

        {{-- Employee Name --}}
        <h3 style="font-size:16px;font-weight:800;color:#0f172a;margin:0 16px 2px;letter-spacing:-0.2px;text-transform:uppercase;">{{ $karyawan->name }}</h3>
        
        {{-- Role/Jabatan with Side Lines --}}
        <div style="display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:20px;padding:0 20px;">
            <span style="flex:1;height:1px;background:#e2e8f0;max-width:35px;"></span>
            <span style="font-size:11px;font-weight:600;color:#64748b;text-transform:capitalize;font-style:italic;">{{ $karyawan->jabatanLabel() }}</span>
            <span style="flex:1;height:1px;background:#e2e8f0;max-width:35px;"></span>
        </div>

        {{-- ID Card Fields Layout --}}
        <div style="display:grid;grid-template-columns:80px 12px 1fr;gap:10px 0;text-align:left;font-size:12px;color:#475569;padding:0 24px;margin-bottom:28px;font-family:'Inter', sans-serif;">
            <div style="font-weight:700;color:#1e293b;">NIP</div>
            <div style="color:#94a3b8;">:</div>
            <div style="color:#334155;font-weight:500;">{{ $karyawan->nip ?? '-' }}</div>

            <div style="font-weight:700;color:#1e293b;">NIK</div>
            <div style="color:#94a3b8;">:</div>
            <div style="color:#334155;font-weight:500;">{{ $karyawan->nik ?? '-' }}</div>

            <div style="font-weight:700;color:#1e293b;">KOPDES</div>
            <div style="color:#94a3b8;">:</div>
            <div style="color:#0284c7;font-weight:700;">{{ $karyawan->kopdes?->nama ?? 'Pusat / Semua Kopdes' }}</div>

            <div style="font-weight:700;color:#1e293b;">STATUS</div>
            <div style="color:#94a3b8;">:</div>
            <div>
                <span class="badge {{ $karyawan->status=='aktif' ? 'badge-success' : ($karyawan->status=='cuti' ? 'badge-warning' : 'badge-danger') }}" style="font-size:10px;padding:2px 8px;border-radius:4px;font-weight:700;">
                    {{ ucfirst($karyawan->status) }}
                </span>
            </div>

            <div style="font-weight:700;color:#1e293b;">PHONE</div>
            <div style="color:#94a3b8;">:</div>
            <div style="color:#334155;font-weight:500;">{{ $karyawan->no_hp ?? '-' }}</div>

            <div style="font-weight:700;color:#1e293b;">SHIFT</div>
            <div style="color:#94a3b8;">:</div>
            <div style="color:#0284c7;font-weight:700;">{{ $karyawan->shiftDefault?->nama_shift ?? 'Belum Diatur' }}</div>

            <div style="font-weight:700;color:#1e293b;">EMAIL</div>
            <div style="color:#94a3b8;">:</div>
            <div style="color:#334155;font-weight:500;word-break:break-all;font-size:11px;">{{ $karyawan->email }}</div>
        </div>

        {{-- Edit Button --}}
        <div style="padding:0 24px 28px;">
            <a href="{{ route('profile.edit') }}" class="btn" style="width:100%;justify-content:center;padding:10px;border-radius:8px;font-size:13px;background:linear-gradient(135deg, #0e7490 0%, #0284c7 100%);color:#fff;box-shadow:0 4px 12px rgba(14,116,144,0.25);font-weight:600;border:none;display:flex;align-items:center;gap:6px;text-decoration:none;transition:all 0.2s;margin-bottom:12px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Profil Saya
            </a>
            
            <button type="button" onclick="downloadIDCard()" class="btn" style="width:100%;justify-content:center;padding:10px;border-radius:8px;font-size:13px;background:#10b981;color:#fff;font-weight:600;border:none;display:flex;align-items:center;gap:6px;cursor:pointer;transition:all 0.2s;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download ID Card
            </button>
            <p style="font-size:11px;color:#64748b;margin-top:10px;line-height:1.4;">Gunakan QR Code pada ID Card ini untuk login instan tanpa menggunakan password (Sign in with ID Card).</p>
        </div>
    </div>

    {{-- Right Column: Detailed Info & History --}}
    <div style="display:flex;flex-direction:column;gap:24px;">

        {{-- 2FA Security Management Card --}}
        <div class="card" style="border-radius:12px;box-shadow:0 4px 20px rgba(220,38,38,0.02);border:1px solid #e2e8f0;overflow:hidden;background:#fff;">
            <div class="card-header" style="background:linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);border-bottom:1px solid #e2e8f0;padding:18px 24px;display:flex;align-items:center;gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#cc0000" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span class="card-title" style="font-size:15px;color:#0f172a;font-weight:700;">Keamanan Dua Langkah (2FA)</span>
            </div>
            <div class="card-body" style="padding:24px;">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;">
                    <div style="flex:1;">
                        <p style="font-size:13px;color:#475569;line-height:1.5;margin:0 0 8px;">
                            Tambahkan lapisan keamanan ekstra pada akun Anda. Setelah diaktifkan, masuk ke sistem memerlukan password dan kode verifikasi satu kali (OTP) dari aplikasi Google Authenticator di perangkat seluler Anda.
                        </p>
                        <div style="display:inline-flex;align-items:center;gap:6px;">
                            <span style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;">Status 2FA:</span>
                            @if($karyawan->hasTwoFactorEnabled())
                                <span class="badge badge-success" style="font-size:10px;padding:3px 8px;border-radius:4px;font-weight:700;">AKTIF</span>
                            @else
                                <span class="badge badge-danger" style="font-size:10px;padding:3px 8px;border-radius:4px;font-weight:700;">TIDAK AKTIF</span>
                            @endif
                        </div>
                    </div>

                    <div style="flex-shrink:0; display:flex; gap:10px; flex-wrap:wrap;">
                        <a href="{{ route('profile.sessions') }}" class="btn btn-secondary" style="border-radius:8px;font-size:12px;padding:10px 16px;font-weight:600;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:all 0.2s;background:#f1f5f9;color:#334155;border:1px solid #cbd5e1;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Kelola Sesi Perangkat
                        </a>

                        @if($karyawan->hasTwoFactorEnabled())
                            <form method="POST" action="{{ route('profile.2fa.disable') }}" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan keamanan 2FA? Keamanan akun Anda akan berkurang.');" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn" style="border-radius:8px;font-size:12px;background:#ef4444;color:#fff;border:none;padding:10px 16px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:all 0.2s;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Nonaktifkan 2FA
                                </button>
                            </form>
                        @else
                            <a href="{{ route('profile.2fa.setup') }}" class="btn" style="border-radius:8px;font-size:12px;background:linear-gradient(135deg, #0e7490 0%, #0284c7 100%);color:#fff;padding:10px 16px;font-weight:600;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:all 0.2s;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Siapkan Keamanan 2FA
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Card --}}
        <div class="card" style="border-radius:12px;box-shadow:0 4px 20px rgba(37,99,235,0.02);border:1px solid #e2e8f0;overflow:hidden;background:#fff;">
            <div class="card-header" style="background:linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);border-bottom:1px solid #e2e8f0;padding:18px 24px;">
                <span class="card-title" style="font-size:15px;color:#0f172a;font-weight:700;">Informasi Lengkap Karyawan</span>
            </div>
            <div class="card-body" style="padding:24px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;font-size:13px;">
                    <div style="background:#f8fafc;padding:14px 18px;border-radius:8px;border:1px solid #e2e8f0;transition:all 0.2s;">
                        <span style="color:#64748b;display:block;font-size:11px;font-weight:600;text-transform:uppercase;margin-bottom:4px;letter-spacing:0.3px;">Kopdes Naungan</span>
                        <strong style="color:#0284c7;font-size:13px;">{{ $karyawan->kopdes?->nama ?? 'Pusat / Semua Kopdes' }}</strong>
                    </div>
                    <div style="background:#f8fafc;padding:14px 18px;border-radius:8px;border:1px solid #e2e8f0;transition:all 0.2s;">
                        <span style="color:#64748b;display:block;font-size:11px;font-weight:600;text-transform:uppercase;margin-bottom:4px;letter-spacing:0.3px;">Tempat, Tanggal Lahir</span>
                        <strong style="color:#1e293b;font-size:13px;">{{ $karyawan->tempat_lahir ?? '-' }}, {{ $karyawan->tanggal_lahir?->format('d F Y') ?? '-' }}</strong>
                    </div>
                    <div style="background:#f8fafc;padding:14px 18px;border-radius:8px;border:1px solid #e2e8f0;transition:all 0.2s;">
                        <span style="color:#64748b;display:block;font-size:11px;font-weight:600;text-transform:uppercase;margin-bottom:4px;letter-spacing:0.3px;">Jenis Kelamin</span>
                        <strong style="color:#1e293b;font-size:13px;">{{ $karyawan->jenis_kelamin == 'L' ? 'Laki-laki' : ($karyawan->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</strong>
                    </div>
                    <div style="background:#f8fafc;padding:14px 18px;border-radius:8px;border:1px solid #e2e8f0;transition:all 0.2s;">
                        <span style="color:#64748b;display:block;font-size:11px;font-weight:600;text-transform:uppercase;margin-bottom:4px;letter-spacing:0.3px;">Agama</span>
                        <strong style="color:#1e293b;font-size:13px;">{{ $karyawan->agama ?? '-' }}</strong>
                    </div>
                    <div style="background:#f8fafc;padding:14px 18px;border-radius:8px;border:1px solid #e2e8f0;transition:all 0.2s;">
                        <span style="color:#64748b;display:block;font-size:11px;font-weight:600;text-transform:uppercase;margin-bottom:4px;letter-spacing:0.3px;">Terdaftar Sejak</span>
                        <strong style="color:#1e293b;font-size:13px;">{{ $karyawan->created_at->format('d M Y') }}</strong>
                    </div>
                    <div style="grid-column:span 2;background:#f8fafc;padding:14px 18px;border-radius:8px;border:1px solid #e2e8f0;transition:all 0.2s;">
                        <span style="color:#64748b;display:block;font-size:11px;font-weight:600;text-transform:uppercase;margin-bottom:4px;letter-spacing:0.3px;">Alamat Lengkap</span>
                        <strong style="color:#1e293b;font-size:13px;line-height:1.6;display:block;">{{ $karyawan->alamat ?? '-' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Attendance --}}
        <div class="card" style="border-radius:12px;box-shadow:0 4px 20px rgba(37,99,235,0.02);border:1px solid #e2e8f0;overflow:hidden;background:#fff;">
            <div class="card-header" style="background:linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);border-bottom:1px solid #e2e8f0;padding:18px 24px;display:flex;align-items:center;justify-content:between;">
                <span class="card-title" style="font-size:15px;color:#0f172a;font-weight:700;">Riwayat Absensi Terakhir</span>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('absensi.index', ['user_id' => $karyawan->id]) }}" class="btn btn-secondary btn-sm" style="margin-left:auto;border-radius:6px;font-weight:600;">Lihat Semua</a>
                @else
                <a href="{{ route('absensi.index') }}" class="btn btn-secondary btn-sm" style="margin-left:auto;border-radius:6px;font-weight:600;">Lihat Semua</a>
                @endif
            </div>
            <div class="table-wrap">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#fafbfc;">
                            <th style="padding:12px 16px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Tanggal</th>
                            <th style="padding:12px 16px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Masuk</th>
                            <th style="padding:12px 16px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Pulang</th>
                            <th style="padding:12px 16px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Status</th>
                            <th style="padding:12px 16px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Keterlambatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawan->absensi as $abs)
                        <tr>
                            <td style="padding:12px 16px;border-bottom:1px solid #e2e8f0;font-weight:600;color:#1e293b;">{{ $abs->tanggal->format('d M Y') }}</td>
                            <td style="padding:12px 16px;border-bottom:1px solid #e2e8f0;color:#475569;">{{ $abs->jam_masuk ? substr($abs->jam_masuk,0,5) : '-' }}</td>
                            <td style="padding:12px 16px;border-bottom:1px solid #e2e8f0;color:#475569;">{{ $abs->jam_pulang ? substr($abs->jam_pulang,0,5) : '-' }}</td>
                            <td style="padding:12px 16px;border-bottom:1px solid #e2e8f0;">
                                <span class="badge badge-{{ $abs->statusColor() }}" style="border-radius:12px;padding:3px 10px;font-size:11px;">{{ $abs->statusLabel() }}</span>
                            </td>
                            <td style="padding:12px 16px;border-bottom:1px solid #e2e8f0;color:#e11d48;font-weight:600;">
                                {{ $abs->keterlambatan_menit > 0 ? $abs->keterlambatan_menit . ' menit' : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:24px;color:#64748b;border-bottom:none;">Belum ada riwayat absensi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    </div>

</div>

{{-- Hidden ID Card Element for Download --}}
<div style="position:absolute; left:-9999px; top:-9999px;">
    <div id="idCardElement" style="width:280px;background:#fff;border-radius:0;box-shadow:none;overflow:hidden;border:1px solid #e2e8f0;position:relative;">
        {{-- Header --}}
        <div style="height:100px;{!! $karyawan->card_theme_style !!};position:relative;border-radius:0 0 50% 50% / 0 0 20px 20px;display:flex;justify-content:center;align-items:flex-start;">
        </div>
        {{-- Avatar --}}
        <div style="position:relative;display:inline-block;margin:-45px auto 10px;z-index:10;text-align:center;width:100%;">
            @if($karyawan->foto_profil)
            <img src="{{ asset('storage/' . $karyawan->foto_profil) }}" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:4px solid #fff;box-shadow:0 4px 10px rgba(0,0,0,0.1);display:inline-block;">
            @else
            <div style="background:linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);color:#0284c7;width:90px;height:90px;border-radius:50%;font-size:32px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;border:4px solid #fff;box-shadow:0 4px 10px rgba(0,0,0,0.1);margin:0 auto;">
                {{ strtoupper(substr($karyawan->name,0,1)) }}
            </div>
            @endif
        </div>
        
        {{-- Info --}}
        <div style="text-align:center;">
            <h3 style="font-size:14px;font-weight:800;color:#0f172a;margin:0 10px 2px;text-transform:uppercase;">{{ $karyawan->name }}</h3>
            <div style="font-size:11px;font-weight:600;color:#64748b;text-transform:capitalize;font-style:italic;margin-bottom:12px;">{{ $karyawan->jabatanLabel() }}</div>
            
            <div style="font-size:12px;color:#334155;font-weight:700;margin-bottom:16px;">
                NIK: {{ $karyawan->nik ?? '-' }}
            </div>
        </div>
        
        {{-- QR Code --}}
        <div style="margin-bottom:20px;display:flex;justify-content:center;">
            <div style="background:#fff;padding:8px;border-radius:8px;border:1px solid #e2e8f0;">
                {!! $karyawan->qr_code_svg !!}
            </div>
        </div>
        
        <div style="background:#0e7490;color:#fff;font-size:10px;font-weight:700;padding:8px;text-transform:uppercase;text-align:center;">
            {{ $karyawan->kopdes?->nama ?? 'Koperasi Desa Nasional' }}
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function downloadIDCard() {
    const element = document.getElementById('idCardElement');
    
    // Simpan style asli untuk dikembalikan nanti
    const originalDisplay = element.parentElement.style.display;
    const originalPosition = element.parentElement.style.position;
    const originalLeft = element.parentElement.style.left;
    
    // Tampilkan elemen sesaat sebelum html2canvas memprosesnya,
    // tetap letakkan off-screen atau di bawah index z
    element.parentElement.style.position = 'fixed';
    element.parentElement.style.left = '0';
    element.parentElement.style.top = '0';
    element.parentElement.style.zIndex = '-9999';
    element.parentElement.style.opacity = '1';

    html2canvas(element, { scale: 3, useCORS: true, logging: false }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'ID-Card-{{ $karyawan->nik ?? $karyawan->id }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        
        // Kembalikan elemen ke kondisi hidden
        element.parentElement.style.position = originalPosition;
        element.parentElement.style.left = originalLeft;
        element.parentElement.style.top = '';
        element.parentElement.style.zIndex = '';
    }).catch(err => {
        console.error("Gagal mendownload ID card:", err);
    });
}
</script>

<style>
@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
</style>
@endsection
