@extends('layouts.app')
@section('title', 'Buat Jadwal Shift')
@section('page-title', 'Buat Penjadwalan Shift')
@section('breadcrumb', 'Manajemen › Jadwal Shift › Buat')

@section('content')
<div style="max-width:700px;margin:0 auto;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Form Penugasan Shift</span>
            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary btn-sm">Batal</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('jadwal.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Pilih Shift <span class="required">*</span></label>
                    <select name="shift_id" class="form-control" required>
                        <option value="" disabled selected>Pilih Shift Kerja</option>
                        @foreach($shifts as $s)
                        <option value="{{ $s->id }}" {{ old('shift_id')==$s->id ? 'selected':'' }}>
                            {{ $s->nama_shift }} ({{ $s->jam_mulai_format }} - {{ $s->jam_selesai_format }})
                        </option>
                        @endforeach
                    </select>
                    @error('shift_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai <span class="required">*</span></label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', today()->format('Y-m-d')) }}" class="form-control" required>
                        @error('tanggal_mulai') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Selesai <span class="required">*</span></label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', today()->addDays(6)->format('Y-m-d')) }}" class="form-control" required>
                        @error('tanggal_selesai') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;user-select:none;font-weight:600;font-size:12px;color:#555;">
                        <input type="checkbox" name="skip_weekend" value="1" {{ old('skip_weekend', true) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:#cc0000;">
                        Lewati hari Sabtu dan Minggu (Weekend)
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Pilih Karyawan <span class="required">*</span></label>
                    <div style="max-height:220px;overflow-y:auto;border:1px solid #d1d5db;border-radius:6px;padding:10px;background:#fafafa;">
                        <label style="display:flex;align-items:center;gap:8px;padding:6px;border-bottom:1px solid #eee;font-weight:700;cursor:pointer;">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)" style="width:16px;height:16px;accent-color:#cc0000;">
                            Pilih Semua Karyawan
                        </label>
                        @foreach($karyawan as $k)
                        <label style="display:flex;align-items:center;gap:8px;padding:6px;cursor:pointer;font-size:12px;">
                            <input type="checkbox" name="user_ids[]" value="{{ $k->id }}" class="emp-checkbox" style="width:15px;height:15px;accent-color:#cc0000;">
                            <span><strong>{{ $k->name }}</strong> ({{ $k->jabatanLabel() }})</span>
                        </label>
                        @endforeach
                    </div>
                    @error('user_ids') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:20px;">
                    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Generate Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleSelectAll(master) {
    document.querySelectorAll('.emp-checkbox').forEach(cb => cb.checked = master.checked);
}
</script>
@endsection
