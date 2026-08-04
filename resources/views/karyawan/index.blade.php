@extends('layouts.app')
@section('title', 'Data Karyawan')
@section('page-title', 'Data Karyawan')
@section('breadcrumb', 'Manajemen › Data Karyawan')

@section('content')

{{-- ── Detail error import CSV ── --}}
@if(session('import_errors'))
<div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:14px 16px;margin-bottom:16px;">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#d97706;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.193 2.5 1.732 2.5z"/>
        </svg>
        <strong style="font-size:13px;color:#92400e;">Detail Baris yang Dilewati saat Import:</strong>
    </div>
    <ul style="margin:0;padding-left:20px;font-size:12px;color:#78350f;line-height:1.8;">
        @foreach(session('import_errors') as $err)
        <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">

    {{-- Header --}}
    <div class="card-header">
        <span class="card-title">Daftar Karyawan</span>
        <div style="display:flex;gap:8px;">
            <button type="button" id="btnImportCsv" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Import CSV
            </button>
            <a href="{{ route('karyawan.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Tambah Karyawan
            </a>
        </div>
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
                        <div class="dropdown">
                            <button type="button" class="dropdown-toggle" title="Aksi">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('karyawan.show', $k) }}" class="dropdown-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    Detail
                                </a>
                                <a href="{{ route('karyawan.edit', $k) }}" class="dropdown-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    Edit
                                </a>
                                @if($k->id !== auth()->id())
                                <form method="POST" action="{{ route('karyawan.destroy', $k) }}"
                                      onsubmit="return confirm('Hapus karyawan {{ $k->name }}?')" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
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

{{-- ============================================================ --}}
{{-- MODAL: Import CSV Karyawan                                  --}}
{{-- ============================================================ --}}
<div id="modalImportCsv" style="
    display:none;position:fixed;inset:0;z-index:9000;
    background:rgba(0,0,0,0.45);backdrop-filter:blur(3px);
    align-items:center;justify-content:center;
">
    <div style="
        background:#fff;border-radius:12px;width:100%;max-width:520px;
        margin:20px;box-shadow:0 20px 60px rgba(0,0,0,0.2);
        animation:modalIn 0.2s ease;
    ">
        {{-- Modal Header --}}
        <div style="padding:18px 22px;border-bottom:1px solid #e5e5e5;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <div style="font-size:15px;font-weight:700;color:#1a1a1a;">Import Karyawan via CSV</div>
                <div style="font-size:11px;color:#888;margin-top:2px;">Tambahkan banyak karyawan sekaligus dari file .csv</div>
            </div>
            <button id="btnCloseModal" type="button" style="background:none;border:none;cursor:pointer;color:#888;padding:4px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <div style="padding:22px;">

            {{-- Download Template --}}
            <div style="background:#fff8f0;border:1px solid #fed7aa;border-radius:8px;padding:12px 14px;margin-bottom:18px;display:flex;align-items:center;gap:10px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#d97706;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div style="flex:1;font-size:12px;color:#92400e;">
                    Belum punya template? <strong>Unduh dulu</strong> agar format kolom CSV sesuai.
                </div>
                <a href="{{ route('karyawan.template-csv') }}" class="btn btn-secondary btn-sm" style="flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Unduh Template
                </a>
            </div>

            {{-- Form Upload --}}
            <form action="{{ route('karyawan.import-csv') }}" method="POST" enctype="multipart/form-data" id="formImportCsv">
                @csrf

                {{-- Drop Zone --}}
                <div id="dropZone" style="
                    border:2px dashed #d1d5db;border-radius:8px;
                    padding:32px 20px;text-align:center;cursor:pointer;
                    transition:all 0.2s;background:#fafafa;margin-bottom:14px;
                ">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:36px;height:36px;color:#ccc;margin:0 auto 10px;display:block;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <div id="dropZoneText" style="font-size:13px;color:#666;font-weight:500;">Klik atau drag & drop file CSV di sini</div>
                    <div style="font-size:11px;color:#aaa;margin-top:4px;">Format: .csv | Maks: 2MB</div>
                    <input type="file" name="csv_file" id="csvFileInput" accept=".csv,text/csv"
                           style="display:none;" required>
                </div>

                {{-- Kolom Wajib / Referensi --}}
                <div style="background:#f8faff;border:1px solid #dbeafe;border-radius:8px;padding:12px 14px;margin-bottom:18px;">
                    <div style="font-size:11px;font-weight:700;color:#1e40af;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px;">Panduan Kolom CSV</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px 16px;font-size:11px;">
                        <div><span style="color:#dc2626;font-weight:700;">*</span> <code style="background:#e0e7ff;padding:1px 5px;border-radius:3px;">name</code> – Nama lengkap</div>
                        <div><span style="color:#dc2626;font-weight:700;">*</span> <code style="background:#e0e7ff;padding:1px 5px;border-radius:3px;">email</code> – Email login</div>
                        <div><span style="color:#dc2626;font-weight:700;">*</span> <code style="background:#e0e7ff;padding:1px 5px;border-radius:3px;">password</code> – Password awal</div>
                        <div><span style="color:#dc2626;font-weight:700;">*</span> <code style="background:#e0e7ff;padding:1px 5px;border-radius:3px;">jabatan</code> – kasir / admin / ...</div>
                        <div><code style="background:#f3f4f6;padding:1px 5px;border-radius:3px;">status</code> – aktif / nonaktif / cuti</div>
                        <div><code style="background:#f3f4f6;padding:1px 5px;border-radius:3px;">nip</code> – Nomor induk pegawai</div>
                        <div><code style="background:#f3f4f6;padding:1px 5px;border-radius:3px;">nik</code> – 16 digit NIK KTP</div>
                        <div><code style="background:#f3f4f6;padding:1px 5px;border-radius:3px;">no_hp</code> – Nomor HP</div>
                        <div><code style="background:#f3f4f6;padding:1px 5px;border-radius:3px;">jenis_kelamin</code> – L / P</div>
                        <div><code style="background:#f3f4f6;padding:1px 5px;border-radius:3px;">tanggal_lahir</code> – YYYY-MM-DD</div>
                    </div>
                    <div style="margin-top:6px;font-size:10px;color:#999;"><span style="color:#dc2626;font-weight:700;">*</span> = kolom wajib diisi</div>
                </div>

                {{-- Submit Button --}}
                <div style="display:flex;gap:8px;justify-content:flex-end;">
                    <button type="button" id="btnCancelModal" class="btn btn-secondary">Batal</button>
                    <button type="submit" id="btnSubmitImport" class="btn btn-primary" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span id="btnSubmitText">Pilih file dulu...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<style>
