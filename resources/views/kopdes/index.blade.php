@extends('layouts.app')
@section('title', 'Data Kopdes')
@section('page-title', 'Manajemen Koperasi Desa')
@section('breadcrumb', 'Manajemen › Data Kopdes')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px;">

    {{-- Top Action & Search Bar --}}
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <form method="GET" action="{{ route('kopdes.index') }}" style="display:flex;gap:8px;flex:1;max-width:400px;margin:0;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama, alamat, provinsi..." style="font-size:13px;padding:8px 14px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
            @if(request('search'))
                <a href="{{ route('kopdes.index') }}" class="btn btn-secondary" style="background:#e5e7eb;color:#374151;">Reset</a>
            @endif
        </form>

        <a href="{{ route('kopdes.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Daftarkan Kopdes Baru
        </a>
    </div>

    {{-- Table Card --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Daftar Koperasi Desa</span>
            <span style="font-size:12px;color:#888;">Total: {{ $kopdes->total() }} Kopdes</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:220px;">Nama Koperasi</th>
                        <th>Alamat Lengkap</th>
                        <th style="width:140px;">Wilayah / Provinsi</th>
                        <th style="width:100px;text-align:center;">Karyawan</th>
                        <th style="width:80px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kopdes as $kop)
                    <tr>
                        <td>
                            <div style="font-weight:700;color:#cc0000;font-size:14px;">{{ $kop->nama }}</div>
                            <div style="font-size:11px;color:#888;margin-top:2px;">ID: #{{ sprintf('%03d', $kop->id) }}</div>
                            <div style="font-size:11px;color:#4b5563;margin-top:4px;">
                                <strong>Manager:</strong> {{ $kop->manager ? $kop->manager->name : 'Belum Ditunjuk' }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size:13px;color:#374151;line-height:1.4;">{{ $kop->alamat }}</div>
                            @if($kop->desa || $kop->kecamatan)
                            <div style="font-size:11px;color:#6b7280;margin-top:4px;">
                                Desa: {{ $kop->desa ?? '-' }} &bull; Kec: {{ $kop->kecamatan ?? '-' }}
                            </div>
                            @endif
                            <div style="font-size:10px;color:#0284c7;font-family:monospace;margin-top:4px;">
                                GPS: {{ $kop->latitude }}, {{ $kop->longitude }} (Radius: {{ $kop->radius_meter }}m)
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:600;font-size:13px;">{{ $kop->provinsi ?? '-' }}</div>
                            <div style="font-size:11px;color:#6b7280;margin-top:2px;">{{ $kop->kabupaten ?? '-' }}</div>
                        </td>
                        <td style="text-align:center;">
                            <span class="badge badge-info" style="font-size:12px;padding:4px 8px;font-weight:700;">
                                {{ $kop->users_count }} Orang
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <div class="dropdown" style="display:inline-block;position:relative;">
                                <button type="button" class="dropdown-toggle" title="Aksi" style="padding: 4px 8px; cursor: pointer; border: 1px solid #d1d5db; background: #fff; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;color:#4b5563;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                                <div class="dropdown-menu" style="text-align:left;position:absolute;right:0;top:100%;z-index:100;min-width:130px;background:#fff;border:1px solid #e5e7eb;border-radius:6px;box-shadow:0 4px 12px rgba(0,0,0,0.1);padding:4px 0;margin-top:2px;">
                                    <a href="{{ route('kopdes.show', $kop) }}" class="dropdown-item" style="display:flex;align-items:center;gap:6px;padding:8px 12px;font-size:12px;font-weight:600;color:#0369a1;text-decoration:none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        Inspect
                                    </a>
                                    <a href="{{ route('kopdes.edit', $kop) }}" class="dropdown-item" style="display:flex;align-items:center;gap:6px;padding:8px 12px;font-size:12px;font-weight:600;color:#374151;text-decoration:none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        Edit
                                    </a>
                                    <hr style="margin:4px 0;border:none;border-top:1px solid #f3f4f6;">
                                    <form action="{{ route('kopdes.destroy', $kop) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Kopdes ini? Semua karyawan terkait akan kehilangan asosiasi Kopdes mereka.')" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" style="display:flex;width:100%;align-items:center;gap:6px;padding:8px 12px;font-size:12px;font-weight:600;color:#dc2626;background:none;border:none;cursor:pointer;text-align:left;">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:40px;color:#888;">Belum ada data Koperasi Desa terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kopdes->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #eee;">
            {{ $kopdes->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
