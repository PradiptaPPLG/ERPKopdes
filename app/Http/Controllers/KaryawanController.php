<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('shiftDefault')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q
                ->where('name', 'like', "%{$s}%")
                ->orWhere('nip', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%")
                ->orWhere('no_hp', 'like', "%{$s}%")
            );
        }

        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $karyawan = $query->paginate(10)->withQueryString();

        return view('karyawan.index', compact('karyawan'));
    }

    public function create()
    {
        $shifts = Shift::all();
        $kopdes = \App\Models\Kopdes::all();
        return view('karyawan.create', compact('shifts', 'kopdes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'        => ['required', 'min:6', 'confirmed'],
            'nik'             => ['nullable', 'string', 'size:16', 'unique:users,nik'],
            'nip'             => ['nullable', 'string', 'max:20', 'unique:users,nip'],
            'tempat_lahir'    => ['nullable', 'string', 'max:50'],
            'tanggal_lahir'   => ['nullable', 'date'],
            'jenis_kelamin'   => ['nullable', Rule::in(['L', 'P'])],
            'agama'           => ['nullable', 'string', 'max:20'],
            'alamat'          => ['nullable', 'string'],
            'no_hp'           => ['nullable', 'string', 'max:13'],
            'jabatan'         => ['required', Rule::in(['admin','ketua','sekretaris','bendahara','kasir','petugas_toko'])],
            'status'          => ['required', Rule::in(['aktif','nonaktif','cuti'])],
            'shift_default_id' => ['nullable', 'exists:shifts,id'],
            'kopdes_id'       => ['nullable', 'exists:kopdes,id'],
            'foto_profil'     => ['nullable', 'image', 'max:2048'],
        ], [
            'nik.size'   => 'NIK harus 16 digit.',
            'email.unique' => 'Email sudah digunakan.',
            'nik.unique' => 'NIK sudah terdaftar.',
        ]);

        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = $request->file('foto_profil')
                ->store('foto-profil', 'public');
        }

        $karyawan = User::create($data);

        LogAktivitas::catat(
            'tambah_karyawan',
            "Menambahkan karyawan baru: {$karyawan->name} ({$karyawan->jabatan})"
        );

        return redirect()->route('karyawan.index')
            ->with('success', "Karyawan {$karyawan->name} berhasil ditambahkan.");
    }

    public function show(User $karyawan)
    {
        $karyawan->load(['shiftDefault', 'absensi' => fn($q) => $q->latest()->take(10), 'izinCuti' => fn($q) => $q->latest()->take(5)]);
        return view('karyawan.show', compact('karyawan'));
    }

    public function edit(User $karyawan)
    {
        $shifts = Shift::all();
        $kopdes = \App\Models\Kopdes::all();
        return view('karyawan.edit', compact('karyawan', 'shifts', 'kopdes'));
    }

    public function update(Request $request, User $karyawan)
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($karyawan->id)],
            'password'        => ['nullable', 'min:6', 'confirmed'],
            'nik'             => ['nullable', 'string', 'size:16', Rule::unique('users', 'nik')->ignore($karyawan->id)],
            'nip'             => ['nullable', 'string', 'max:20', Rule::unique('users', 'nip')->ignore($karyawan->id)],
            'tempat_lahir'    => ['nullable', 'string', 'max:50'],
            'tanggal_lahir'   => ['nullable', 'date'],
            'jenis_kelamin'   => ['nullable', Rule::in(['L', 'P'])],
            'agama'           => ['nullable', 'string', 'max:20'],
            'alamat'          => ['nullable', 'string'],
            'no_hp'           => ['nullable', 'string', 'max:13'],
            'jabatan'         => ['required', Rule::in(['admin','ketua','sekretaris','bendahara','kasir','petugas_toko'])],
            'status'          => ['required', Rule::in(['aktif','nonaktif','cuti'])],
            'shift_default_id' => ['nullable', 'exists:shifts,id'],
            'kopdes_id'       => ['nullable', 'exists:kopdes,id'],
            'foto_profil'     => ['nullable', 'image', 'max:2048'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('foto_profil')) {
            if ($karyawan->foto_profil) {
                Storage::disk('public')->delete($karyawan->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')
                ->store('foto-profil', 'public');
        }

        $karyawan->update($data);

        LogAktivitas::catat(
            'edit_karyawan',
            "Mengubah data karyawan: {$karyawan->name}"
        );

        return redirect()->route('karyawan.show', $karyawan)
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(User $karyawan)
    {
        if ($karyawan->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $name = $karyawan->name;

        if ($karyawan->foto_profil) {
            Storage::disk('public')->delete($karyawan->foto_profil);
        }

        $karyawan->delete();

        LogAktivitas::catat('hapus_karyawan', "Menghapus karyawan: {$name}");

        return redirect()->route('karyawan.index')
            ->with('success', "Karyawan {$name} berhasil dihapus.");
    }

    // ── Import CSV ────────────────────────────────────────────────
    public function importCsv(Request $request)
    {
        // ── Validasi file ─────────────────────────────────────────
        $request->validate([
            'csv_file' => ['required', 'file', 'max:2048'],
        ], [
            'csv_file.required' => 'Pilih file CSV terlebih dahulu.',
        ]);

        $file = $request->file('csv_file');

        $ekstensi = strtolower($file->getClientOriginalExtension());
        if (!in_array($ekstensi, ['csv', 'txt'])) {
            return back()->with('error', 'File harus berformat .csv');
        }

        $path   = $file->getRealPath();
        $konten = file_get_contents($path);

        // Convert UTF-16 to UTF-8 if Excel-encoded
        $bom = substr($konten, 0, 2);
        if ($bom === "\xFF\xFE" || $bom === "\xFE\xFF") {
            $konten = mb_convert_encoding($konten, 'UTF-8', 'UTF-16');
        } elseif (strpos($konten, "\0") !== false) {
            $konten = mb_convert_encoding($konten, 'UTF-8', 'UTF-16');
        }

        // ── Strip BOM (ditambahkan Excel) ─────────────────────────
        $konten = preg_replace('/^\xEF\xBB\xBF/', '', $konten);

        // Normalisasi line ending (CRLF → LF)
        $konten = str_replace("\r\n", "\n", $konten);
        $konten = str_replace("\r",   "\n", $konten);

        // Auto-heal accidental newlines (e.g. name field split into two lines without quotes)
        $lines = explode("\n", $konten);
        $cleanLines = [];
        $tempLine = '';
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '') continue;
            
            // If the line has fewer than 3 delimiters, it is likely a split line fragment
            if (substr_count($trimmed, ',') < 3 && substr_count($trimmed, ';') < 3) {
                $tempLine .= ($tempLine === '' ? '' : ' ') . $trimmed;
            } else {
                $cleanLines[] = ($tempLine === '' ? '' : $tempLine . ' ') . $trimmed;
                $tempLine = '';
            }
        }
        if ($tempLine !== '') {
            $cleanLines[] = $tempLine;
        }
        $konten = implode("\n", $cleanLines);

        // ── Auto-detect delimiter ─────────────────────────────────
        $firstLine = $cleanLines[0] ?? '';
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        // ── Baca & normalisasi semua baris menggunakan str_getcsv ─
        $allRows = [];
        foreach ($cleanLines as $line) {
            $row = str_getcsv($line, $delimiter);
            $row = array_map('trim', $row);
            if (empty(array_filter($row))) continue;

            // Auto-heal single-cell pasting mistake (when Excel wraps the entire CSV line in quotes)
            if (count($row) === 1) {
                $singleCell = $row[0];
                $cellDelimiter = (substr_count($singleCell, ';') > substr_count($singleCell, ',')) ? ';' : ',';
                if (substr_count($singleCell, $cellDelimiter) >= 3) {
                    $row = str_getcsv($singleCell, $cellDelimiter);
                    $row = array_map('trim', $row);
                }
            }

            $allRows[] = $row;
        }

        if (empty($allRows)) {
            return back()->with('error', 'File CSV kosong atau tidak bisa dibaca.');
        }

        // ── Auto-detect apakah baris pertama adalah header ────────
        // Header valid: cell pertama berisi teks mirip "name" atau "nama"
        $kolomWajib = ['name', 'email', 'password', 'jabatan'];
        $barisPertamaRow = array_map(fn($h) => strtolower(preg_replace('/^\xEF\xBB\xBF/', '', $h)), $allRows[0]);

        $isHeader = count(array_intersect($kolomWajib, $barisPertamaRow)) >= 2;

        if ($isHeader) {
            $header   = $barisPertamaRow;
            $dataRows = array_slice($allRows, 1);
        } else {
            // Baris pertama adalah data — gunakan urutan kolom dari template
            $header   = ['name','email','password','jabatan','status','nip','nik','no_hp','jenis_kelamin','tempat_lahir','tanggal_lahir','agama','alamat','kopdes'];
            $dataRows = $allRows;
        }

        if (empty($dataRows)) {
            return back()->with('error', 'Tidak ada baris data di dalam file CSV.');
        }

        $berhasil     = 0;
        $gagal        = [];
        $baris        = $isHeader ? 1 : 0;
        $jabatanValid = ['admin','ketua','sekretaris','bendahara','kasir','petugas_toko'];
        $statusValid  = ['aktif','nonaktif','cuti'];

        foreach ($dataRows as $row) {
            $baris++;

            // Auto-heal split date of birth (e.g. 2009-02,23 due to comma typo instead of hyphen)
            if (count($row) > count($header)) {
                for ($idx = 0; $idx < count($row) - 1; $idx++) {
                    if (preg_match('/^\d{4}-\d{2}$/', $row[$idx]) && preg_match('/^\d{1,2}$/', $row[$idx + 1])) {
                        $row[$idx] = $row[$idx] . '-' . sprintf('%02d', $row[$idx + 1]);
                        array_splice($row, $idx + 1, 1);
                        break;
                    }
                }
            }

            // Sesuaikan jumlah kolom
            $jumlahHeader = count($header);
            $jumlahRow    = count($row);
            if ($jumlahRow > $jumlahHeader) {
                $row = array_slice($row, 0, $jumlahHeader);
            } elseif ($jumlahRow < $jumlahHeader) {
                $row = array_pad($row, $jumlahHeader, '');
            }

            $data = array_combine($header, $row);

            $name     = trim($data['name']     ?? '');
            $email    = trim($data['email']    ?? '');
            $password = trim($data['password'] ?? '');
            $jabatan  = strtolower(trim($data['jabatan'] ?? ''));
            $status   = strtolower(trim($data['status']  ?? 'aktif'));

            // ── Validasi kolom wajib ──────────────────────────────
            $errors = [];
            if (!$name)     $errors[] = 'kolom [name] kosong';
            if (!$email)    $errors[] = 'kolom [email] kosong';
            if (!$password) $errors[] = 'kolom [password] kosong';

            if (!empty($errors)) {
                $gagal[] = "Baris {$baris}: " . implode(', ', $errors) . '.';
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $gagal[] = "Baris {$baris}: format email '{$email}' tidak valid — pastikan format seperti nama@domain.com";
                continue;
            }

            if (User::where('email', $email)->exists()) {
                $gagal[] = "Baris {$baris}: email '{$email}' sudah terdaftar, baris dilewati.";
                continue;
            }

            if (!in_array($jabatan, $jabatanValid)) {
                $gagal[] = "Baris {$baris}: jabatan '{$jabatan}' tidak dikenal. Pilihan yang valid: " . implode(', ', $jabatanValid) . '.';
                continue;
            }

            if (!in_array($status, $statusValid)) {
                $status = 'aktif';
            }

            $nip = trim($data['nip'] ?? '');
            $nik = trim($data['nik'] ?? '');
            $kopdesName = trim($data['kopdes'] ?? '');
            $kopdesId   = null;

            if ($kopdesName) {
                $kopdesId = \App\Models\Kopdes::where('nama', 'like', "%{$kopdesName}%")->value('id');
                if (!$kopdesId) {
                    $gagal[] = "Baris {$baris}: Kopdes '{$kopdesName}' tidak ditemukan di database, karyawan tetap diimport tanpa Kopdes.";
                }
            }

            if ($nip && User::where('nip', $nip)->exists()) {
                $gagal[] = "Baris {$baris}: NIP '{$nip}' sudah terdaftar, baris dilewati.";
                continue;
            }

            if ($nik && strlen($nik) !== 16) {
                $gagal[] = "Baris {$baris}: NIK '{$nik}' harus tepat 16 digit (sekarang " . strlen($nik) . " digit), kolom NIK dikosongkan — data lain tetap diimport.";
                $nik = '';
            }

            // ── Sanitasi tanggal lahir ────────────────────────────
            // Toleransi: "2009-02,23" → "2009-02-23", "2009/02/23" → "2009-02-23"
            $tanggalLahirRaw = trim($data['tanggal_lahir'] ?? '');
            $tanggalLahir    = null;
            if ($tanggalLahirRaw) {
                // Ganti pemisah yang salah (koma, slash) → strip
                $tgl = preg_replace('/[,\/]/', '-', $tanggalLahirRaw);
                // Pastikan format YYYY-MM-DD valid
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl)) {
                    $tanggalLahir = $tgl;
                } else {
                    $gagal[] = "Baris {$baris}: format tanggal_lahir '{$tanggalLahirRaw}' tidak dikenal, gunakan YYYY-MM-DD (contoh: 2009-02-23). Kolom tanggal dikosongkan.";
                }
            }

            // ── Simpan ke database ────────────────────────────────
            try {
                User::create([
                    'name'          => $name,
                    'email'         => $email,
                    'password'      => Hash::make($password),
                    'jabatan'       => $jabatan,
                    'status'        => $status,
                    'nip'           => $nip ?: null,
                    'nik'           => $nik ?: null,
                    'no_hp'         => trim($data['no_hp']         ?? '') ?: null,
                    'jenis_kelamin' => strtoupper(trim($data['jenis_kelamin'] ?? '')) ?: null,
                    'tempat_lahir'  => trim($data['tempat_lahir']  ?? '') ?: null,
                    'tanggal_lahir' => $tanggalLahir,
                    'agama'         => trim($data['agama']         ?? '') ?: null,
                    'alamat'        => trim($data['alamat']        ?? '') ?: null,
                    'kopdes_id'     => $kopdesId,
                ]);
                $berhasil++;
            } catch (\Exception $e) {
                $gagal[] = "Baris {$baris}: gagal disimpan — {$e->getMessage()}.";
            }
        }

        LogAktivitas::catat('import_karyawan', "Import CSV: {$berhasil} karyawan berhasil ditambahkan.");

        // ── Response ──────────────────────────────────────────────
        if ($berhasil === 0 && empty($gagal)) {
            return redirect()->route('karyawan.index')
                ->with('error', 'Tidak ada data yang diproses. Pastikan file CSV tidak kosong.');
        }

        $pesan = "Import selesai: {$berhasil} karyawan berhasil ditambahkan.";

        if (!empty($gagal)) {
            // Simpan detail error ke session terpisah agar lebih rapi
            session()->flash('import_errors', $gagal);
            if ($berhasil > 0) {
                return redirect()->route('karyawan.index')
                    ->with('success', $pesan)
                    ->with('import_warnings', true);
            }
            return redirect()->route('karyawan.index')
                ->with('error', "Import gagal semua ({$berhasil} berhasil). Periksa detail di bawah.")
                ->with('import_warnings', true);
        }

        return redirect()->route('karyawan.index')->with('success', $pesan);
    }

    // ── Download Template CSV ─────────────────────────────────────
    public function templateCsv()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_karyawan.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns = ['name','email','password','jabatan','status','nip','nik','no_hp','jenis_kelamin','tempat_lahir','tanggal_lahir','agama','alamat','kopdes'];

        $contoh = [
            ['Budi Santoso','budi@kopdes.id','rahasia123','kasir','aktif','KD-2025-001','3201010101010001','081234567890','L','Jakarta','1990-01-01','Islam','Jl. Mawar No. 1','Kopdes Cijeungjing'],
            ['Siti Rahayu','siti@kopdes.id','rahasia123','petugas_toko','aktif','KD-2025-002','3201010101010002','082345678901','P','Bandung','1995-05-15','Islam','Jl. Melati No. 5','Kopdes Dago'],
        ];

        $callback = function () use ($columns, $contoh) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $columns);
            foreach ($contoh as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}

