<?php

namespace App\Http\Controllers;

use App\Models\PenciptaArsip;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PenciptaArsipController extends Controller
{
    public function index()
    {
        $penciptaArsip = PenciptaArsip::orderBy('created_at', 'desc')->paginate(10);
        return view('pencipta_arsip.index', compact('penciptaArsip'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_departemen' => 'required'
        ],[
            'nama_departemen' => 'Wajib diisi.',
        ]);


        $record = PenciptaArsip::create($request->all());
        // Notifikasi CREATE
        if (function_exists('notifyCreate')) {
            try {
                notifyCreate('PenciptaArsip', $record->nama_departemen ?? ('ID '.$record->id), route('pencipta_arsip.index'));
            } catch (\Throwable $te) {
                \Log::warning('NotifyCreate PenciptaArsip gagal: '.$te->getMessage());
            }
        }
        Alert::success('Berhasil', 'Data berhasil disimpan.');
        return redirect()->route('pencipta_arsip.index');

    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama_departemen' => 'required']);

        $penciptaArsip = PenciptaArsip::findOrFail($id);
        $penciptaArsip->update($request->all());
        // Notifikasi UPDATE
        if (function_exists('notifyUpdate')) {
            try {
                notifyUpdate('PenciptaArsip', $penciptaArsip->nama_departemen ?? ('ID '.$penciptaArsip->id), route('pencipta_arsip.index'));
            } catch (\Throwable $te) {
                \Log::warning('NotifyUpdate PenciptaArsip gagal: '.$te->getMessage());
            }
        }
        Alert::success('Berhasil', 'Data berhasil diupdate.');
        return redirect()->route('pencipta_arsip.index');
    }

    public function destroy($id)
    {
        $penciptaArsip = PenciptaArsip::findOrFail($id);
        $nama = $penciptaArsip->nama_departemen; // simpan sebelum delete
        $penciptaArsip->delete();
        // Notifikasi DELETE
        if (function_exists('notifyDelete')) {
            try {
                notifyDelete('PenciptaArsip', $nama ?? ('ID '.$penciptaArsip->id));
            } catch (\Throwable $te) {
                \Log::warning('NotifyDelete PenciptaArsip gagal: '.$te->getMessage());
            }
        }
        Alert::success('Berhasil', 'Data berhasil dihapus.');
        return redirect()->route('pencipta_arsip.index');
    }
}
