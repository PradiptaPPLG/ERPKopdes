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
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <a href="{{ route('shift.edit', $s) }}" class="btn btn-secondary btn-xs">Edit</a>
                            @if($s->users_count == 0)
                            <form method="POST" action="{{ route('shift.destroy', $s) }}" onsubmit="return confirm('Hapus shift {{ $s->nama_shift }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                            </form>
                            @endif
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
