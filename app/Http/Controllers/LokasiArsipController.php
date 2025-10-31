<?php

namespace App\Http\Controllers;

use App\Models\LokasiArsip;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LokasiArsipController extends Controller
{
    public function index()
    {
        $lokasi = LokasiArsip::all();
        return view('lokasi.index', compact('lokasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruangan' => 'required',
            'gedung' => 'required',
            'lemari' => 'required',
            'rak' => 'required',
            'book' => 'required',
            'folder' => 'required',
        ]);

        LokasiArsip::create($request->all());

        Alert::success('Berhasil', 'Data berhasil disimpan.');
        return redirect()->route('lokasi.index');

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ruangan' => 'required',
            'gedung' => 'required',
            'lemari' => 'required',
            'rak' => 'required',
            'book' => 'required',
            'folder' => 'required',
        ]);

        $lokasi = LokasiArsip::findOrFail($id);
        $lokasi->update($request->all());

        Alert::success('Berhasil', 'Data berhasil Update.');
        return redirect()->route('lokasi.index');;
    }

    public function destroy($id)
    {
        $lokasi = LokasiArsip::findOrFail($id);
        $lokasi ->delete();
        Alert::success('Berhasil', 'Data berhasil dihapus.');
        return redirect()->route('lokasi.index');
    }
}
