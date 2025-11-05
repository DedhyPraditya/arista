<?php

namespace App\Http\Controllers;

use App\Models\UnitPengelola;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class UnitPengelolaController extends Controller
{
    public function index()
    {
        $units = UnitPengelola::orderBy('created_at', 'desc')->paginate(10);
        return view('unit.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_pengelola' => 'required|string|max:255',
        ]);

        $unit = UnitPengelola::create($request->all());
        // Notifikasi CREATE
        if (function_exists('notifyCreate')) {
            try { notifyCreate('UnitPengelola', $unit->unit_pengelola ?? ('ID '.$unit->id), route('unit.index')); }
            catch (\Throwable $te) { Log::warning('NotifyCreate UnitPengelola gagal: '.$te->getMessage()); }
        }
        Alert::success('Berhasil', 'Data berhasil disimpan.');
        return redirect()->route('unit.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'unit_pengelola' => 'required|string|max:255',
        ]);

        $units = UnitPengelola::findOrFail($id);
        $units->update($request->all());
        // Notifikasi UPDATE
        if (function_exists('notifyUpdate')) {
            try { notifyUpdate('UnitPengelola', $units->unit_pengelola ?? ('ID '.$units->id), route('unit.index')); }
            catch (\Throwable $te) { Log::warning('NotifyUpdate UnitPengelola gagal: '.$te->getMessage()); }
        }
        Alert::success('Berhasil', 'Data berhasil diupdate.');
        return redirect()->route('unit.index');
    }

    public function destroy($id)
    {
        $unit = UnitPengelola::findOrFail($id);
        $nama = $unit->unit_pengelola; // simpan sebelum delete
        $unit->delete();
        // Notifikasi DELETE
        if (function_exists('notifyDelete')) {
            try { notifyDelete('UnitPengelola', $nama ?? ('ID '.$unit->id)); }
            catch (\Throwable $te) { Log::warning('NotifyDelete UnitPengelola gagal: '.$te->getMessage()); }
        }
        Alert::success('Berhasil', 'Data berhasil dihapus.');
        return redirect()->route('unit.index');
    }
}
