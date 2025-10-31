<?php

namespace App\Http\Controllers;

use App\Models\PenciptaArsip;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PenciptaArsipController extends Controller
{
    public function index()
    {
        $penciptaArsip = PenciptaArsip::all();
        return view('pencipta_arsip.index', compact('penciptaArsip'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_departemen' => 'required'
        ],[
            'nama_departemen' => 'Wajib diisi.',
        ]);


        PenciptaArsip::create($request->all());
        Alert::success('Berhasil', 'Data berhasil disimpan.');
        return redirect()->route('pencipta_arsip.index');

    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama_departemen' => 'required']);

        $penciptaArsip = PenciptaArsip::findOrFail($id);
        $penciptaArsip->update($request->all());
        Alert::success('Berhasil', 'Data berhasil diupdate.');
        return redirect()->route('pencipta_arsip.index');
    }

    public function destroy($id)
    {
        $penciptaArsip = PenciptaArsip::findOrFail($id);
        $penciptaArsip->delete();
        Alert::success('Berhasil', 'Data berhasil dihapus.');
        return redirect()->route('pencipta_arsip.index');
    }
}
