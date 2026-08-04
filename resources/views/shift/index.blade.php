@extends('layouts.app')
@section('title', 'Manajemen Shift')
@section('page-title', 'Manajemen Shift')
@section('breadcrumb', 'Manajemen › Shift')

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-title">Daftar Shift Kerja</span>
        <a href="{{ route('shift.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Shift
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Shift</th>
                    <th>Warna UI</th>
                    <th>Jam Kerja</th>
                    <th>Durasi</th>
                    <th>Toleransi Keterlambatan</th>
                    <th>Karyawan (Default)</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shifts as $s)
                <tr>
                    <td style="font-weight:700;color:#1a1a1a;">
                        <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $s->kode_warna }};margin-right:6px;"></span>
                        {{ $s->nama_shift }}
                    </td>
                    <td><code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:11px;">{{ $s->kode_warna }}</code></td>
                    <td style="font-weight:600;">{{ $s->jam_mulai_format }} - {{ $s->jam_selesai_format }} WIB</td>
                    <td>{{ $s->durasi_jam }}</td>
                    <td>{{ $s->toleransi_keterlambatan_menit }} menit</td>
                    <td><span class="badge badge-secondary">{{ $s->users_count }} karyawan</span></td>
                    <td style="text-align:center;">
                        <div class="dropdown">
                            <button type="button" class="dropdown-toggle" title="Aksi">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('shift.edit', $s) }}" class="dropdown-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    Edit
                                </a>
                                @if($s->users_count == 0)
                                <form method="POST" action="{{ route('shift.destroy', $s) }}" onsubmit="return confirm('Hapus shift {{ $s->nama_shift }}?')" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:24px;color:#888;">Belum ada shift.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
