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
            <div class="sidebar-logo-sub">Maju Bersama</div>
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

        {{-- Kehadiran: hanya tampil untuk karyawan (bukan admin) --}}
        @if(!auth()->user()->isAdmin())
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
        @endif

        {{-- Manajemen: hanya tampil untuk yang bisa approve (admin/ketua/sekretaris) --}}
        @if(auth()->user()->canApprove())
        <div class="sidebar-group-label">Manajemen</div>

        {{-- Monitor Absensi (hanya admin) --}}
        @if(auth()->user()->isAdmin())
        <a href="{{ route('absensi.index') }}" class="sidebar-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span>Monitor Absensi</span>
        </a>
        @endif

        <a href="{{ route('izin.index') }}" class="sidebar-link {{ request()->routeIs('izin.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Izin &amp; Cuti</span>
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
        @endif

        <div class="sidebar-group-label">Bantuan</div>
        <a href="{{ route('panduan') }}" class="sidebar-link {{ request()->routeIs('panduan') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span>Menu Panduan</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div style="display:flex;align-items:center;gap:10px;">
            <div class="avatar" style="background:#fff3f3;color:#cc0000;font-size:11px;width:32px;height:32px;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div style="flex:1;min-width:0;">
                <div style="color:#fff;font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ Str::limit(auth()->user()->name, 18) }}
                </div>
                <div style="color:rgba(255,255,255,0.6);font-size:10px;">
                    {{ auth()->user()->jabatanLabel() }}
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
            @csrf
            <button type="submit" class="btn btn-sm" style="width:100%;background:rgba(255,255,255,0.12);color:#fff;border:1px solid rgba(255,255,255,0.2);justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
            </button>
        </form>
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
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="text-align:right;">
                <div style="font-size:13px;font-weight:600;color:#1a1a1a;">{{ auth()->user()->name }}</div>
                <div style="font-size:11px;color:#888;">{{ auth()->user()->jabatanLabel() }}</div>
            </div>
            @if(auth()->user()->foto_profil)
            <img src="{{ Storage::url(auth()->user()->foto_profil) }}" alt="" class="avatar">
            @else
            <div class="avatar" style="background:#fff0f0;color:#cc0000;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            @endif
        </div>
    </header>

    <div style="padding: 0 28px;">
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

@stack('scripts')
</body>
</html>
