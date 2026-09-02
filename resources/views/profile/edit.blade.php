@extends('layouts.app')
@section('title', 'Edit Profil Saya')
@section('page-title', 'Edit Profil Saya')
@section('breadcrumb', 'Profil Saya › Edit')

@section('content')
<div style="max-width:800px;margin:0 auto;">
    <div class="card" style="border-radius:12px;box-shadow:0 4px 20px rgba(37,99,235,0.02);border:1px solid #dbeafe;overflow:hidden;background:#fff;">
        <div class="card-header" style="background:linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);border-bottom:1px solid #e2e8f0;padding:18px 24px;display:flex;align-items:center;justify-content:between;">
            <span class="card-title" style="font-size:15px;color:#0f172a;font-weight:700;">Form Edit Profil</span>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-sm" style="margin-left:auto;border-radius:6px;font-weight:600;">Batal</a>
        </div>
        <div class="card-body" style="padding:24px;">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Section A --}}
                <div style="font-weight:700;font-size:14px;color:#1e3a8a;margin-bottom:18px;padding-bottom:8px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:6px;">
                    <span style="background:#eff6ff;color:#2563eb;width:24px;height:24px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;border:1px solid #bfdbfe;">A</span>
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

                <div class="form-group">
                    <label class="form-label">Email Pemulihan (Recovery Email)</label>
                    <input type="email" name="recovery_email" value="{{ old('recovery_email', $karyawan->recovery_email) }}" class="form-control" placeholder="email.pemulihan@contoh.com">
                    <span style="font-size:11px;color:#64748b;display:block;margin-top:4px;">Digunakan sebagai alternatif penerima kode OTP saat Anda lupa password.</span>
                    @error('recovery_email') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                {{-- Password change via OTP only --}}
                <div style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:1px solid #e2e8f0;border-radius:10px;padding:16px 20px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                    <div>
                        <div style="font-size:13px;font-weight:700;color:#1e293b;margin-bottom:3px;">🔒 Keamanan Akun</div>
                        <div style="font-size:12px;color:#64748b;line-height:1.5;">Untuk mengubah password, verifikasi OTP diperlukan terlebih dahulu demi keamanan akun Anda.</div>
                    </div>
                    <a href="{{ route('profile.change-password') }}" class="btn btn-secondary" style="white-space:nowrap;font-weight:700;font-size:12px;padding:8px 16px;border-radius:8px;background:#cc0000;color:#fff;border:none;">
                        Ubah Password →
                    </a>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;background:#f8fafc;padding:16px;border-radius:8px;border:1px solid #e2e8f0;margin-bottom:20px;">
                    <div>
                        <label class="form-label" style="color:#475569;">Jabatan / Role</label>
                        <div style="font-weight:700;color:#1e293b;font-size:13px;">
                            {{ $karyawan->jabatanLabel() }}
                            <span style="font-size:10px;color:#64748b;font-weight:normal;display:block;margin-top:2px;">(Hanya dapat diubah oleh Administrator)</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label" style="color:#475569;">Status Kepegawaian</label>
                        <div style="font-weight:700;color:#1e293b;font-size:13px;">
                            {{ ucfirst($karyawan->status) }}
                            <span style="font-size:10px;color:#64748b;font-weight:normal;display:block;margin-top:2px;">(Hanya dapat diubah oleh Administrator)</span>
                        </div>
                    </div>
                </div>

                {{-- Section B --}}
                <div style="font-weight:700;font-size:14px;color:#1e3a8a;margin:24px 0 18px;padding-bottom:8px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:6px;">
                    <span style="background:#eff6ff;color:#2563eb;width:24px;height:24px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;border:1px solid #bfdbfe;">B</span>
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
                    <span style="font-size:11px;color:#64748b;display:block;margin-top:4px;">Format gambar: JPG, PNG. Maksimal 2MB.</span>
                    @error('foto_profil') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                {{-- Section C --}}
                <div style="font-weight:700;font-size:14px;color:#1e3a8a;margin:24px 0 18px;padding-bottom:8px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:6px;">
                    <span style="background:#eff6ff;color:#2563eb;width:24px;height:24px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;border:1px solid #bfdbfe;">C</span>
                    Kustomisasi ID Card (Gamifikasi)
                </div>

                <div style="background:#f8fafc;padding:16px;border-radius:8px;border:1px solid #e2e8f0;margin-bottom:20px;">
                    <div style="font-size:13px;color:#475569;margin-bottom:16px;">
                        Pilih tema frame ID Card Anda! Tema baru akan terbuka sesuai dengan jumlah absensi harian Anda. 
                        <strong>(Absensi Anda saat ini: {{ $karyawan->attendance_count }} hari)</strong>
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(140px, 1fr));gap:12px;">
                        @php
                            $tiers = \App\Models\User::getCardTiers();
                            $unlocked = $karyawan->unlocked_tiers;
                            $currentTheme = old('id_card_theme', $karyawan->id_card_theme ?: 1);
                        @endphp
                        @foreach($tiers as $level => $tier)
                            @php
                                $isUnlocked = in_array($level, $unlocked);
                            @endphp
                            <label style="display:block;cursor:{{ $isUnlocked ? 'pointer' : 'not-allowed' }};position:relative;">
                                <input type="radio" name="id_card_theme" value="{{ $level }}" 
                                       style="display:none;" 
                                       {{ !$isUnlocked ? 'disabled' : '' }}
                                       {{ $currentTheme == $level && $isUnlocked ? 'checked' : '' }}>
                                
                                <div style="border: 2px solid {{ $currentTheme == $level && $isUnlocked ? '#3b82f6' : '#e2e8f0' }};
                                            border-radius: 8px; overflow:hidden; opacity:{{ $isUnlocked ? '1' : '0.5' }};
                                            transition: all 0.2s;">
                                    {{-- Preview Banner --}}
                                    <div style="height:40px;{{ $tier['style'] }}"></div>
                                    <div style="padding:8px;text-align:center;background:#fff;">
                                        <div style="font-size:12px;font-weight:700;color:#1e293b;margin-bottom:2px;">{{ $tier['name'] }}</div>
                                        @if(!$isUnlocked)
                                            <div style="font-size:10px;color:#ef4444;font-weight:600;display:flex;align-items:center;justify-content:center;gap:4px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                                Butuh {{ $tier['days'] }} hari
                                            </div>
                                        @else
                                            <div style="font-size:10px;color:#10b981;font-weight:600;">Terbuka</div>
                                        @endif
                                    </div>
                                </div>
                                {{-- Checked Indicator --}}
                                @if($currentTheme == $level && $isUnlocked)
                                    <div style="position:absolute;top:-6px;right:-6px;background:#3b82f6;color:#fff;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 4px rgba(0,0,0,0.2);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:32px;padding-top:20px;border-top:1px solid #e2e8f0;">
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary" style="border-radius:6px;font-weight:600;">Batal</a>
                    <button type="submit" class="btn" style="border-radius:6px;background:linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);color:#fff;box-shadow:0 4px 12px rgba(37,99,235,0.2);font-weight:600;display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:none;cursor:pointer;">
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