@keyframes modalIn {
    from { opacity:0; transform:translateY(-12px) scale(0.97); }
    to   { opacity:1; transform:translateY(0)      scale(1); }
}
#dropZone.drag-over {
    border-color:#cc0000;
    background:#fff5f5;
}
#dropZone.has-file {
    border-color:#16a34a;
    background:#f0fdf4;
}
</style>
<script>
(function() {
    const modal       = document.getElementById('modalImportCsv');
    const btnOpen     = document.getElementById('btnImportCsv');
    const btnClose    = document.getElementById('btnCloseModal');
    const btnCancel   = document.getElementById('btnCancelModal');
    const dropZone    = document.getElementById('dropZone');
    const fileInput   = document.getElementById('csvFileInput');
    const btnSubmit   = document.getElementById('btnSubmitImport');
    const btnText     = document.getElementById('btnSubmitText');
    const dropZoneText = document.getElementById('dropZoneText');

    // Buka modal
    btnOpen.addEventListener('click', () => {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });

    // Tutup modal
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        resetDropzone();
    }
    btnClose.addEventListener('click', closeModal);
    btnCancel.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

    // Klik area drop zone → trigger file input
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag & drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file) applyFile(file);
    });

    // File dipilih via input
    fileInput.addEventListener('change', () => {
        if (fileInput.files[0]) applyFile(fileInput.files[0]);
    });

    function applyFile(file) {
        if (!file.name.toLowerCase().endsWith('.csv')) {
            alert('File harus berformat .csv');
            return;
        }
        // Update tampilan
        dropZone.classList.add('has-file');
        dropZone.classList.remove('drag-over');
        dropZoneText.innerHTML = `<strong style="color:#16a34a;">${file.name}</strong><br><span style="font-size:11px;color:#888;">${(file.size/1024).toFixed(1)} KB &mdash; klik untuk ganti</span>`;
        // Aktifkan tombol submit
        btnSubmit.disabled = false;
        btnText.textContent = 'Import Sekarang';
        // Assign file ke input agar terkirim
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
    }

    function resetDropzone() {
        dropZone.classList.remove('has-file', 'drag-over');
        dropZoneText.textContent = 'Klik atau drag & drop file CSV di sini';
        fileInput.value = '';
        btnSubmit.disabled = true;
        btnText.textContent = 'Pilih file dulu...';
    }

    // Loading state saat submit
    document.getElementById('formImportCsv').addEventListener('submit', () => {
        btnSubmit.disabled = true;
        btnText.textContent = 'Mengimport...';
    });
})();
</script>
@endpush

@endsection
