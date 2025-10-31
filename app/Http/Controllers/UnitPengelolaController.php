<?php

namespace App\Http\Controllers;

use App\Models\UnitPengelola;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class UnitPengelolaController extends Controller
{
    public function index()
    {
        $units = UnitPengelola::all();
        return view('unit.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_pengelola' => 'required|string|max:255',
        ]);

        UnitPengelola::create($request->all());
        Alert::success('Berhasil', 'Data berhasil disimpan.');

        // Perbaikan dari routeroute menjadi route
        return redirect()->route('unit.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'unit_pengelola' => 'required|string|max:255',
        ]);

        $units = UnitPengelola::findOrFail($id);
        $units->update($request->all());
        Alert::success('Berhasil', 'Data berhasil diupdate.');

        // Perbaikan dari routeroute menjadi route
        return redirect()->route('unit.index');
    }

    public function destroy($id)
    {
        $unit = UnitPengelola::findOrFail($id);
        $unit->delete();
        Alert::success('Berhasil', 'Data berhasil dihapus.');

        // Perbaikan dari routeroute menjadi route
        return redirect()->route('unit.index');
    }
}
