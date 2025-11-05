<?php

namespace App\Http\Controllers;

use App\Models\LokasiArsip;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LokasiArsipController extends Controller
{
    public function index()
    {
        $lokasi = LokasiArsip::orderBy('created_at', 'desc')->paginate(10);
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

        $lokasi = LokasiArsip::create($request->all());
        // Notifikasi CREATE
        if (function_exists('notifyCreate')) {
            try {
                $identifier = trim(($lokasi->ruangan ?? '').' '.$lokasi->lemari ?? '') ?: ('ID '.$lokasi->id);
                notifyCreate('LokasiArsip', $identifier, route('lokasi.index'));
            } catch (\Throwable $te) { Log::warning('NotifyCreate LokasiArsip gagal: '.$te->getMessage()); }
        }

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
        // Notifikasi UPDATE
        if (function_exists('notifyUpdate')) {
            try {
                $identifier = trim(($lokasi->ruangan ?? '').' '.$lokasi->lemari ?? '') ?: ('ID '.$lokasi->id);
                notifyUpdate('LokasiArsip', $identifier, route('lokasi.index'));
            } catch (\Throwable $te) { Log::warning('NotifyUpdate LokasiArsip gagal: '.$te->getMessage()); }
        }

        Alert::success('Berhasil', 'Data berhasil Update.');
        return redirect()->route('lokasi.index');
    }

    public function destroy($id)
    {
        $lokasi = LokasiArsip::findOrFail($id);
        $identifier = trim(($lokasi->ruangan ?? '').' '.$lokasi->lemari ?? '') ?: ('ID '.$lokasi->id);
        $lokasi ->delete();
        // Notifikasi DELETE
        if (function_exists('notifyDelete')) {
            try { notifyDelete('LokasiArsip', $identifier); } catch (\Throwable $te) { Log::warning('NotifyDelete LokasiArsip gagal: '.$te->getMessage()); }
        }
        Alert::success('Berhasil', 'Data berhasil dihapus.');
        return redirect()->route('lokasi.index');
    }
}
