<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\NasibAkhir;
use App\Models\Klasifikasi;
use App\Models\LokasiArsip;
use Illuminate\Http\Request;
use App\Models\UnitPengelola;
use App\Models\TingkatPerkembangan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class KeuanganController extends Controller
{
    public function index()
    {
        Log::info('Fetching all Keuangans');
        $keuangans = Keuangan::with(['klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelola'])->get();
        Log::info('Fetched Keuangans: ' . $keuangans->count() . ' records');

        return view('keuangan.index', compact('keuangans'));
    }

    public function show(Keuangan $keuangan)
    {
        Log::info('Showing details for Keuangan with ID: ' . $keuangan->id);
        return view('keuangan.show', compact('keuangan'));
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

        return view('keuangan.create', compact('klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelolas'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called');
        Log::info('Request Data: ', $request->all());

        // Validate input
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

        // Handle file upload
        if ($request->hasFile('file_path')) {
            Log::info('File detected for upload');
            $filePath = $request->file('file_path')->store('keuangan_file', 'public');
            Log::info('File uploaded to: ' . $filePath);
            $validated['file_path'] = $filePath;
        }

        // Create the HKT record
        Keuangan::create($validated);
        Alert::success('Berhasil', 'Data berhasil disimpan.');
        Log::info('KEUANGAN record successfully created');
        return redirect()->route('keuangan.index');
    }

    public function edit(Keuangan $keuangan)
    {
        Log::info('Fetching data for editing Keuangan with ID: ' . $keuangan->id);
        $klasifikasi = Klasifikasi::all();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();
        $unitPengelola = UnitPengelola::all();

        Log::info('Fetched data for edit form: klasifikasi, tingkatPerkembangan, lokasiArsip, nasibAkhir, unitPengelola');

        return view('keuangan.edit', compact('keuangan', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelola'));
    }

    public function update(Request $request, Keuangan $keuangan)
    {
        Log::info('Update method called for Keuangan with ID: ' . $keuangan->id);
        Log::info('Request Data for Update: ', $request->all());

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
            'nasib_akhir_id' => 'required|exists:nasib_akhirs,id',
            'file_path' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        Log::info('Validated Data for Update: ', $validated);

        // Handle file upload
        if ($request->hasFile('file_path')) {
            Log::info('New file detected for update');

            if ($keuangan->file_path) {
                Log::info('Deleting old file');
                Storage::disk('public')->delete($keuangan->file_path);
            }

            $filePath = $request->file('file_path')->store('keuangans_files', 'public');
            $validated['file_path'] = $filePath;
            Log::info('New file uploaded to: ' . $filePath);
        }

        $keuangan->update($validated);
        Log::info('Keuangan with ID ' . $keuangan->id . ' successfully updated');
        Alert::success('Berhasil', 'Data Keuangan berhasil diupdate.');
        return redirect()->route('keuangan.index');
    }

    public function destroy(Keuangan $keuangan)
    {
        Log::info('Destroy method called for Keuangan with ID: ' . $keuangan->id);

        // Delete file from storage
        if ($keuangan->file_path) {
            Log::info('Deleting file from storage: ' . $keuangan->file_path);
            Storage::disk('public')->delete($keuangan->file_path);
        }

        $keuangan->delete();
        Log::info('Keuangan with ID ' . $keuangan->id . ' successfully deleted');
        Alert::success('Berhasil', 'Data Keuangan berhasil dihapus.');
        return redirect()->route('keuangan.index');
    }
}
