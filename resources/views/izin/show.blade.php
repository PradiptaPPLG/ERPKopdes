@extends('layouts.app')
@section('title', 'Detail Permohonan Izin')
@section('page-title', 'Detail Permohonan Izin / Cuti')
@section('breadcrumb', 'Kehadiran › Izin & Cuti › Detail')

@section('content')
<div style="max-width:750px;margin:0 auto;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Permohonan Izin: {{ $izin->user->name }}</span>
            <a href="{{ route('izin.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;font-size:13px;">
                <div>
                    <h4 style="font-size:13px;font-weight:700;color:#1a1a1a;margin-bottom:10px;">Data Permohonan</h4>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <div><span style="color:#888;">Pemohon:</span> <strong>{{ $izin->user->name }}</strong> ({{ $izin->user->jabatanLabel() }})</div>
                        <div><span style="color:#888;">Jenis Izin:</span> <span class="badge badge-info">{{ $izin->jenisLabel() }}</span></div>
                        <div><span style="color:#888;">Periode:</span> <strong>{{ $izin->tanggal_mulai->format('d/m/Y') }}</strong> s/d <strong>{{ $izin->tanggal_selesai->format('d/m/Y') }}</strong></div>
                        <div><span style="color:#888;">Total Durasi:</span> <strong>{{ $izin->jumlahHari() }} hari</strong></div>
                        <div><span style="color:#888;">Diajukan Pada:</span> {{ $izin->created_at->format('d M Y H:i') }}</div>
                    </div>
                </div>

                <div>
                    <h4 style="font-size:13px;font-weight:700;color:#1a1a1a;margin-bottom:10px;">Status & Lampiran</h4>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <div><span style="color:#888;">Status Approval:</span> <span class="badge {{ $izin->status=='disetujui' ? 'badge-success' : ($izin->status=='ditolak' ? 'badge-danger' : 'badge-warning') }}">{{ $izin->statusLabel() }}</span></div>
                        @if($izin->approver)
                        <div><span style="color:#888;">Diproses Oleh:</span> <strong>{{ $izin->approver->name }}</strong></div>
                        @endif
                        @if($izin->catatan_approver)
                        <div><span style="color:#888;">Catatan Approver:</span> <div style="background:#fafafa;padding:8px;border-radius:4px;margin-top:4px;">{{ $izin->catatan_approver }}</div></div>
                        @endif
                        <div><span style="color:#888;">Dokumen Lampiran:</span>
                            @if($izin->lampiran)
                            <div style="margin-top:4px;"><a href="{{ Storage::url($izin->lampiran) }}" target="_blank" class="btn btn-secondary btn-xs">Buka Dokumen</a></div>
                            @else
                            <span>-</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="grid-column:span 2;border-top:1px solid #eee;padding-top:14px;">
                    <h4 style="font-size:13px;font-weight:700;color:#1a1a1a;margin-bottom:6px;">Alasan Pengajuan</h4>
                    <div style="background:#fafafa;padding:12px;border-radius:6px;border:1px solid #e5e5e5;color:#333;line-height:1.5;">
                        {{ $izin->alasan }}
                    </div>
                </div>
            </div>

            {{-- Approval Controls for Admins --}}
            @if(auth()->user()->canApprove() && $izin->status == 'pending')
            <div style="margin-top:24px;padding-top:18px;border-top:1px solid #eee;">
                <h4 style="font-size:14px;font-weight:700;color:#1a1a1a;margin-bottom:10px;">Proses Persetujuan</h4>
                <form method="POST" action="{{ route('izin.approve', $izin) }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Catatan Approver (Opsional)</label>
                        <input type="text" name="catatan_approver" class="form-control" placeholder="Contoh: Disetujui, harap selesaikan pekerjaan sebelum cuti.">
                    </div>
                    <div style="display:flex;gap:10px;">
                        <button type="submit" name="status" value="disetujui" class="btn btn-success" style="flex:1;justify-content:center;">Setujui Permohonan</button>
                        <button type="submit" name="status" value="ditolak" class="btn btn-danger" style="flex:1;justify-content:center;">Tolak Permohonan</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
