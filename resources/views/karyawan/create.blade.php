@extends('layouts.app')
@section('title', 'Tambah Karyawan')
@section('page-title', 'Tambah Karyawan Baru')
@section('breadcrumb', 'Manajemen › Data Karyawan › Tambah')

@section('content')
<div style="max-width:800px;margin:0 auto;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Formulir Karyawan Baru</span>
            <a href="{{ route('karyawan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('karyawan.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Informational Section Title --}}
                <div style="font-weight:700;font-size:14px;color:#1a1a1a;margin-bottom:14px;padding-bottom:6px;border-bottom:1px solid #eee;">
                    A. Informasi Akun & Jabatan
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Nama sesuai KTP" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="email@kopdes.id" required>
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Password <span class="required">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        @error('password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Jabatan <span class="required">*</span></label>
                        <select name="jabatan" class="form-control" required>
                            <option value="" disabled selected>Pilih Jabatan</option>
                            @foreach(['admin'=>'Administrator','ketua'=>'Ketua','sekretaris'=>'Sekretaris','bendahara'=>'Bendahara','kasir'=>'Kasir','petugas_toko'=>'Petugas Toko'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('jabatan') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                        @error('jabatan') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="aktif" {{ old('status')=='aktif' ? 'selected':'' }}>Aktif</option>
                            <option value="cuti" {{ old('status')=='cuti' ? 'selected':'' }}>Cuti</option>
                            <option value="nonaktif" {{ old('status')=='nonaktif' ? 'selected':'' }}>Nonaktif</option>
                        </select>
                        @error('status') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Shift Default</label>
                        <select name="shift_default_id" class="form-control">
                            <option value="">Pilih Shift Default</option>
                            @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ old('shift_default_id') == $shift->id ? 'selected' : '' }}>
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
                        <input type="text" name="nip" value="{{ old('nip') }}" class="form-control" placeholder="Contoh: KD-2026-0010">
                        @error('nip') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">NIK (KTP)</label>
                        <input type="text" name="nik" value="{{ old('nik') }}" class="form-control" maxlength="16" placeholder="16 digit NIK">
                        @error('nik') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="form-control" placeholder="Kota/Kabupaten">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-control">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="">Pilih</option>
                            <option value="L" {{ old('jenis_kelamin')=='L' ? 'selected':'' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin')=='P' ? 'selected':'' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Agama</label>
                        <select name="agama" class="form-control">
                            <option value="">Pilih Agama</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag)
                            <option value="{{ $ag }}" {{ old('agama')==$ag ? 'selected':'' }}>{{ $ag }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor HP / WA</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" placeholder="Alamat rumah tinggal">{{ old('alamat') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="foto_profil" class="form-control" accept="image/*">
                    <div style="font-size:11px;color:#888;margin-top:4px;">Format: JPG, PNG. Maks 2MB.</div>
                    @error('foto_profil') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px;padding-top:16px;border-top:1px solid #eee;">
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Karyawan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
