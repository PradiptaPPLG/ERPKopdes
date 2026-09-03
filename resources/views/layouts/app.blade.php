<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - ERP Kopdes</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/logos/kopdes.png') }}"
             alt="Logo Kopdes"
             class="sidebar-logo-img"
             onerror="this.style.display='none'">
        <div>
            <div class="sidebar-logo-text">Kopdes</div>
            <div class="sidebar-logo-sub">Nasional</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-group-label">Utama</div>
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
        </a>

        {{-- ══ MENU ADMIN (Super Administrator) ══ --}}
        @if(auth()->user()->isAdmin())
        <div class="sidebar-group-label">Kehadiran</div>
        <a href="{{ route('absensi.index') }}" class="sidebar-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span>Monitor Absensi</span>
        </a>
        <a href="{{ route('izin.index') }}" class="sidebar-link {{ request()->routeIs('izin.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Izin &amp; Cuti</span>
        </a>

        <div class="sidebar-group-label">Manajemen</div>
        <a href="{{ route('kopdes.index') }}" class="sidebar-link {{ request()->routeIs('kopdes.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Data Kopdes</span>
        </a>
        <a href="{{ route('karyawan.index') }}" class="sidebar-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>Data Karyawan</span>
        </a>
        <a href="{{ route('shift.index') }}" class="sidebar-link {{ request()->routeIs('shift.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Manajemen Shift</span>
        </a>
        <a href="{{ route('jadwal.index') }}" class="sidebar-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Jadwal Shift</span>
        </a>
        <a href="{{ route('laporan.index') }}" class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
            </svg>
            <span>Laporan</span>
        </a>
        <a href="{{ route('admin.logs') }}" class="sidebar-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Log Aktivitas</span>
        </a>

        {{-- ══ MENU KETUA (Manager Kopdes) ══ --}}
        @elseif(auth()->user()->isKetua())
        <div class="sidebar-group-label">Monitoring Kopdes</div>
        <a href="{{ route('absensi.index') }}" class="sidebar-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span>Monitor Absensi</span>
        </a>
        <a href="{{ route('izin.index') }}" class="sidebar-link {{ request()->routeIs('izin.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Izin &amp; Cuti</span>
        </a>

        <div class="sidebar-group-label">SDM Kopdes</div>
        <a href="{{ route('karyawan.index') }}" class="sidebar-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>Data Karyawan</span>
        </a>
        <a href="{{ route('jadwal.index') }}" class="sidebar-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Jadwal Shift</span>
        </a>
        <a href="{{ route('laporan.index') }}" class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
            </svg>
            <span>Laporan</span>
        </a>

        {{-- ══ MENU STAF (Sekretaris / Bendahara / Kasir / Petugas Toko) ══ --}}
        @else
        <div class="sidebar-group-label">Kehadiran</div>
        <a href="{{ route('absensi.create') }}" class="sidebar-link {{ request()->routeIs('absensi.create') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span>Absen Hari Ini</span>
        </a>
        <a href="{{ route('absensi.index') }}" class="sidebar-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Rekap Absensi</span>
        </a>
        <a href="{{ route('izin.index') }}" class="sidebar-link {{ request()->routeIs('izin.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Izin &amp; Cuti</span>
        </a>

        {{-- Sekretaris dapat approve izin --}}
        @if(auth()->user()->canApprove())
        <div class="sidebar-group-label">Manajemen</div>
        <a href="{{ route('absensi.index') }}" class="sidebar-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span>Monitor Absensi</span>
        </a>
        @endif
        @endif

        <div class="sidebar-group-label">Bantuan</div>
        <a href="{{ route('panduan') }}" class="sidebar-link {{ request()->routeIs('panduan') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span>Menu Panduan</span>
        </a>
    </nav>

    <div class="sidebar-footer" style="padding:15px; border-top:1px solid rgba(255,255,255,0.08); text-align:center; font-size:11px; color:rgba(255,255,255,0.4);">
        &copy; {{ date('Y') }} ERP Kopdes
    </div>
