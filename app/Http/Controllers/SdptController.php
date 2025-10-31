<?php

namespace App\Http\Controllers;

use App\Models\Sdpt;
use App\Models\NasibAkhir;
use App\Models\Klasifikasi;
use App\Models\LokasiArsip;
use Illuminate\Http\Request;
use App\Models\UnitPengelola;
use App\Models\TingkatPerkembangan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class SdptController extends Controller
{
    public function index()
    {
        Log::info('Fetching all Sdpt records');
        $sdpt = Sdpt::with(['unitPengelola', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'])->get();
        Log::info('Fetched Sdpt: ' . $sdpt->count() . ' records');

        return view('sdpt.index', compact('sdpt'));
    }

    public function show(Sdpt $sdpt)
    {
        Log::info('Showing details for Sdpt with ID: ' . $sdpt->id);
        return view('sdpt.show', compact('sdpt'));
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

        return view('sdpt.create', compact('unitPengelola', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called for Sdpt');
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
            $filePath = $request->file('file_path')->store('sdpt_files', 'public');
            Log::info('File uploaded to: ' . $filePath);
            $validated['file_path'] = $filePath;
        }

        // Create the Sdpt record
        Sdpt::create($validated);
        Alert::success('Berhasil', 'Data Sdpt berhasil disimpan.');
        Log::info('Sdpt record successfully created');
        return redirect()->route('sdpt.index');
    }

    public function edit(Sdpt $sdpt)
    {
        Log::info('Fetching data for editing Sdpt with ID: ' . $sdpt->id);
        $unitPengelola = UnitPengelola::all();
        $klasifikasi = Klasifikasi::all();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();

        Log::info('Fetched data for edit form: unitPengelola, klasifikasi, tingkatPerkembangan, lokasiArsip, nasibAkhir');

        return view('sdpt.edit', compact('sdpt', 'unitPengelola', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'));
    }

    public function update(Request $request, Sdpt $sdpt)
    {
        Log::info('Update method called for Sdpt with ID: ' . $sdpt->id);
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
            'nasib_akhir_id' => 'required|exists:nasib_akhir,id',
            'jumlah_item' => 'required|integer',
            'lampiran' => 'nullable|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        Log::info('Validated Data for Update: ', $validated);

        // Handle file upload
        if ($request->hasFile('file_path')) {
            Log::info('New file detected for update');

            if ($sdpt->file_path) {
                Log::info('Deleting old file');
                Storage::disk('public')->delete($sdpt->file_path);
            }

            $filePath = $request->file('file_path')->store('sdpt_files', 'public');
            $validated['file_path'] = $filePath;
            Log::info('New file uploaded to: ' . $filePath);
        }

        $sdpt->update($validated);
        Log::info('Sdpt with ID ' . $sdpt->id . ' successfully updated');
        Alert::success('Berhasil', 'Data Sdpt berhasil diupdate.');
        return redirect()->route('sdpt.index');
    }

    public function destroy(Sdpt $sdpt)
    {
        Log::info('Destroy method called for Sdpt with ID: ' . $sdpt->id);

        // Delete file from storage
        if ($sdpt->file_path) {
            Log::info('Deleting file from storage: ' . $sdpt->file_path);
            Storage::disk('public')->delete($sdpt->file_path);
        }

        $sdpt->delete();
        Log::info('Sdpt with ID ' . $sdpt->id . ' successfully deleted');
        Alert::success('Berhasil', 'Data Sdpt berhasil dihapus.');
        return redirect()->route('sdpt.index');
    }
}
