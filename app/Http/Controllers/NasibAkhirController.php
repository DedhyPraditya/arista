<?php

namespace App\Http\Controllers;

use App\Models\NasibAkhir;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class NasibAkhirController extends Controller
{
    public function index()
    {
        $nasibAkhir = NasibAkhir::orderBy('created_at', 'desc')->paginate(10);
        return view('nasib.index', compact('nasibAkhir'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nasib_akhir' => 'required|string|max:255',
        ]);

        $nasib = NasibAkhir::create($request->all());
        // Notifikasi CREATE
        if (function_exists('notifyCreate')) {
            try { notifyCreate('NasibAkhir', $nasib->nasib_akhir ?? ('ID '.$nasib->id), route('nasib.index')); }
            catch (\Throwable $te) { Log::warning('NotifyCreate NasibAkhir gagal: '.$te->getMessage()); }
        }
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
        // Notifikasi UPDATE
        if (function_exists('notifyUpdate')) {
            try { notifyUpdate('NasibAkhir', $nasibAkhir->nasib_akhir ?? ('ID '.$nasibAkhir->id), route('nasib.index')); }
            catch (\Throwable $te) { Log::warning('NotifyUpdate NasibAkhir gagal: '.$te->getMessage()); }
        }
        Alert::success('Berhasil', 'Data berhasil diperbarui.');
        return redirect()->route('nasib.index');
    }

    public function destroy($id)
    {
        $nasibAkhir = NasibAkhir::findOrFail($id);
        $nama = $nasibAkhir->nasib_akhir; // simpan sebelum delete
        $nasibAkhir->delete();
        // Notifikasi DELETE
        if (function_exists('notifyDelete')) {
            try { notifyDelete('NasibAkhir', $nama ?? ('ID '.$nasibAkhir->id)); }
            catch (\Throwable $te) { Log::warning('NotifyDelete NasibAkhir gagal: '.$te->getMessage()); }
        }
        Alert::success('Berhasil', 'Data berhasil dihapus.');
        return redirect()->route('nasib.index');
    }
}
