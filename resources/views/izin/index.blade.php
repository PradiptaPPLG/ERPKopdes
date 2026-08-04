@extends('layouts.app')
@section('title', 'Izin & Cuti')
@section('page-title', 'Permohonan Izin & Cuti')
@section('breadcrumb', 'Kehadiran › Izin & Cuti')

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-title">Daftar Pengajuan Izin & Cuti</span>
        @if(!auth()->user()->canApprove())
        <a href="{{ route('izin.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Ajukan Izin / Cuti
        </a>
        @endif
    </div>

    {{-- Filter --}}
    <div style="padding:14px 20px;border-bottom:1px solid #e5e5e5;background:#fafafa;">
        <form method="GET" action="{{ route('izin.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            @if(auth()->user()->canApprove())
            <select name="user_id" class="form-control" style="width:180px;">
                <option value="">Semua Karyawan</option>
                @foreach($karyawan as $k)
                <option value="{{ $k->id }}" {{ request('user_id')==$k->id ? 'selected':'' }}>{{ $k->name }}</option>
                @endforeach
            </select>
            @endif

            <select name="jenis" class="form-control" style="width:150px;">
                <option value="">Semua Jenis</option>
                <option value="cuti_tahunan" {{ request('jenis')=='cuti_tahunan'?'selected':'' }}>Cuti Tahunan</option>
                <option value="sakit" {{ request('jenis')=='sakit'?'selected':'' }}>Sakit</option>
                <option value="izin_pribadi" {{ request('jenis')=='izin_pribadi'?'selected':'' }}>Izin Pribadi</option>
                <option value="dinas_luar" {{ request('jenis')=='dinas_luar'?'selected':'' }}>Dinas Luar</option>
            </select>

            <select name="status" class="form-control" style="width:140px;">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="disetujui" {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
                <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
            </select>

            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('izin.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Jenis</th>
                    <th>Tanggal Mulai - Selesai</th>
                    <th>Durasi</th>
                    <th>Alasan</th>
                    <th>Lampiran</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($izinCuti as $iz)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $iz->user->name }}</div>
                        <div style="font-size:11px;color:#888;">{{ $iz->user->jabatanLabel() }}</div>
                    </td>
                    <td><span class="badge badge-info">{{ $iz->jenisLabel() }}</span></td>
                    <td style="font-size:12px;">
                        {{ $iz->tanggal_mulai->format('d/m/Y') }} - {{ $iz->tanggal_selesai->format('d/m/Y') }}
                    </td>
                    <td>{{ $iz->jumlahHari() }} hari</td>
                    <td style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#666;">
                        {{ $iz->alasan }}
                    </td>
                    <td>
                        @if($iz->lampiran)
                        <a href="{{ Storage::url($iz->lampiran) }}" target="_blank" class="btn btn-secondary btn-xs">File</a>
                        @else - @endif
                    </td>
                    <td>
                        <span class="badge {{ $iz->status=='disetujui' ? 'badge-success' : ($iz->status=='ditolak' ? 'badge-danger' : 'badge-warning') }}">
                            {{ $iz->statusLabel() }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <a href="{{ route('izin.show', $iz) }}" class="btn btn-secondary btn-xs">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:28px;color:#888;">Tidak ada pengajuan izin/cuti.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($izinCuti->hasPages())
    <div style="padding:14px 20px;border-top:1px solid #e5e5e5;">
        {{ $izinCuti->links() }}
    </div>
    @endif
</div>
@endsection
