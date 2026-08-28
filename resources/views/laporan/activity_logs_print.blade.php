<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ekspor Log Aktivitas - ERP Kopdes</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; padding: 40px; line-height: 1.5; font-size: 13px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px double #cbd5e1; padding-bottom: 20px; }
        .header h1 { margin: 0 0 5px; font-size: 22px; color: #0f172a; text-transform: uppercase; }
        .header p { margin: 0; color: #64748b; font-size: 12px; }
        .meta { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .meta div span { font-weight: bold; color: #475569; display: block; font-size: 11px; text-transform: uppercase; margin-bottom: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f1f5f9; padding: 10px 12px; font-size: 11px; font-weight: bold; text-transform: uppercase; color: #475569; border: 1px solid #cbd5e1; text-align: left; }
        td { padding: 10px 12px; border: 1px solid #cbd5e1; vertical-align: top; }
        .signature-section { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; margin-top: 40px; page-break-inside: avoid; }
        .certificate { border: 1px dashed #22c55e; background: #f0fdf4; padding: 15px; border-radius: 8px; font-size: 11px; color: #166534; }
        .certificate.manipulated { border-color: #ef4444; background: #fff1f2; color: #9f1239; }
        .sign-box { text-align: center; }
        .sign-box .title { font-weight: bold; margin-bottom: 60px; }
        .sign-box .name { font-weight: bold; text-decoration: underline; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #0284c7; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Cetak Laporan</button>
    </div>

    <div class="header">
        <h1>Laporan Log Aktivitas Koperasi</h1>
        <p>Sistem ERP Kopdes Nasional - Modul Audit Keamanan Terintegrasi</p>
    </div>

    <div class="meta">
        <div>
            <span>Tanggal Cetak</span>
            <strong>{{ $exportTime->format('d F Y, H:i:s') }}</strong>
        </div>
        <div>
            <span>Oleh Auditor/Admin</span>
            <strong>{{ auth()->user()->name }} ({{ auth()->user()->jabatanLabel() }})</strong>
        </div>
        <div>
            <span>Total Rekam Log</span>
            <strong>{{ count($logs) }} Baris</strong>
        </div>
        <div>
            <span>Status Verifikasi Integritas</span>
            @if($manipulatedCount > 0)
                <strong style="color: #ef4444;">Ditemukan {{ $manipulatedCount }} Data Terindikasi Manipulasi!</strong>
            @else
                <strong style="color: #16a34a;">Semua Data Otentik &amp; Valid (100% Verified)</strong>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 130px;">Waktu</th>
                <th style="width: 140px;">Karyawan</th>
                <th style="width: 100px;">Aksi</th>
                <th>Deskripsi</th>
                <th style="width: 100px;">IP Address</th>
                <th style="width: 160px;">Tanda Tangan Hash (SHA-256)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                @php
                    $isValid = $log->isValidHash();
                @endphp
                <tr style="background: {{ !$isValid ? '#fff1f2' : 'transparent' }};">
                    <td>{{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-' }}</td>
                    <td>
                        <strong>{{ $log->user ? $log->user->name : 'Sistem' }}</strong><br>
                        <small style="color: #64748b;">{{ $log->user ? $log->user->jabatanLabel() : '-' }}</small>
                    </td>
                    <td><span style="font-family: monospace; font-weight: bold;">{{ strtoupper($log->aksi) }}</span></td>
                    <td>
                        {{ $log->deskripsi }}
                        @if(!$isValid)
                            <br><strong style="color: #ef4444; font-size: 10px;">[PERINGATAN: Integritas Rusak/Data Dimanipulasi!]</strong>
                        @endif
                    </td>
                    <td style="font-family: monospace;">{{ $log->ip_address }}</td>
                    <td style="font-family: monospace; font-size: 9px; word-break: break-all; color: {{ !$isValid ? '#ef4444' : '#475569' }};">
                        {{ $log->hash ?? 'LOG LAMA / TANPA HASH' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-section">
        @if($manipulatedCount > 0)
            <div class="certificate manipulated">
                <strong>SERTIFIKAT INTEGRITAS LOG (GAGAL):</strong><br>
                Sistem mendeteksi adanya manipulasi data log secara langsung di database. Laporan ini tidak memenuhi standar audit keamanan digital ERP Kopdes. Kode verifikasi kegagalan audit telah dicatat otomatis.
            </div>
        @else
            <div class="certificate">
                <strong>SERTIFIKAT INTEGRITAS LOG (VALID):</strong><br>
                Dokumen log ini telah diverifikasi secara digital menggunakan algoritma SHA-256 HMAC dengan tanda tangan rahasia sistem Kopdes. Seluruh data log terbukti otentik dan bebas dari modifikasi/tampering pihak ketiga.
            </div>
        @endif

        <div class="sign-box">
            <div class="title">Ketua Koperasi Desa Nasional,</div>
            <div class="name">{{ auth()->user()->name }}</div>
            <div style="font-size: 11px; color: #64748b;">NIP. {{ auth()->user()->nip ?? '-' }}</div>
        </div>
    </div>

</body>
</html>
