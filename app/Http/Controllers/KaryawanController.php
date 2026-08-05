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

        // Cek ekstensi manual (lebih toleran dari MIME check)
        $ekstensi = strtolower($file->getClientOriginalExtension());
        if (!in_array($ekstensi, ['csv', 'txt'])) {
            return back()->with('error', 'File harus berformat .csv');
        }

        $path    = $file->getRealPath();
        $konten  = file_get_contents($path);

        // ── Strip BOM (Byte Order Mark) yang ditambahkan Excel ────
        $konten  = preg_replace('/^\xEF\xBB\xBF/', '', $konten);

        // Simpan ke file temp agar bisa dibaca fgetcsv
        $tmp = tmpfile();
        fwrite($tmp, $konten);
        rewind($tmp);

        // ── Auto-detect delimiter: coba koma dulu, kalau gagal coba titik koma ──
        $barisPertama = fgets($tmp);
        rewind($tmp);
        $delimiter = (substr_count($barisPertama, ';') > substr_count($barisPertama, ',')) ? ';' : ',';

        // ── Baca header, skip baris kosong di awal ────────────────
        $header = null;
        while (($row = fgetcsv($tmp, 0, $delimiter)) !== false) {
            if (empty(array_filter($row))) continue; // skip baris kosong
            // Strip BOM dari cell pertama header (jaga-jaga)
            $row[0] = preg_replace('/^\xEF\xBB\xBF/', '', $row[0]);
            // Trim semua nama kolom
            $header = array_map(fn($h) => strtolower(trim($h)), $row);
            break;
        }

        if (!$header || !in_array('name', $header) || !in_array('email', $header)) {
            fclose($tmp);
            return back()->with('error', 'Format CSV tidak valid. Pastikan baris pertama berisi header: name, email, password, jabatan, dst. Unduh template untuk contoh yang benar.');
        }

        $berhasil     = 0;
        $gagal        = [];
        $baris        = 1;
        $jabatanValid = ['admin','ketua','sekretaris','bendahara','kasir','petugas_toko'];
        $statusValid  = ['aktif','nonaktif','cuti'];

        // ── Proses baris data ─────────────────────────────────────
        while (($row = fgetcsv($tmp, 0, $delimiter)) !== false) {
            $baris++;

            // Skip baris kosong
            if (empty(array_filter($row))) continue;

            // Sesuaikan jumlah kolom agar array_combine tidak error
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

            if (!$name)     $errors[] = 'name kosong';
            if (!$email)    $errors[] = 'email kosong';
            if (!$password) $errors[] = 'password kosong';

            if (!empty($errors)) {
                $gagal[] = "Baris {$baris}: " . implode(', ', $errors) . '.';
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $gagal[] = "Baris {$baris}: format email '{$email}' tidak valid.";
                continue;
            }

            if (User::where('email', $email)->exists()) {
                $gagal[] = "Baris {$baris}: email '{$email}' sudah terdaftar, dilewati.";
                continue;
            }

            if (!in_array($jabatan, $jabatanValid)) {
                $gagal[] = "Baris {$baris}: jabatan '{$jabatan}' tidak dikenal. Pilihan: " . implode(', ', $jabatanValid) . '.';
                continue;
            }

            if (!in_array($status, $statusValid)) {
                $status = 'aktif'; // fallback, tidak blok
            }

            $nip = trim($data['nip'] ?? '');
            $nik = trim($data['nik'] ?? '');

            $nip = trim($data['nip'] ?? '');
            $nik = trim($data['nik'] ?? '');
            $kopdesName = trim($data['kopdes'] ?? '');
            $kopdesId = null;

            if ($kopdesName) {
                $kopdesId = \App\Models\Kopdes::where('nama', 'like', "%{$kopdesName}%")->value('id');
            }

            if ($nip && User::where('nip', $nip)->exists()) {
                $gagal[] = "Baris {$baris}: NIP '{$nip}' sudah terdaftar, dilewati.";
                continue;
            }

            // NIK: hanya validasi jika diisi DAN bukan 16 digit → warning tapi tetap import
            if ($nik && strlen($nik) !== 16) {
                // Lanjutkan import tapi NIK dikosongkan & catat peringatan
                $gagal[] = "Baris {$baris}: NIK '{$nik}' bukan 16 digit, kolom NIK dilewati (data lain tetap diimport).";
                $nik = '';
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
                    'tanggal_lahir' => trim($data['tanggal_lahir'] ?? '') ?: null,
                    'agama'         => trim($data['agama']         ?? '') ?: null,
                    'alamat'        => trim($data['alamat']        ?? '') ?: null,
                    'kopdes_id'     => $kopdesId,
                ]);
                $berhasil++;
            } catch (\Exception $e) {
                $gagal[] = "Baris {$baris}: gagal disimpan — {$e->getMessage()}.";
            }
        }

        fclose($tmp);

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

