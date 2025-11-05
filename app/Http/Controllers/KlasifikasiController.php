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
        $validated = $request->validate([
            'kode' => 'required|max:50',
            'urusan' => 'nullable|string|max:255',
            'sub_urusan' => 'nullable|string',
            'nama' => 'required|max:255',
            'retensi' => 'nullable|integer|min:0',
        ], [
            'kode.required' => 'Kode klasifikasi wajib diisi.',
            'nama.required' => 'Nama/judul wajib diisi.',
            'retensi.integer' => 'Retensi harus berupa angka.',
        ]);

        // Validasi: nama harus unik di seluruh tabel
        $exists = \App\Models\Klasifikasi::where('nama', $validated['nama'])->exists();
        if ($exists) {
            return back()->withErrors(['Nama klasifikasi sudah digunakan.'])->withInput();
        }

        // Validasi custom: kombinasi kode, nama, urusan, sub_urusan harus unik
        $exists = \App\Models\Klasifikasi::where('kode', $validated['kode'])
            ->where('nama', $validated['nama'])
            ->where('urusan', $validated['urusan'])
            ->where('sub_urusan', $validated['sub_urusan'])
            ->exists();
        if ($exists) {
            return back()->withErrors(['Data klasifikasi dengan kombinasi tersebut sudah ada.'])->withInput();
        }

        // Derive hierarchy attributes from kode
        $segments = explode('.', $validated['kode']);
        $parentKode = count($segments) > 1 ? implode('.', array_slice($segments, 0, -1)) : null;
        $level = count($segments) - 1; // root = 0
        $isLeaf = true; // diasumsikan leaf saat dibuat; akan berubah jika nanti ditambah anak

        // Normalisasi retensi: parent boleh null
        if (!array_key_exists('retensi', $validated) || $validated['retensi'] === '' ) {
            $validated['retensi'] = null;
        }

        $validated['parent_kode'] = $parentKode;
        $validated['level'] = $level;
        $validated['is_leaf'] = $isLeaf;

        Klasifikasi::create($validated);

        Alert::success('Berhasil', 'Data klasifikasi berhasil disimpan.');
        return redirect()->route('klasifikasi.index');
    }

    public function edit($id)
    {
        $klasifikasi = Klasifikasi::findOrFail($id);
        return view('klasifikasi.edit', compact('klasifikasi'));
    }

    public function update(Request $request, $id)
    {
        $klasifikasi = Klasifikasi::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|max:50',
            'urusan' => 'nullable|string|max:255',
            'sub_urusan' => 'nullable|string',
            'nama' => 'required|max:255',
            'retensi' => 'nullable|integer|min:0',
        ], [
            'kode.required' => 'Kode klasifikasi wajib diisi.',
            'nama.required' => 'Nama/judul wajib diisi.',
            'retensi.integer' => 'Retensi harus berupa angka.',
        ]);

        // Recalculate hierarchy (if kode berubah)
        $segments = explode('.', $validated['kode']);
        $parentKode = count($segments) > 1 ? implode('.', array_slice($segments, 0, -1)) : null;
        $level = count($segments) - 1;

        if (!array_key_exists('retensi', $validated) || $validated['retensi'] === '') {
            $validated['retensi'] = null; // parent atau tidak diisi
        }

        $validated['parent_kode'] = $parentKode;
        $validated['level'] = $level;
        // is_leaf tidak otomatis diubah di sini; perubahan struktur anak akan ditangani via backfill command

        $klasifikasi->update($validated);

        Alert::success('Berhasil', 'Data klasifikasi berhasil diupdate.');
        return redirect()->route('klasifikasi.index');
    }

    public function destroy($id)
    {
        $klasifikasi = Klasifikasi::findOrFail($id);
        $klasifikasi->delete();

        Alert::success('Berhasil', 'Data klasifikasi berhasil dihapus.');
        return redirect()->route('klasifikasi.index');
    }

}
