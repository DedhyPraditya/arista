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
use App\Traits\FileUploadTrait;
use App\Traits\ValidationMessagesTrait;

class KemahasiswaanController extends Controller
{
    use FileUploadTrait, ValidationMessagesTrait;
    public function index(Request $request)
    {
        Log::info('Fetching Kemahasiswaan with filters', $request->query());
        $query = Kemahasiswaan::with(['unitPengelola','klasifikasi','tingkatPerkembangan','lokasiArsip','nasibAkhir'])->orderBy('created_at','desc');
        if ($request->filled('nomor_surat')) $query->where('nomor_surat','like','%'.$request->nomor_surat.'%');
        if ($request->filled('tahun_surat')) $query->where('tahun_surat',$request->tahun_surat);
        if ($request->filled('unit_pengelola_id')) $query->where('unit_pengelola_id',$request->unit_pengelola_id);
        if ($request->filled('kode_klasifikasi_id')) $query->where('kode_klasifikasi_id',$request->kode_klasifikasi_id);
        if ($request->filled('keterangan')) $query->where('keterangan',$request->keterangan);
        if ($request->filled('nasib_akhir_id')) $query->where('nasib_akhir_id',$request->nasib_akhir_id);
        $kemahasiswaan = $query->paginate(10)->withQueryString();
        Log::info('Fetched Kemahasiswaan after filter: '.$kemahasiswaan->total().' records');
        $unitPengelolas = UnitPengelola::select('id','unit_pengelola')->orderBy('unit_pengelola')->get();
        $klasifikasis = Klasifikasi::select('id','nama')->orderBy('nama')->get();
        $nasibAkhirs = NasibAkhir::select('id','nasib_akhir')->orderBy('nasib_akhir')->get();
        return view('kemahasiswaan.index', compact('kemahasiswaan','unitPengelolas','klasifikasis','nasibAkhirs'));
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
        $klasifikasiTree = Klasifikasi::where(function($q){
            $q->whereNull('parent_kode')->orWhere('parent_kode','');
        })->with('children.children')->orderBy('kode')->get();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();

        Log::info('Fetched hierarchical klasifikasi tree for create form');

        return view('kemahasiswaan.create', compact('unitPengelola', 'klasifikasiTree', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called for Kemahasiswaan');
        Log::info('Request Data: ', $request->all());
        try {
            $rules = array_merge($this->getCommonValidationRules(), [
                'jumlah_item' => 'required|integer|min:0',
                'lampiran' => 'nullable|string|max:255',
                'file_path' => 'nullable|' . $this->getFileValidationRules(false, 10240),
            ]);

            $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());

            // Validasi leaf & ambil retensi
            $klasifikasi = Klasifikasi::find($validated['kode_klasifikasi_id']);
            if ($klasifikasi) {
                if (!$klasifikasi->isLeaf()) {
                    return back()->withErrors(['kode_klasifikasi_id' => 'Harus memilih klasifikasi tingkat akhir (leaf).'])->withInput();
                }
                $validated['retensi'] = $klasifikasi->retensi;
            }
            if (empty($validated['tahun_surat']) && !empty($validated['tanggal_surat'])) {
                $validated['tahun_surat'] = (int)\Carbon\Carbon::parse($validated['tanggal_surat'])->year;
            }

            if ($request->hasFile('file_path')) {
                Log::info('File detected for upload');
                $filePath = $this->uploadFile($request->file('file_path'), 'arsip/kemahasiswaan');
                if (!$filePath) {
                    Alert::error('Gagal', 'Upload file gagal.');
                    return redirect()->back()->withInput();
                }
                $validated['file_path'] = $filePath;
                Log::info('File uploaded to: ' . $filePath);
            }

            $record = Kemahasiswaan::create($validated);
            // Notifikasi CREATE
            if (function_exists('notifyCreate')) {
                try {
                    notifyCreate('Kemahasiswaan', $record->nomor_surat ?? ('ID '.$record->id), route('kemahasiswaan.index'));
                } catch (\Throwable $te) {
                    Log::warning('NotifyCreate Kemahasiswaan gagal: '.$te->getMessage());
                }
            }
            Alert::success('Berhasil', 'Data Kemahasiswaan berhasil disimpan.');
            Log::info('Kemahasiswaan record successfully created');
            return redirect()->route('kemahasiswaan.index');
        } catch (\Exception $e) {
            Log::error('Error saving Kemahasiswaan: ' . $e->getMessage());
            Alert::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
            return redirect()->back()->withInput();
        }
    }

    public function edit(Kemahasiswaan $kemahasiswaan)
    {
        Log::info('Fetching data for editing Kemahasiswaan with ID: ' . $kemahasiswaan->id);
        $unitPengelola = UnitPengelola::all();
        $klasifikasiTree = Klasifikasi::where(function ($q) {
            $q->whereNull('parent_kode')->orWhere('parent_kode', '');
        })->with('children.children')->orderBy('kode')->get();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();

        Log::info('Fetched data for edit form: unitPengelola, klasifikasiTree, tingkatPerkembangan, lokasiArsip, nasibAkhir');

        return view('kemahasiswaan.edit', compact('kemahasiswaan', 'unitPengelola', 'klasifikasiTree', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'));
    }

    public function update(Request $request, Kemahasiswaan $kemahasiswaan)
    {
        Log::info('Update method called for Kemahasiswaan with ID: ' . $kemahasiswaan->id);
        Log::info('Request Data for Update: ', $request->all());
        try {
            $rules = array_merge($this->getCommonValidationRules(), [
                'jumlah_item' => 'required|integer|min:0',
                'lampiran' => 'nullable|string|max:255',
                'file_path' => 'nullable|' . $this->getFileValidationRules(false, 10240),
            ]);

            $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());
            Log::info('Validated Data for Update: ', $validated);

            // Auto-fill retensi jika klasifikasi berubah
            if (isset($validated['kode_klasifikasi_id'])) {
                $klasifikasi = Klasifikasi::find($validated['kode_klasifikasi_id']);
                if ($klasifikasi) {
                    // Validasi harus leaf
                    if (!$klasifikasi->isLeaf()) {
                        return back()->withErrors(['kode_klasifikasi_id' => 'Harus memilih klasifikasi tingkat akhir (leaf).'])->withInput();
                    }
                    $validated['retensi'] = $klasifikasi->retensi;
                }
            }
            if (isset($validated['tanggal_surat'])) {
                $validated['tahun_surat'] = (int)\Carbon\Carbon::parse($validated['tanggal_surat'])->year;
            }

            if ($request->hasFile('file_path')) {
                Log::info('New file detected for update');
                $filePath = $this->uploadFile($request->file('file_path'), 'arsip/kemahasiswaan', $kemahasiswaan->file_path);
                if (!$filePath) {
                    Alert::error('Gagal', 'Upload file gagal.');
                    return redirect()->back()->withInput();
                }
                $validated['file_path'] = $filePath;
                Log::info('New file uploaded to: ' . $filePath);
            }

            $kemahasiswaan->update($validated);
            // Notifikasi UPDATE
            if (function_exists('notifyUpdate')) {
                try {
                    notifyUpdate('Kemahasiswaan', $kemahasiswaan->nomor_surat ?? ('ID '.$kemahasiswaan->id), route('kemahasiswaan.index'));
                } catch (\Throwable $te) {
                    Log::warning('NotifyUpdate Kemahasiswaan gagal: '.$te->getMessage());
                }
            }
            Log::info('Kemahasiswaan with ID ' . $kemahasiswaan->id . ' successfully updated');
            Alert::success('Berhasil', 'Data Kemahasiswaan berhasil diupdate.');
            return redirect()->route('kemahasiswaan.index');
        } catch (\Exception $e) {
            Log::error('Error updating Kemahasiswaan: ' . $e->getMessage());
            Alert::error('Gagal', 'Terjadi kesalahan saat mengupdate data.');
            return redirect()->back()->withInput();
        }
    }

    public function destroy(Kemahasiswaan $kemahasiswaan)
    {
        Log::info('Destroy method called for Kemahasiswaan with ID: ' . $kemahasiswaan->id);
        $nomorSurat = $kemahasiswaan->nomor_surat; // simpan sebelum delete
        $this->deleteFile($kemahasiswaan->file_path);
        $kemahasiswaan->delete();
        // Notifikasi DELETE
        if (function_exists('notifyDelete')) {
            try {
                notifyDelete('Kemahasiswaan', $nomorSurat ?? ('ID '.$kemahasiswaan->id));
            } catch (\Throwable $te) {
                Log::warning('NotifyDelete Kemahasiswaan gagal: '.$te->getMessage());
            }
        }
        Log::info('Kemahasiswaan with ID ' . $kemahasiswaan->id . ' successfully deleted');
        Alert::success('Berhasil', 'Data Kemahasiswaan berhasil dihapus.');
        return redirect()->route('kemahasiswaan.index');
    }
}
