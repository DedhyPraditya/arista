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
        try {
            $rules = array_merge($this->getCommonValidationRules(), [
                'jumlah_item' => 'required|integer|min:0',
                'lampiran' => 'nullable|string|max:255',
                'file_path' => $this->getFileValidationRules(false, 10240),
            ]);

            $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());

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

            Kemahasiswaan::create($validated);
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
        try {
            $rules = array_merge($this->getCommonValidationRules(), [
                'jumlah_item' => 'required|integer|min:0',
                'lampiran' => 'nullable|string|max:255',
                'file_path' => $this->getFileValidationRules(false, 10240),
            ]);

            $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());
            Log::info('Validated Data for Update: ', $validated);

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
        $this->deleteFile($kemahasiswaan->file_path);
        $kemahasiswaan->delete();
        Log::info('Kemahasiswaan with ID ' . $kemahasiswaan->id . ' successfully deleted');
        Alert::success('Berhasil', 'Data Kemahasiswaan berhasil dihapus.');
        return redirect()->route('kemahasiswaan.index');
    }
}
