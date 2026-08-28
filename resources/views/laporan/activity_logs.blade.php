@extends('layouts.app')
@section('title', 'Log Aktivitas Sistem')
@section('page-title', 'Log Aktivitas Sistem')
@section('breadcrumb')
    Log Aktivitas
@endsection

@section('content')
<div style="display:flex; flex-direction:column; gap:24px;">

    {{-- Filter Card --}}
    <div class="card" style="border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.02);border:1px solid #e2e8f0;background:#fff;padding:20px;">
        <form method="GET" action="{{ route('admin.logs') }}" style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap; margin:0;">
            <div style="flex:1; min-width:200px;">
                <label style="font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; display:block; margin-bottom:6px;">Filter Karyawan</label>
                <select name="user_id" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; font-size:13px; color:#1e293b;">
                    <option value="">Semua Karyawan</option>
                    @foreach($karyawan as $k)
                        <option value="{{ $k->id }}" {{ request('user_id') == $k->id ? 'selected' : '' }}>{{ $k->name }} ({{ $k->jabatanLabel() }})</option>
                    @endforeach
                </select>
            </div>

            <div style="flex:1; min-width:200px;">
                <label style="font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; display:block; margin-bottom:6px;">Filter Aksi</label>
                <input type="text" name="aksi" value="{{ request('aksi') }}" placeholder="Contoh: login, clock_in" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; font-size:13px; color:#1e293b;">
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-primary" style="padding:10px 20px; font-size:13px; border-radius:8px; border:none; background:linear-gradient(135deg, #0e7490 0%, #0284c7 100%); color:#fff; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.logs') }}" class="btn btn-secondary" style="padding:10px 20px; font-size:13px; border-radius:8px; border:1px solid #cbd5e1; background:#f1f5f9; color:#334155; font-weight:600; text-decoration:none; display:flex; align-items:center; justify-content:center;">Reset</a>
                <a href="{{ route('admin.logs.export', request()->all()) }}" target="_blank" class="btn" style="padding:10px 20px; font-size:13px; border-radius:8px; border:none; background:#16a34a; color:#fff; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ekspor Log
                </a>
            </div>
        </form>
    </div>

    {{-- Logs Table Card --}}
    <div class="card" style="border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.02);border:1px solid #e2e8f0;background:#fff;overflow:hidden;">
        <div class="table-wrap">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr style="background:#fafbfc; border-bottom:1px solid #e2e8f0;">
                        <th style="padding:16px; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; width:150px;">Waktu</th>
                        <th style="padding:16px; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; width:180px;">Karyawan</th>
                        <th style="padding:16px; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; width:130px;">Aksi</th>
                        <th style="padding:16px; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase;">Deskripsi</th>
                        <th style="padding:16px; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; width:120px;">IP Address</th>
                        <th style="padding:16px; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; width:140px; text-align:center;">Integritas Log</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        @php
                            $isValid = $log->isValidHash();
                        @endphp
                        <tr style="border-bottom:1px solid #f1f5f9; background:{{ !$isValid ? '#fff1f2' : 'transparent' }};">
                            <td style="padding:16px; font-size:12px; color:#475569; font-weight:500;">
                                {{ $log->created_at ? $log->created_at->format('d M Y, H:i:s') : '-' }}
                            </td>
                            <td style="padding:16px; font-size:13px; color:#1e293b;">
                                <strong style="display:block;">{{ $log->user ? $log->user->name : 'Sistem' }}</strong>
                                <span style="font-size:11px; color:#64748b;">{{ $log->user ? $log->user->jabatanLabel() : '-' }}</span>
                            </td>
                            <td style="padding:16px;">
                                <span style="background:#e0f2fe; color:#0369a1; font-size:10px; font-weight:700; padding:4px 8px; border-radius:4px; text-transform:uppercase;">
                                    {{ $log->aksi }}
                                </span>
                            </td>
                            <td style="padding:16px; font-size:13px; color:#334155; line-height:1.5;">
                                {{ $log->deskripsi }}
                            </td>
                            <td style="padding:16px; font-size:12px; color:#64748b; font-family:monospace;">
                                {{ $log->ip_address }}
                            </td>
                            <td style="padding:16px; text-align:center;">
                                @if($isValid)
                                    <span style="background:#dcfce7; color:#166534; font-size:10px; font-weight:700; padding:4px 10px; border-radius:20px; border:1px solid #bbf7d0; display:inline-flex; align-items:center; gap:4px;">
                                        <span style="width:6px; height:6px; background:#22c55e; border-radius:50%;"></span>
                                        Otentik
                                    </span>
                                @else
                                    <span title="Tanda tangan digital tidak cocok! Data terindikasi dimanipulasi secara langsung di database." style="background:#ffe4e6; color:#9f1239; font-size:10px; font-weight:700; padding:4px 10px; border-radius:20px; border:1px solid #fecdd3; display:inline-flex; align-items:center; gap:4px; animation: pulse 2s infinite;">
                                        <span style="width:6px; height:6px; background:#ef4444; border-radius:50%;"></span>
                                        Manipulasi!
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:32px; color:#64748b;">Belum ada catatan log aktivitas yang terekam.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div style="padding:16px; border-top:1px solid #e2e8f0; background:#fafbfc;">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>

<style>
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
    70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
    100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}
</style>
@endsection
