<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Klasifikasi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KlasifikasiController extends Controller
{
    public function index()
    {
        $query = Klasifikasi::query();
        if (request('filter_kode')) {
            $query->where('kode', 'like', '%' . request('filter_kode') . '%');
        }
        if (request('filter_nama')) {
            $query->where('nama', 'like', '%' . request('filter_nama') . '%');
        }
        if (request('filter_urusan')) {
            $query->where('urusan', 'like', '%' . request('filter_urusan') . '%');
        }
        $klasifikasi = $query->orderBy('kode', 'asc')->paginate(10);

        // Builder tree sederhana
        $all = Klasifikasi::orderBy('kode')->get();
        $klasifikasiTree = $all->where('parent_kode', null)->map(function($parent) use ($all) {
            $parent->children = $all->where('parent_kode', $parent->kode)->values();
            return $parent;
        });

        return view('klasifikasi.index', compact('klasifikasi', 'klasifikasiTree'));
    }

    public function create()
    {
        // Form untuk menambah data klasifikasi
        return view('klasifikasi.create');
    }

    public function store(Request $request)
    {
        try {
            // Validasi dasar
            $validated = $request->validate([
                'kode' => 'required|max:50|unique:klasifikasi',
                'urusan' => 'nullable|string|max:255',
                'sub_urusan' => 'nullable|string|max:255',
                'nama' => 'required|max:255',
                'retensi' => 'nullable|integer|min:0',
            ], [
                'kode.required' => 'Kode klasifikasi wajib diisi.',
                'kode.unique' => 'Kode klasifikasi sudah digunakan.',
                'nama.required' => 'Nama/judul wajib diisi.',
                'retensi.integer' => 'Retensi harus berupa angka.',
            ]);

            // Validasi nama harus unik
            $existingNama = Klasifikasi::where('nama', $validated['nama'])->exists();
            if ($existingNama) {
                Alert::error('Gagal', 'Nama klasifikasi sudah digunakan.');
                return back()->withInput();
            }

            // Validasi urusan harus unik jika diisi
            if (!empty($validated['urusan'])) {
                $existingUrusan = Klasifikasi::where('urusan', $validated['urusan'])->exists();
                if ($existingUrusan) {
                    Alert::error('Gagal', 'Urusan sudah digunakan.');
                    return back()->withInput();
                }
            }

            // Validasi sub urusan harus unik jika diisi
            if (!empty($validated['sub_urusan'])) {
                $existingSubUrusan = Klasifikasi::where('sub_urusan', $validated['sub_urusan'])->exists();
                if ($existingSubUrusan) {
                    Alert::error('Gagal', 'Sub urusan sudah digunakan.');
                    return back()->withInput();
                }
            }

            // Observer akan otomatis menghitung parent_kode dan level
            Klasifikasi::create($validated);
            Alert::success('Berhasil', 'Data klasifikasi berhasil disimpan.');
            return redirect()->route('klasifikasi.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $klasifikasi = Klasifikasi::findOrFail($id);
        return view('klasifikasi.edit', compact('klasifikasi'));
    }

    public function update(Request $request, $id)
    {
        $klasifikasi = Klasifikasi::findOrFail($id);

        try {
            $validated = $request->validate([
                'kode' => 'required|max:50',
                'urusan' => 'nullable|string|max:255',
                'sub_urusan' => 'nullable|string|max:255',
                'nama' => 'required|max:255',
                'retensi' => 'nullable|integer|min:0',
            ], [
                'kode.required' => 'Kode klasifikasi wajib diisi.',
                'nama.required' => 'Nama/judul wajib diisi.',
                'retensi.integer' => 'Retensi harus berupa angka.',
            ]);
            // Validasi kode unik kecuali untuk record yang sedang diupdate
            $existingKode = Klasifikasi::where('kode', $validated['kode'])
                ->where('id', '!=', $id)
                ->exists();
            if ($existingKode) {
                Alert::error('Gagal', 'Kode klasifikasi sudah digunakan.');
                return back()->withInput();
            }

            // Validasi nama unik
            $existingNama = Klasifikasi::where('nama', $validated['nama'])
                ->where('id', '!=', $id)
                ->exists();
            if ($existingNama) {
                Alert::error('Gagal', 'Nama klasifikasi sudah digunakan.');
                return back()->withInput();
            }

            // Validasi urusan unik jika diisi
            if (!empty($validated['urusan'])) {
                $existingUrusan = Klasifikasi::where('urusan', $validated['urusan'])
                    ->where('id', '!=', $id)
                    ->exists();
                if ($existingUrusan) {
                    Alert::error('Gagal', 'Urusan sudah digunakan.');
                    return back()->withInput();
                }
            }

            // Validasi sub urusan unik jika diisi
            if (!empty($validated['sub_urusan'])) {
                $existingSubUrusan = Klasifikasi::where('sub_urusan', $validated['sub_urusan'])
                    ->where('id', '!=', $id)
                    ->exists();
                if ($existingSubUrusan) {
                    Alert::error('Gagal', 'Sub urusan sudah digunakan.');
                    return back()->withInput();
                }
            }

            // Observer akan otomatis menghitung parent_kode dan level jika kode berubah
            $klasifikasi->update($validated);
            Alert::success('Berhasil', 'Data klasifikasi berhasil diupdate.');
            return redirect()->route('klasifikasi.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        $klasifikasi = Klasifikasi::findOrFail($id);
        try {
            $klasifikasi->delete();
            Alert::success('Berhasil', 'Data klasifikasi berhasil dihapus.');
            return redirect()->route('klasifikasi.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus data klasifikasi.');
            return redirect()->route('klasifikasi.index');
        }
    }
}