</aside>


<!-- MAIN CONTENT -->
<div class="main-content" style="min-height:100vh;">
    <header class="topbar">
        <div style="flex:1;">
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            @hasSection('breadcrumb')
            <div class="topbar-breadcrumb">@yield('breadcrumb')</div>
            @endif
        </div>
        <div style="display:flex;align-items:center;gap:16px;">
            {{-- Notification Dropdown --}}
            <div class="dropdown">
                <button type="button" class="dropdown-toggle" style="background:#f3f4f6;border:1px solid #d1d5db;border-radius:20px;padding:6px 14px;font-size:12px;font-weight:600;color:#374151;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all 0.15s;line-height:1;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#4b5563;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span>Notifikasi</span>
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        <span style="background:#dc2626;color:#fff;font-size:10px;font-weight:700;padding:1px 6px;border-radius:10px;line-height:1;">
                            {{ auth()->user()->unreadNotifications()->count() }}
                        </span>
                    @endif
                </button>
                <div class="dropdown-menu" style="min-width:320px;max-height:400px;overflow-y:auto;right:0;padding:0;border-radius:8px;border:1px solid #eef2f6;box-shadow:0 10px 25px rgba(0,0,0,0.1);">
                    <div style="padding:12px 16px;border-bottom:1px solid #f0f3f6;display:flex;align-items:center;justify-content:between;background:#fafbfc;">
                        <span style="font-weight:700;color:#1a1a1a;font-size:12px;">Notifikasi</span>
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <form action="{{ route('notifications.read-all') }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" style="background:none;border:none;color:#cc0000;font-size:11px;font-weight:600;cursor:pointer;padding:0;">Tandai Semua Dibaca</button>
                            </form>
                        @endif
                    </div>
                    <div style="padding:6px 0;">
                        @forelse(auth()->user()->notifications()->take(5)->get() as $notif)
                            <a href="{{ route('notifications.read', $notif) }}" class="dropdown-item" style="padding:10px 16px;border-bottom:1px solid #fafafa;white-space:normal;display:block;background: {{ $notif->is_read ? '#fff' : '#fff9f9' }};">
                                <div style="display:flex;justify-content:between;margin-bottom:2px;">
                                    <span style="font-weight:700;color:#1a1a1a;font-size:12px;">{{ $notif->title }}</span>
                                    @if(!$notif->is_read)
                                        <span style="display:inline-block;width:6px;height:6px;background:#dc2626;border-radius:50%;margin-left:auto;align-self:center;"></span>
                                    @endif
                                </div>
                                <div style="color:#555;font-size:11px;line-height:1.4;margin-top:2px;">{{ $notif->message }}</div>
                                <div style="color:#999;font-size:10px;margin-top:4px;">{{ $notif->created_at->diffForHumans() }}</div>
                            </a>
                        @empty
                            <div style="padding:24px 16px;text-align:center;color:#888;font-size:12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="margin:0 auto 6px;color:#cbd5e1;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                Tidak ada notifikasi baru
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Profile Dropdown --}}
            <div class="dropdown">
                <button type="button" class="dropdown-toggle" style="display:flex;align-items:center;gap:10px;background:none;border:none;cursor:pointer;padding:4px 8px;border-radius:8px;transition:background 0.15s;text-align:left;">
                    <div style="text-align:right;">
                        <div style="font-size:13px;font-weight:600;color:#1a1a1a;">{{ auth()->user()->name }}</div>
                        <div style="font-size:11px;color:#888;">
                            {{ auth()->user()->jabatanLabel() }}
                            @if(auth()->user()->kopdes)
                                <span style="color:#0284c7;font-weight:600;"> &bull; {{ auth()->user()->kopdes->nama }}</span>
                            @endif
                        </div>
                    </div>
                    @if(auth()->user()->foto_profil)
                    <img src="{{ Storage::url(auth()->user()->foto_profil) }}" alt="" class="avatar" style="border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    @else
                    <div class="avatar" style="background:#fff0f0;color:#cc0000;border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="color:#666;margin-left:2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="dropdown-menu" style="min-width:180px;right:0;padding:6px 0;border-radius:8px;border:1px solid #eef2f6;box-shadow:0 10px 25px rgba(0,0,0,0.08);background:#fff;margin-top:4px;">
                    <a href="{{ route('profile.show') }}" class="dropdown-item" style="padding:10px 16px;font-size:13px;color:#333;display:flex;align-items:center;gap:8px;text-decoration:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#666;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profil Saya</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item" style="padding:10px 16px;font-size:13px;color:#333;display:flex;align-items:center;gap:8px;text-decoration:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#666;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Edit Profil</span>
                    </a>
                    <a href="{{ route('profile.change-password') }}" class="dropdown-item" style="padding:10px 16px;font-size:13px;color:#333;display:flex;align-items:center;gap:8px;text-decoration:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#666;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Ubah Password</span>
                    </a>
                    <hr style="border:none;border-top:1px solid #f0f3f6;margin:6px 0;">
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="dropdown-item" style="width:100%;padding:10px 16px;font-size:13px;color:#dc2626;border:none;background:none;cursor:pointer;display:flex;align-items:center;gap:8px;text-align:left;font-family:inherit;font-weight:600;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Keluar (Logout)</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div style="padding: 0 28px;">
        {{-- Banner Peringatan 2FA --}}
        @if(!auth()->user()->hasTwoFactorEnabled())
        <div id="banner-2fa" style="display: flex; align-items: center; justify-content: space-between; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 1px solid #fde68a; border-radius: 8px; padding: 12px 18px; margin-top: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: all 0.2s ease;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="background: #f59e0b; color: #fff; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m0-6h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <strong style="color: #78350f; font-size: 13px; display: block;">Keamanan Akun Anda Belum Optimal!</strong>
                    <span style="color: #92400e; font-size: 11px;">Anda belum mengaktifkan Otentikasi Dua Faktor (2FA). Aktifkan sekarang untuk melindungi akun Anda dari spamming dan akses tidak sah.</span>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route('profile.2fa.setup') }}" style="background: #d97706; color: #fff; font-size: 11px; font-weight: 700; text-decoration: none; padding: 6px 14px; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: background 0.15s;">
                    Aktifkan 2FA
                </a>
                <button type="button" onclick="dismiss2faBanner()" style="background: none; border: none; color: #b45309; cursor: pointer; padding: 4px; font-size: 16px; font-weight: bold; line-height: 1; display: flex; align-items: center; justify-content: center;">
                    &times;
                </button>
            </div>
        </div>
        <script>
            if (sessionStorage.getItem('dismiss_2fa_banner') === 'true') {
                document.getElementById('banner-2fa').style.display = 'none';
            }
            function dismiss2faBanner() {
                document.getElementById('banner-2fa').style.display = 'none';
                sessionStorage.setItem('dismiss_2fa_banner', 'true');
            }
        </script>
        @endif

        @if(session('success'))
        <div class="alert alert-success" style="margin-top:16px;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0;margin-top:1px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-error" style="margin-top:16px;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0;margin-top:1px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif
    </div>

    <main class="content-area">
        @yield('content')
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        const toggle = e.target.closest('.dropdown-toggle');
        if (toggle) {
            const dropdown = toggle.closest('.dropdown');
            if (dropdown) {
                document.querySelectorAll('.dropdown.active').forEach(d => {
                    if (d !== dropdown) d.classList.remove('active');
                });
                dropdown.classList.toggle('active');
                e.preventDefault();
                e.stopPropagation();
                return;
            }
        }
        
        document.querySelectorAll('.dropdown.active').forEach(d => {
            d.classList.remove('active');
        });
    });
});
</script>

@stack('scripts')
</body>
</html>
