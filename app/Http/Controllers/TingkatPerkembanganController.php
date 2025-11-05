<?php

namespace App\Http\Controllers;

use App\Models\TingkatPerkembangan;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TingkatPerkembanganController extends Controller
{
    public function index()
    {
        $tingkat = TingkatPerkembangan::orderBy('created_at', 'desc')->paginate(10);
        return view('tingkat.index', compact('tingkat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tingkat_perkembangan' => 'required|string|max:255',
        ]);

        $tingkat = TingkatPerkembangan::create($request->all());
        // Notifikasi CREATE
        if (function_exists('notifyCreate')) {
            try { notifyCreate('TingkatPerkembangan', $tingkat->tingkat_perkembangan ?? ('ID '.$tingkat->id), route('tingkat.index')); }
            catch (\Throwable $te) { Log::warning('NotifyCreate TingkatPerkembangan gagal: '.$te->getMessage()); }
        }
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
        // Notifikasi UPDATE
        if (function_exists('notifyUpdate')) {
            try { notifyUpdate('TingkatPerkembangan', $tingkat->tingkat_perkembangan ?? ('ID '.$tingkat->id), route('tingkat.index')); }
            catch (\Throwable $te) { Log::warning('NotifyUpdate TingkatPerkembangan gagal: '.$te->getMessage()); }
        }
        Alert::success('Berhasil', 'Data berhasil diupdate.');
        return redirect()->route('tingkat.index');
    }

    public function destroy($id)
    {
        $tingkat = TingkatPerkembangan::findOrFail($id);
        $nama = $tingkat->tingkat_perkembangan; // simpan sebelum delete
        $tingkat->delete();
        // Notifikasi DELETE
        if (function_exists('notifyDelete')) {
            try { notifyDelete('TingkatPerkembangan', $nama ?? ('ID '.$tingkat->id)); }
            catch (\Throwable $te) { Log::warning('NotifyDelete TingkatPerkembangan gagal: '.$te->getMessage()); }
        }
        Alert::success('Berhasil', 'Data berhasil dihapus.');
        return redirect()->route('tingkat.index');
    }
}
