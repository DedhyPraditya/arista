<?php

namespace App\Http\Controllers;

use App\Models\Akademik;
use App\Models\NasibAkhir;
use App\Models\Klasifikasi;
use App\Models\LokasiArsip;
use Illuminate\Http\Request;
use App\Models\UnitPengelola;
use App\Models\TingkatPerkembangan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class AkademikController extends Controller
{
    public function index()
    {
        Log::info('Fetching all Akademik records');
        $akademik = Akademik::with(['unitPengelola', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'])->get();
        Log::info('Fetched Akademik: ' . $akademik->count() . ' records');

        return view('akademik.index', compact('akademik'));
    }

    public function show(Akademik $akademik)
    {
        Log::info('Showing details for Akademik with ID: ' . $akademik->id);
        return view('akademik.show', compact('akademik'));
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

        return view('akademik.create', compact('unitPengelola', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called for Akademik');
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
            $filePath = $request->file('file_path')->store('akademik_files', 'public');
            Log::info('File uploaded to: ' . $filePath);
            $validated['file_path'] = $filePath;
        }

        // Create the Akademik record
        Akademik::create($validated);
        Alert::success('Berhasil', 'Data Akademik berhasil disimpan.');
        Log::info('Akademik record successfully created');
        return redirect()->route('akademik.index');
    }

    public function edit(Akademik $akademik)
    {
        Log::info('Fetching data for editing Akademik with ID: ' . $akademik->id);
        $unitPengelola = UnitPengelola::all();
        $klasifikasi = Klasifikasi::all();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();

        Log::info('Fetched data for edit form: unitPengelola, klasifikasi, tingkatPerkembangan, lokasiArsip, nasibAkhir');

        return view('akademik.edit', compact('akademik', 'unitPengelola', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'));
    }

    public function update(Request $request, Akademik $akademik)
    {
        Log::info('Update method called for Akademik with ID: ' . $akademik->id);
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

            if ($akademik->file_path) {
                Log::info('Deleting old file');
                Storage::disk('public')->delete($akademik->file_path);
            }

            $filePath = $request->file('file_path')->store('akademik_files', 'public');
            $validated['file_path'] = $filePath;
            Log::info('New file uploaded to: ' . $filePath);
        }

        $akademik->update($validated);
        Log::info('Akademik with ID ' . $akademik->id . ' successfully updated');
        Alert::success('Berhasil', 'Data Akademik berhasil diupdate.');
        return redirect()->route('akademik.index');
    }

    public function destroy(Akademik $akademik)
    {
        Log::info('Destroy method called for Akademik with ID: ' . $akademik->id);

        // Delete file from storage
        if ($akademik->file_path) {
            Log::info('Deleting file from storage: ' . $akademik->file_path);
            Storage::disk('public')->delete($akademik->file_path);
        }

        $akademik->delete();
        Log::info('Akademik with ID ' . $akademik->id . ' successfully deleted');
        Alert::success('Berhasil', 'Data Akademik berhasil dihapus.');
        return redirect()->route('akademik.index');
    }
}
