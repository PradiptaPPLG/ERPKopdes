@extends('layouts.app')
@section('title', 'Sesi Perangkat Aktif')
@section('page-title', 'Sesi Perangkat Aktif')
@section('breadcrumb')
    <a href="{{ route('profile.show') }}">Profil</a> &raquo; Sesi Perangkat
@endsection

@section('content')
<div style="max-width:800px; margin:0 auto;">

    <div class="card" style="border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.03);border:1px solid #e2e8f0;background:#fff;overflow:hidden;margin-bottom:24px;">
        <div class="card-header" style="background:linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);border-bottom:1px solid #e2e8f0;padding:18px 24px;display:flex;align-items:center;justify-content:between;">
            <div>
                <span class="card-title" style="font-size:15px;color:#0f172a;font-weight:700;display:block;margin-bottom:2px;">Daftar Sesi Perangkat</span>
                <span style="font-size:11px;color:#64748b;">Kelola perangkat yang sedang login menggunakan akun Anda saat ini.</span>
            </div>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-sm" style="border-radius:6px;font-weight:600;font-size:11px;background:#f1f5f9;border:1px solid #cbd5e1;color:#334155;text-decoration:none;padding:5px 12px;">Kembali ke Profil</a>
        </div>
        
        <div style="padding:24px; display:flex; flex-direction:column; gap:16px;">
            @foreach($sessions as $session)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:16px; border:1px solid #e2e8f0; border-radius:10px; background:{{ $session->is_current_device ? '#f0fdf4' : '#fff' }}; border-color:{{ $session->is_current_device ? '#bbf7d0' : '#e2e8f0' }}; transition:all 0.15s;">
                    <div style="display:flex; align-items:center; gap:14px;">
                        {{-- Icon Perangkat --}}
                        <div style="background:{{ $session->is_current_device ? '#dcfce7' : '#f1f5f9' }}; color:{{ $session->is_current_device ? '#15803d' : '#475569' }}; width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            @if($session->platform === 'Windows' || $session->platform === 'Mac' || $session->platform === 'Linux')
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                    <line x1="8" y1="21" x2="16" y2="21"/>
                                    <line x1="12" y1="17" x2="12" y2="21"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
                                    <line x1="12" y1="18" x2="12.01" y2="18"/>
                                </svg>
                            @endif
                        </div>
                        
                        {{-- Detail --}}
                        <div>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <strong style="font-size:13px; color:#1e293b;">{{ $session->platform }} &bull; {{ $session->browser }}</strong>
                                @if($session->is_current_device)
                                    <span style="background:#dcfce7; color:#166534; font-size:9px; font-weight:800; padding:2px 8px; border-radius:10px; border:1px solid #bbf7d0; text-transform:uppercase;">Perangkat Ini</span>
                                @endif
                            </div>
                            <span style="font-size:11px; color:#64748b; display:block; margin-top:2px;">
                                IP Address: {{ $session->ip_address }} &bull; Aktif {{ $session->last_active->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    {{-- Tombol Force Logout --}}
                    @if(!$session->is_current_device)
                        <form method="POST" action="{{ route('profile.sessions.destroy', $session->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin mengeluarkan paksa perangkat ini? Sesi login di perangkat tersebut akan berakhir secara instan.');" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:#fff1f2; border:1px solid #fecdd3; color:#e11d48; font-size:11px; font-weight:700; padding:8px 14px; border-radius:6px; cursor:pointer; transition:all 0.15s; display:inline-flex; align-items:center; gap:4px;">
                                Keluar
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
