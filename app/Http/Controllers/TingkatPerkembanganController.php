<?php

namespace App\Http\Controllers;

use App\Models\TingkatPerkembangan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TingkatPerkembanganController extends Controller
{
    public function index()
    {
        $tingkat = TingkatPerkembangan::all();
        return view('tingkat.index', compact('tingkat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tingkat_perkembangan' => 'required|string|max:255',
        ]);

        TingkatPerkembangan::create($request->all());
        Alert::success('Berhasil', 'Data berhasil disimpan.');
        return redirect()->route('tingkat.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tingkat_perkembangan' => 'required|string|max:255',
        ]);

        $tingkat = TingkatPerkembangan::findOrFail($id);
        $tingkat->update($request->all());
        Alert::success('Berhasil', 'Data berhasil diupdate.');
        return redirect()->route('tingkat.index');
    }

    public function destroy($id)
    {
        $tingkat = TingkatPerkembangan::findOrFail($id);
        $tingkat->delete();
        Alert::success('Berhasil', 'Data berhasil dihapus.');
        return redirect()->route('tingkat.index');
    }
}
