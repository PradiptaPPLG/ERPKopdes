@extends('layouts.app')
@section('title', 'Ajukan Izin / Cuti')
@section('page-title', 'Form Permohonan Izin / Cuti')
@section('breadcrumb', 'Kehadiran › Izin & Cuti › Permohonan')

@section('content')
<div style="max-width:650px;margin:0 auto;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Form Permohonan Izin & Cuti</span>
            <a href="{{ route('izin.index') }}" class="btn btn-secondary btn-sm">Batal</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('izin.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Jenis Permohonan <span class="required">*</span></label>
                    <select name="jenis" class="form-control" required>
                        <option value="" disabled selected>Pilih Jenis Permohonan</option>
                        <option value="cuti_tahunan" {{ old('jenis')=='cuti_tahunan'?'selected':'' }}>Cuti Tahunan</option>
                        <option value="sakit" {{ old('jenis')=='sakit'?'selected':'' }}>Sakit (Surat Dokter)</option>
                        <option value="izin_pribadi" {{ old('jenis')=='izin_pribadi'?'selected':'' }}>Izin Keperluan Pribadi</option>
                        <option value="dinas_luar" {{ old('jenis')=='dinas_luar'?'selected':'' }}>Dinas Luar / Pelatihan</option>
                    </select>
                    @error('jenis') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai <span class="required">*</span></label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', today()->format('Y-m-d')) }}" class="form-control" required>
                        @error('tanggal_mulai') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Selesai <span class="required">*</span></label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', today()->format('Y-m-d')) }}" class="form-control" required>
                        @error('tanggal_selesai') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alasan Permohonan <span class="required">*</span></label>
                    <textarea name="alasan" class="form-control" placeholder="Jelaskan alasan pengajuan izin atau cuti Anda secara rinci" minlength="10" required>{{ old('alasan') }}</textarea>
                    @error('alasan') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Dokumen / Surat Lampiran (Opsional)</label>
                    <input type="file" name="lampiran" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                    <div style="font-size:11px;color:#888;margin-top:4px;">Upload surat keterangan dokter / undangan dinas. Max 2MB (JPG/PNG/PDF).</div>
                    @error('lampiran') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:20px;">
                    <a href="{{ route('izin.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Kirim Permohonan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
