<?php

namespace App\Http\Controllers;

use App\Models\NasibAkhir;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class NasibAkhirController extends Controller
{
    public function index()
    {
        $nasibAkhir = NasibAkhir::all();
        return view('nasib.index', compact('nasibAkhir'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nasib_akhir' => 'required|string|max:255',
        ]);

        NasibAkhir::create($request->all());
        Alert::success('Berhasil', 'Data berhasil disimpan.');
        return redirect()->route('nasib.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nasib_akhir' => 'required|string|max:255',
        ]);

        $nasibAkhir = NasibAkhir::findOrFail($id);
        $nasibAkhir->update($request->all());
        Alert::success('Berhasil', 'Data berhasil diperbarui.');
        return redirect()->route('nasib.index');
    }

    public function destroy($id)
    {
        $nasibAkhir = NasibAkhir::findOrFail($id);
        $nasibAkhir->delete();
        Alert::success('Berhasil', 'Data berhasil dihapus.');
        return redirect()->route('nasib.index');
    }
}
