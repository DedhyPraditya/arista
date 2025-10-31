<?php

namespace App\Http\Controllers;

use App\Models\Kemahasiswaan;
use App\Models\NasibAkhir;
use App\Models\Klasifikasi;
use App\Models\LokasiArsip;
use Illuminate\Http\Request;
use App\Models\UnitPengelola;
use App\Models\TingkatPerkembangan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class KemahasiswaanController extends Controller
{
    public function index()
    {
        Log::info('Fetching all Kemahasiswaan records');
        $kemahasiswaan = Kemahasiswaan::with(['unitPengelola', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'])->get();
        Log::info('Fetched Kemahasiswaan: ' . $kemahasiswaan->count() . ' records');

        return view('kemahasiswaan.index', compact('kemahasiswaan'));
    }

    public function show(Kemahasiswaan $kemahasiswaan)
    {
        Log::info('Showing details for Kemahasiswaan with ID: ' . $kemahasiswaan->id);
        return view('kemahasiswaan.show', compact('kemahasiswaan'));
    }

    public function create()
    {
        Log::info('Fetching dropdown data for create form');
        $unitPengelola = UnitPengelola::all();
        $klasifikasi = Klasifikasi::all();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();

        Log::info('Fetched data for dropdowns: unitPengelola, klasifikasi, tingkatPerkembangan, lokasiArsip, nasibAkhir');

        return view('kemahasiswaan.create', compact('unitPengelola', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called for Kemahasiswaan');
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
            'retensi' => 'required|integer',
            'keterangan' => 'required|string|in:Aktif,Inaktif',
            'nasib_akhir_id' => 'required|exists:nasib_akhir,id',
            'jumlah_item' => 'required|integer',
            'lampiran' => 'nullable|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('file_path')) {
            Log::info('File detected for upload');
            $filePath = $request->file('file_path')->store('kemahasiswaan_files', 'public');
            Log::info('File uploaded to: ' . $filePath);
            $validated['file_path'] = $filePath;
        }

        // Create the Kemahasiswaan record
        Kemahasiswaan::create($validated);
        Alert::success('Berhasil', 'Data Kemahasiswaan berhasil disimpan.');
        Log::info('Kemahasiswaan record successfully created');
        return redirect()->route('kemahasiswaan.index');
    }

    public function edit(Kemahasiswaan $kemahasiswaan)
    {
        Log::info('Fetching data for editing Kemahasiswaan with ID: ' . $kemahasiswaan->id);
        $unitPengelola = UnitPengelola::all();
        $klasifikasi = Klasifikasi::all();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();

        Log::info('Fetched data for edit form: unitPengelola, klasifikasi, tingkatPerkembangan, lokasiArsip, nasibAkhir');

        return view('kemahasiswaan.edit', compact('kemahasiswaan', 'unitPengelola', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'));
    }

    public function update(Request $request, Kemahasiswaan $kemahasiswaan)
    {
        Log::info('Update method called for Kemahasiswaan with ID: ' . $kemahasiswaan->id);
        Log::info('Request Data for Update: ', $request->all());

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
            'retensi' => 'required|integer',
            'keterangan' => 'required|string|in:Aktif,Inaktif',
            'nasib_akhir_id' => 'required|exists:nasib_akhirs,id',
            'jumlah_item' => 'required|integer',
            'lampiran' => 'nullable|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        Log::info('Validated Data for Update: ', $validated);

        // Handle file upload
        if ($request->hasFile('file_path')) {
            Log::info('New file detected for update');

            if ($kemahasiswaan->file_path) {
                Log::info('Deleting old file');
                Storage::disk('public')->delete($kemahasiswaan->file_path);
            }

            $filePath = $request->file('file_path')->store('kemahasiswaan_files', 'public');
            $validated['file_path'] = $filePath;
            Log::info('New file uploaded to: ' . $filePath);
        }

        $kemahasiswaan->update($validated);
        Log::info('Kemahasiswaan with ID ' . $kemahasiswaan->id . ' successfully updated');
        Alert::success('Berhasil', 'Data Kemahasiswaan berhasil diupdate.');
        return redirect()->route('kemahasiswaan.index');
    }

    public function destroy(Kemahasiswaan $kemahasiswaan)
    {
        Log::info('Destroy method called for Kemahasiswaan with ID: ' . $kemahasiswaan->id);

        // Delete file from storage
        if ($kemahasiswaan->file_path) {
            Log::info('Deleting file from storage: ' . $kemahasiswaan->file_path);
            Storage::disk('public')->delete($kemahasiswaan->file_path);
        }

        $kemahasiswaan->delete();
        Log::info('Kemahasiswaan with ID ' . $kemahasiswaan->id . ' successfully deleted');
        Alert::success('Berhasil', 'Data Kemahasiswaan berhasil dihapus.');
        return redirect()->route('kemahasiswaan.index');
    }
}
