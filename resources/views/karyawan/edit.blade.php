@extends('layouts.app')
@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Data Karyawan')
@section('breadcrumb', 'Manajemen › Data Karyawan › Edit')

@section('content')
<div style="max-width:800px;margin:0 auto;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Edit Karyawan: {{ $karyawan->name }}</span>
            <a href="{{ route('karyawan.show', $karyawan) }}" class="btn btn-secondary btn-sm">Batal</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('karyawan.update', $karyawan) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="font-weight:700;font-size:14px;color:#1a1a1a;margin-bottom:14px;padding-bottom:6px;border-bottom:1px solid #eee;">
                    A. Informasi Akun & Jabatan
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $karyawan->name) }}" class="form-control" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $karyawan->email) }}" class="form-control" required>
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                        @error('password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Jabatan <span class="required">*</span></label>
                        <select name="jabatan" class="form-control" required>
                            @foreach(['admin'=>'Administrator','ketua'=>'Ketua','sekretaris'=>'Sekretaris','bendahara'=>'Bendahara','kasir'=>'Kasir','petugas_toko'=>'Petugas Toko'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('jabatan', $karyawan->jabatan) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="aktif" {{ old('status', $karyawan->status)=='aktif' ? 'selected':'' }}>Aktif</option>
                            <option value="cuti" {{ old('status', $karyawan->status)=='cuti' ? 'selected':'' }}>Cuti</option>
                            <option value="nonaktif" {{ old('status', $karyawan->status)=='nonaktif' ? 'selected':'' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Shift Default</label>
                        <select name="shift_default_id" class="form-control">
                            <option value="">Pilih Shift Default</option>
                            @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ old('shift_default_id', $karyawan->shift_default_id) == $shift->id ? 'selected' : '' }}>
                                {{ $shift->nama_shift }} ({{ $shift->jam_mulai_format }}-{{ $shift->jam_selesai_format }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="font-weight:700;font-size:14px;color:#1a1a1a;margin:20px 0 14px;padding-bottom:6px;border-bottom:1px solid #eee;">
                    B. Data Pribadi & Identitas
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" name="nip" value="{{ old('nip', $karyawan->nip) }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">NIK (KTP)</label>
                        <input type="text" name="nik" value="{{ old('nik', $karyawan->nik) }}" class="form-control" maxlength="16">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $karyawan->tempat_lahir) }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir?->format('Y-m-d')) }}" class="form-control">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="">Pilih</option>
                            <option value="L" {{ old('jenis_kelamin', $karyawan->jenis_kelamin)=='L' ? 'selected':'' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $karyawan->jenis_kelamin)=='P' ? 'selected':'' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Agama</label>
                        <select name="agama" class="form-control">
                            <option value="">Pilih Agama</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag)
                            <option value="{{ $ag }}" {{ old('agama', $karyawan->agama)==$ag ? 'selected':'' }}>{{ $ag }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor HP / WA</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $karyawan->no_hp) }}" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control">{{ old('alamat', $karyawan->alamat) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Ganti Foto Profil</label>
                    <input type="file" name="foto_profil" class="form-control" accept="image/*">
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px;padding-top:16px;border-top:1px solid #eee;">
                    <a href="{{ route('karyawan.show', $karyawan) }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
