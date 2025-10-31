<?php
namespace App\Http\Controllers;

use App\Models\Hkt;
use App\Models\NasibAkhir;
use App\Models\Klasifikasi;
use App\Models\LokasiArsip;
use Illuminate\Http\Request;
use App\Models\UnitPengelola;
use App\Models\TingkatPerkembangan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class HktController extends Controller
{
    public function index()
    {
        Log::info('Fetching all HKTs');
        $hkts = Hkt::with(['klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelola'])->get();
        Log::info('Fetched HKTs: ' . $hkts->count() . ' records');

        return view('hkt.index', compact('hkts'));
    }

    public function show(Hkt $hkt)
    {
        Log::info('Showing details for HKT with ID: ' . $hkt->id);
        Log::info('File URL: ' . Storage::url($hkt->file_path));
        Log::info('File Path: ' . public_path('storage/hkts_files/' . basename($hkt->file_path)));
        return view('hkt.show', compact('hkt'));
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

        return view('hkt.create', compact('klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelolas'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called');
        Log::info('Request Data: ', $request->all());

        // Validasi input
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

        // Cek apakah ada file yang diupload
        if ($request->hasFile('file_path')) {
            Log::info('File detected for upload');

            // Pastikan file diupload dan disimpan dengan benar
            try {
                // Menyimpan file dan mendapatkan path
                $filePath = $request->file('file_path')->store('hkts_files', 'public');
                Log::info('File uploaded to: ' . $filePath);

                // Menyimpan file path ke dalam data yang akan disimpan ke database
                $validated['file_path'] = $filePath;
            } catch (\Exception $e) {
                Log::error('Error while uploading file: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupload file.');
            }
        } else {
            Log::warning('No file uploaded');
        }

        // Cek apakah file_path valid (terisi atau tidak)
        if (empty($validated['file_path'])) {
            Log::warning('File path is empty, data will be saved without file.');
        }

        // Membuat record HKT di database
        try {
            Hkt::create($validated);
            Log::info('HKT record successfully created');
            Alert::success('Berhasil', 'Data HKT berhasil disimpan.');
            return redirect()->route('hkt.index');
        } catch (\Exception $e) {
            Log::error('Error while saving HKT record: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function edit(Hkt $hkt)
    {
        Log::info('Fetching data for editing HKT with ID: ' . $hkt->id);
        $klasifikasi = Klasifikasi::all();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();
        $unitPengelola = UnitPengelola::all();

        Log::info('Fetched data for edit form: klasifikasi, tingkatPerkembangan, lokasiArsip, nasibAkhir, unitPengelola');

        return view('hkt.edit', compact('hkt', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelola'));
    }

    public function update(Request $request, Hkt $hkt)
    {
        Log::info('Update method called for HKT with ID: ' . $hkt->id);
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
            'nasib_akhir_id' => 'required|exists:nasib_akhir,id',
            'file_path' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        Log::info('Validated Data for Update: ', $validated);

        // Handle file upload
        if ($request->hasFile('file_path')) {
            Log::info('New file detected for update');

            if ($hkt->file_path) {
                Log::info('Deleting old file');
                Storage::disk('public')->delete($hkt->file_path);
            }

            $filePath = $request->file('file_path')->store('hkts_files', 'public');
            $validated['file_path'] = $filePath;
            Log::info('New file uploaded to: ' . $filePath);
        }

        $hkt->update($validated);
        Log::info('HKT with ID ' . $hkt->id . ' successfully updated');
        Alert::success('Berhasil', 'Data HKT berhasil diupdate.');
        return redirect()->route('hkt.index');
    }

    public function destroy(Hkt $hkt)
    {
        Log::info('Destroy method called for HKT with ID: ' . $hkt->id);

        // Delete file from storage
        if ($hkt->file_path) {
            Log::info('Deleting file from storage: ' . $hkt->file_path);
            Storage::disk('public')->delete($hkt->file_path);
        }

        $hkt->delete();
        Log::info('HKT with ID ' . $hkt->id . ' successfully deleted');
        Alert::success('Berhasil', 'Data HKT berhasil dihapus.');
        return redirect()->route('hkt.index');
    }
}
