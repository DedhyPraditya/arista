<?php

namespace App\Http\Controllers;

use App\Models\Kelembagaan;
use App\Models\NasibAkhir;
use App\Models\Klasifikasi;
use App\Models\LokasiArsip;
use Illuminate\Http\Request;
use App\Models\UnitPengelola;
use App\Models\TingkatPerkembangan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class KelembagaanController extends Controller
{
    public function index()
    {
        Log::info('Fetching all Kelembagaans');
        $kelembagaans = Kelembagaan::all();
        Log::info('Fetched Kelembagaans: ' . $kelembagaans->count() . ' records');

        return view('kelembagaan.index', compact('kelembagaans'));
    }

    public function show(Kelembagaan $kelembagaan)
    {
        Log::info('Showing details for Kelembagaan with ID: ' . $kelembagaan->id);
        return view('kelembagaan.show', compact('kelembagaan'));
    }

    public function create()
    {
        Log::info('Fetching dropdown data for create form');
        $klasifikasi = Klasifikasi::all();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();
        $unitPengelolas = UnitPengelola::all();

        Log::info('Fetched data for dropdowns: klasifikasi, tingkatPerkembangan, lokasiArsip, nasibAkhir, unitPengelola');

        return view('kelembagaan.create', compact('klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelolas'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called');
        Log::info('Request Data: ', $request->all());

        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tahun_surat' => 'required|integer',
            'pencipta_arsip' => 'required|string|max:255',
            'unit_pengelola_id' => 'required|exists:unit_pengelolas,id',
            'kode_klasifikasi_id' => 'required|exists:klasifikasi,id',
            'prihal' => 'required|string|max:255',
            'uraian_informasi' => 'required|string',
            'tingkat_perkembangan_id' => 'required|exists:tingkat_perkembangans,id',
            'lokasi_arsip_id' => 'required|exists:lokasi_arsips,id',
            'jumlah_item' => 'required|integer',
            'lampiran' => 'nullable|string',
            'retensi' => 'required|integer',
            'keterangan' => 'required|string|in:Aktif,Inaktif',
            'nasib_akhir_id' => 'required|exists:nasib_akhir,id',
            'file_path' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('file_path')) {
            Log::info('File detected for upload');
            $filePath = $request->file('file_path')->store('kelembagaan_files', 'public');
            $validated['file_path'] = $filePath;
            Log::info('File uploaded to: ' . $filePath);
        }

        Kelembagaan::create($validated);
        Alert::success('Berhasil', 'Data berhasil disimpan.');
        Log::info('Kelembagaan record successfully created');
        return redirect()->route('kelembagaan.index');
    }

    public function edit(Kelembagaan $kelembagaan)
    {
        Log::info('Fetching data for editing Kelembagaan with ID: ' . $kelembagaan->id);
        $klasifikasi = Klasifikasi::all();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();
        $unitPengelola = UnitPengelola::all();

        return view('kelembagaan.edit', compact('kelembagaan', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelola'));
    }

    public function update(Request $request, Kelembagaan $kelembagaan)
    {
        Log::info('Update method called for Kelembagaan with ID: ' . $kelembagaan->id);

        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tahun_surat' => 'required|integer',
            'pencipta_arsip' => 'required|string|max:255',
            'unit_pengelola_id' => 'required|exists:unit_pengelolas,id',
            'kode_klasifikasi_id' => 'required|exists:klasifikasi,id',
            'prihal' => 'required|string|max:255',
            'uraian_informasi' => 'required|string',
            'tingkat_perkembangan_id' => 'required|exists:tingkat_perkembangans,id',
            'lokasi_arsip_id' => 'required|exists:lokasi_arsips,id',
            'jumlah_item' => 'required|integer',
            'lampiran' => 'nullable|string',
            'retensi' => 'required|integer',
            'keterangan' => 'required|string|in:Aktif,Inaktif',
            'nasib_akhir_id' => 'required|exists:nasib_akhir,id',
            'file_path' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('file_path')) {
            if ($kelembagaan->file_path) {
                Storage::disk('public')->delete($kelembagaan->file_path);
            }
            $filePath = $request->file('file_path')->store('kelembagaan_files', 'public');
            $validated['file_path'] = $filePath;
        }

        $kelembagaan->update($validated);
        Alert::success('Berhasil', 'Data Kelembagaan berhasil diupdate.');
        return redirect()->route('kelembagaan.index');
    }

    public function destroy(Kelembagaan $kelembagaan)
    {
        if ($kelembagaan->file_path) {
            Storage::disk('public')->delete($kelembagaan->file_path);
        }

        $kelembagaan->delete();
        Alert::success('Berhasil', 'Data Kelembagaan berhasil dihapus.');
        return redirect()->route('kelembagaan.index');
    }
}
