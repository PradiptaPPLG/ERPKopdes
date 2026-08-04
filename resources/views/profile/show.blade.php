@extends('layouts.app')
@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('breadcrumb', 'Profil Saya')

@section('content')
<div style="display:grid;grid-template-columns:320px 1fr;gap:24px;align-items:start;">

    {{-- Left Card: ID Card Style --}}
    <div class="card" style="border-radius:24px;box-shadow:0 10px 30px rgba(14,116,144,0.06);border:1px solid #dbeafe;background:#fff;overflow:hidden;text-align:center;padding:0;">
        
        {{-- Top Teal-Blue Gradient Header with ID Card Slot --}}
        <div style="height:135px;background:linear-gradient(135deg, #0e7490 0%, #0284c7 100%);position:relative;border-radius:0 0 50% 50% / 0 0 20px 20px;display:flex;justify-content:center;align-items:flex-start;overflow:hidden;">
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
            <a href="{{ route('profile.edit') }}" class="btn" style="width:100%;justify-content:center;padding:10px;border-radius:8px;font-size:13px;background:linear-gradient(135deg, #0e7490 0%, #0284c7 100%);color:#fff;box-shadow:0 4px 12px rgba(14,116,144,0.25);font-weight:600;border:none;display:flex;align-items:center;gap:6px;text-decoration:none;transition:all 0.2s;">
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
