<?php

namespace App\Http\Controllers;

use App\Models\Kopdes;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class KopdesController extends Controller
{
    public function index(Request $request)
    {
        $query = Kopdes::withCount('users')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('nama', 'like', "%{$s}%")
                ->orWhere('alamat', 'like', "%{$s}%")
                ->orWhere('provinsi', 'like', "%{$s}%")
                ->orWhere('kabupaten', 'like', "%{$s}%");
        }

        $kopdes = $query->paginate(10)->withQueryString();

        return view('kopdes.index', compact('kopdes'));
    }

    public function create()
    {
        return view('kopdes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'      => ['required', 'string', 'max:150'],
            'alamat'    => ['required', 'string'],
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'desa'      => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'provinsi'  => ['nullable', 'string', 'max:100'],
        ]);

        $kopdes = Kopdes::create($data);

        LogAktivitas::catat(
            'tambah_kopdes',
            "Mendaftarkan Kopdes baru: {$kopdes->nama} di {$kopdes->provinsi}"
        );

        return redirect()->route('kopdes.index')
            ->with('success', "Kopdes {$kopdes->nama} berhasil didaftarkan.");
    }

    public function show(Kopdes $kopde) // Note: route model binding matches parameter name `kopde` or `kopdes` based on routes
    {
        // Fitur "Inspect" untuk melihat siapa saja karyawan/pegawai yang terdaftar di Kopdes ini
        $kopde->load(['users' => fn($q) => $q->latest()]);
        return view('kopdes.show', compact('kopde'));
    }

    public function edit(Kopdes $kopde)
    {
        return view('kopdes.edit', compact('kopde'));
    }

    public function update(Request $request, Kopdes $kopde)
    {
        $data = $request->validate([
            'nama'      => ['required', 'string', 'max:150'],
            'alamat'    => ['required', 'string'],
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'desa'      => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'provinsi'  => ['nullable', 'string', 'max:100'],
        ]);

        $kopde->update($data);

        LogAktivitas::catat(
            'edit_kopdes',
            "Mengubah data Kopdes: {$kopde->nama}"
        );

        return redirect()->route('kopdes.index')
            ->with('success', "Data Kopdes {$kopde->nama} berhasil diperbarui.");
    }

    public function destroy(Kopdes $kopde)
    {
        $nama = $kopde->nama;
        $kopde->delete();

        LogAktivitas::catat(
            'hapus_kopdes',
            "Menghapus Kopdes: {$nama}"
        );

        return redirect()->route('kopdes.index')
            ->with('success', "Kopdes {$nama} berhasil dihapus.");
    }
}
