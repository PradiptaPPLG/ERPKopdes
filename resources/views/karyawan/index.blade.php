@extends('layouts.app')
@section('title', 'Data Karyawan')
@section('page-title', 'Data Karyawan')
@section('breadcrumb', 'Manajemen › Data Karyawan')

@section('content')
<div class="card">
    {{-- Header --}}
    <div class="card-header">
        <span class="card-title">Daftar Karyawan</span>
        <a href="{{ route('karyawan.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Tambah Karyawan
        </a>
    </div>

    {{-- Filter Bar --}}
    <div style="padding:14px 20px;border-bottom:1px solid #e5e5e5;background:#fafafa;">
        <form method="GET" action="{{ route('karyawan.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <div style="position:relative;flex:1;min-width:200px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                     style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#999;pointer-events:none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                       style="padding-left:34px;" placeholder="Cari nama, NIP, email...">
            </div>
            <select name="jabatan" class="form-control" style="width:160px;">
                <option value="">Semua Jabatan</option>
                @foreach(['admin'=>'Administrator','ketua'=>'Ketua','sekretaris'=>'Sekretaris','bendahara'=>'Bendahara','kasir'=>'Kasir','petugas_toko'=>'Petugas Toko'] as $val => $lbl)
                <option value="{{ $val }}" {{ request('jabatan') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
            <select name="status" class="form-control" style="width:130px;">
                <option value="">Semua Status</option>
                <option value="aktif"   {{ request('status')=='aktif'   ? 'selected' : '' }}>Aktif</option>
                <option value="cuti"    {{ request('status')=='cuti'    ? 'selected' : '' }}>Cuti</option>
                <option value="nonaktif"{{ request('status')=='nonaktif'? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                Filter
            </button>
            @if(request()->hasAny(['search','jabatan','status']))
            <a href="{{ route('karyawan.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Karyawan</th>
                    <th>NIP</th>
                    <th>Jabatan</th>
                    <th>Shift Default</th>
                    <th>No. HP</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawan as $i => $k)
                <tr>
                    <td style="color:#888;font-size:12px;">{{ $karyawan->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            @if($k->foto_profil)
                            <img src="{{ Storage::url($k->foto_profil) }}" class="avatar">
                            @else
                            <div class="avatar" style="background:#fff0f0;color:#cc0000;">
                                {{ strtoupper(substr($k->name,0,1)) }}
                            </div>
                            @endif
                            <div>
                                <div style="font-weight:600;color:#1a1a1a;">{{ $k->name }}</div>
                                <div style="font-size:11px;color:#888;">{{ $k->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:12px;color:#555;">{{ $k->nip ?? '-' }}</td>
                    <td>
                        <span class="badge badge-primary">{{ $k->jabatanLabel() }}</span>
                    </td>
                    <td>
                        @if($k->shiftDefault)
                        <span style="display:inline-block;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;background:{{ $k->shiftDefault->kode_warna }}22;color:{{ $k->shiftDefault->kode_warna }};">
                            {{ $k->shiftDefault->nama_shift }}
                        </span>
                        @else
                        <span style="color:#888;">-</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#555;">{{ $k->no_hp ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $k->status=='aktif' ? 'badge-success' : ($k->status=='cuti' ? 'badge-warning' : 'badge-danger') }}">
                            {{ ucfirst($k->status) }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <a href="{{ route('karyawan.show', $k) }}" class="btn btn-secondary btn-xs" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                            <a href="{{ route('karyawan.edit', $k) }}" class="btn btn-secondary btn-xs" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            @if($k->id !== auth()->id())
                            <form method="POST" action="{{ route('karyawan.destroy', $k) }}"
                                  onsubmit="return confirm('Hapus karyawan {{ $k->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:32px;color:#888;">Tidak ada data karyawan ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($karyawan->hasPages())
    <div style="padding:14px 20px;border-top:1px solid #e5e5e5;">
        {{ $karyawan->links() }}
    </div>
    @endif
</div>
@endsection
