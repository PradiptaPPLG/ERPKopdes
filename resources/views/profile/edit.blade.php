@extends('layouts.app')
@section('title', 'Edit Profil Saya')
@section('page-title', 'Edit Profil Saya')
@section('breadcrumb', 'Profil Saya › Edit')

@section('content')
<div style="max-width:800px;margin:0 auto;">
    <div class="card" style="border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.02);border:1px solid #eef2f6;overflow:hidden;">
        <div class="card-header" style="background:#fff;border-bottom:1px solid #f0f3f6;padding:18px 24px;display:flex;align-items:center;justify-content:between;">
            <span class="card-title" style="font-size:15px;color:#1a1a1a;font-weight:700;">Form Edit Profil</span>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-sm" style="margin-left:auto;">Batal</a>
        </div>
        <div class="card-body" style="padding:24px;">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Section A --}}
                <div style="font-weight:700;font-size:14px;color:#cc0000;margin-bottom:18px;padding-bottom:8px;border-bottom:1px solid #f0f3f6;display:flex;align-items:center;gap:6px;">
                    <span style="background:#fef2f2;color:#cc0000;width:24px;height:24px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:12px;">A</span>
                    Informasi Akun & Keamanan
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
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                        @error('password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;background:#fafbfc;padding:16px;border-radius:8px;border:1px solid #f0f3f6;margin-bottom:20px;">
                    <div>
                        <label class="form-label" style="color:#666;">Jabatan / Role</label>
                        <div style="font-weight:700;color:#333;font-size:13px;">
                            {{ $karyawan->jabatanLabel() }}
                            <span style="font-size:10px;color:#888;font-weight:normal;display:block;margin-top:2px;">(Hanya dapat diubah oleh Administrator)</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label" style="color:#666;">Status Kepegawaian</label>
                        <div style="font-weight:700;color:#333;font-size:13px;">
                            {{ ucfirst($karyawan->status) }}
                            <span style="font-size:10px;color:#888;font-weight:normal;display:block;margin-top:2px;">(Hanya dapat diubah oleh Administrator)</span>
                        </div>
                    </div>
                </div>

                {{-- Section B --}}
                <div style="font-weight:700;font-size:14px;color:#cc0000;margin:24px 0 18px;padding-bottom:8px;border-bottom:1px solid #f0f3f6;display:flex;align-items:center;gap:6px;">
                    <span style="background:#fef2f2;color:#cc0000;width:24px;height:24px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:12px;">B</span>
                    Data Pribadi & Identitas
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" name="nip" value="{{ old('nip', $karyawan->nip) }}" class="form-control" placeholder="Contoh: KD-2025-001">
                        @error('nip') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">NIK (KTP)</label>
                        <input type="text" name="nik" value="{{ old('nik', $karyawan->nik) }}" class="form-control" maxlength="16" placeholder="16 Digit NIK KTP">
                        @error('nik') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $karyawan->tempat_lahir) }}" class="form-control">
                        @error('tempat_lahir') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir?->format('Y-m-d')) }}" class="form-control">
                        @error('tanggal_lahir') <div class="form-error">{{ $message }}</div> @enderror
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
                        @error('jenis_kelamin') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Agama</label>
                        <select name="agama" class="form-control">
                            <option value="">Pilih Agama</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag)
                            <option value="{{ $ag }}" {{ old('agama', $karyawan->agama)==$ag ? 'selected':'' }}>{{ $ag }}</option>
                            @endforeach
                        </select>
                        @error('agama') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor HP / WA</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $karyawan->no_hp) }}" class="form-control">
                        @error('no_hp') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $karyawan->alamat) }}</textarea>
                    @error('alamat') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Profil Baru (Opsional)</label>
                    <input type="file" name="foto_profil" class="form-control" accept="image/*">
                    <span style="font-size:11px;color:#888;display:block;margin-top:4px;">Format gambar: JPG, PNG. Maksimal 2MB.</span>
                    @error('foto_profil') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:32px;padding-top:20px;border-top:1px solid #f0f3f6;">
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary" style="border-radius:6px;">Batal</a>
                    <button type="submit" class="btn btn-primary" style="border-radius:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
