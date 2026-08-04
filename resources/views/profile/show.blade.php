@extends('layouts.app')
@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('breadcrumb', 'Profil Saya')

@section('content')
<div style="display:grid;grid-template-columns:300px 1fr;gap:24px;align-items:start;">

    {{-- Left Card: Profile Summary --}}
    <div class="card" style="text-align:center;padding:28px 24px;border-radius:12px;box-shadow:0 4px 20px rgba(37,99,235,0.03);border:1px solid #dbeafe;background:linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);">
        <div style="position:relative;display:inline-block;margin:0 auto 20px;">
            @if($karyawan->foto_profil)
            <img src="{{ Storage::url($karyawan->foto_profil) }}" class="avatar avatar-lg" style="margin:0 auto;width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid #fff;box-shadow:0 8px 24px rgba(37,99,235,0.12);">
            @else
            <div class="avatar avatar-lg" style="background:linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);color:#0284c7;margin:0 auto;width:120px;height:120px;border-radius:50%;font-size:42px;font-weight:800;display:flex;align-items:center;justify-content:center;border:4px solid #fff;box-shadow:0 8px 24px rgba(37,99,235,0.12);">
                {{ strtoupper(substr($karyawan->name,0,1)) }}
            </div>
            @endif
        </div>

        <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin-bottom:6px;letter-spacing:-0.2px;">{{ $karyawan->name }}</h3>
        <div style="font-size:12px;color:#475569;margin-bottom:16px;font-weight:500;">{{ $karyawan->email }}</div>

        <div style="display:flex;gap:8px;justify-content:center;margin-bottom:24px;">
            <span class="badge badge-info" style="padding:6px 12px;font-size:11px;border-radius:20px;background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;">{{ $karyawan->jabatanLabel() }}</span>
            <span class="badge {{ $karyawan->status=='aktif' ? 'badge-success' : ($karyawan->status=='cuti' ? 'badge-warning' : 'badge-danger') }}" style="padding:6px 12px;font-size:11px;border-radius:20px;">
                {{ ucfirst($karyawan->status) }}
            </span>
        </div>

        <div style="text-align:left;border-top:1px solid #e2e8f0;padding-top:20px;font-size:12px;display:flex;flex-direction:column;gap:12px;">
            <div style="display:flex;justify-content:between;align-items:center;">
                <span style="color:#64748b;">NIP</span>
                <strong style="color:#1e293b;margin-left:auto;">{{ $karyawan->nip ?? '-' }}</strong>
            </div>
            <div style="display:flex;justify-content:between;align-items:center;">
                <span style="color:#64748b;">NIK</span>
                <strong style="color:#1e293b;margin-left:auto;">{{ $karyawan->nik ?? '-' }}</strong>
            </div>
            <div style="display:flex;justify-content:between;align-items:center;">
                <span style="color:#64748b;">No. HP</span>
                <strong style="color:#1e293b;margin-left:auto;">{{ $karyawan->no_hp ?? '-' }}</strong>
            </div>
            <div style="display:flex;justify-content:between;align-items:center;">
                <span style="color:#64748b;">Shift Default</span>
                <strong style="color:#1d4ed8;margin-left:auto;background:#eff6ff;padding:2px 8px;border-radius:4px;font-size:11px;border:1px solid #bfdbfe;">{{ $karyawan->shiftDefault?->nama_shift ?? 'Belum Diatur' }}</strong>
            </div>
        </div>

        <div style="margin-top:28px;">
            <a href="{{ route('profile.edit') }}" class="btn" style="width:100%;justify-content:center;padding:10px;border-radius:8px;font-size:13px;background:linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);color:#fff;box-shadow:0 4px 12px rgba(37,99,235,0.2);font-weight:600;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Profil Saya
            </a>
        </div>
    </div>

    {{-- Right Column: Detailed Info & History --}}
    <div style="display:flex;flex-direction:column;gap:24px;">

        {{-- Detail Card --}}
        <div class="card" style="border-radius:12px;box-shadow:0 4px 20px rgba(37,99,235,0.02);border:1px solid #e2e8f0;overflow:hidden;background:#fff;">
            <div class="card-header" style="background:linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);border-bottom:1px solid #e2e8f0;padding:18px 24px;">
                <span class="card-title" style="font-size:15px;color:#0f172a;font-weight:700;">Informasi Lengkap Karyawan</span>
            </div>
            <div class="card-body" style="padding:24px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;font-size:13px;">
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
@endsection
