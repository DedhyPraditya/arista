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
        $klasifikasi = Klasifikasi::all();
        return view('klasifikasi.index', compact('klasifikasi'));
    }

    public function create()
    {
        // Form untuk menambah data klasifikasi
        return view('klasifikasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:klasifikasi|max:10',
            'nama' => 'required|max:255',
            'retensi' => 'required|integer',
        ], [
            'kode.required' => 'Kode wajib diisi.',
            'kode.unique' => 'Kode sudah digunakan.',
            'retensi.integer' => 'Retensi harus berupa angka.',
        ]);

        Klasifikasi::create($request->all());

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
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'retensi' => 'required|integer',
        ]);

        $klasifikasi = Klasifikasi::findOrFail($id);
        $klasifikasi->update($request->all());

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
