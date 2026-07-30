@extends('layouts.app')
@section('title', 'Tambah Shift')
@section('page-title', 'Tambah Shift Baru')
@section('breadcrumb', 'Manajemen › Shift › Tambah')

@section('content')
<div style="max-width:600px;margin:0 auto;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Form Shift Kerja</span>
            <a href="{{ route('shift.index') }}" class="btn btn-secondary btn-sm">Batal</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('shift.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Shift <span class="required">*</span></label>
                    <input type="text" name="nama_shift" value="{{ old('nama_shift') }}" class="form-control" placeholder="Contoh: Pagi, Siang, Full" required>
                    @error('nama_shift') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Jam Mulai <span class="required">*</span></label>
                        <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" class="form-control" required>
                        @error('jam_mulai') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jam Selesai <span class="required">*</span></label>
                        <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" class="form-control" required>
                        @error('jam_selesai') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Kode Warna HEX <span class="required">*</span></label>
                        <div style="display:flex;gap:8px;">
                            <input type="color" name="kode_warna" value="{{ old('kode_warna', '#cc0000') }}" style="width:40px;height:38px;border:none;border-radius:6px;cursor:pointer;">
                            <input type="text" name="kode_warna_text" value="{{ old('kode_warna', '#cc0000') }}" class="form-control" readonly style="font-family:monospace;">
                        </div>
                        @error('kode_warna') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Toleransi Keterlambatan (menit) <span class="required">*</span></label>
                        <input type="number" name="toleransi_keterlambatan_menit" value="{{ old('toleransi_keterlambatan_menit', 15) }}" class="form-control" min="0" max="60" required>
                        @error('toleransi_keterlambatan_menit') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Shift</label>
                    <textarea name="deskripsi" class="form-control" placeholder="Keterangan singkat mengenai shift">{{ old('deskripsi') }}</textarea>
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:20px;">
                    <a href="{{ route('shift.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Shift</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
