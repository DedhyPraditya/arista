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
use App\Traits\FileUploadTrait;
use App\Traits\ValidationMessagesTrait;

class SdptController extends Controller
{
    use FileUploadTrait, ValidationMessagesTrait;
    public function index(Request $request)
    {
        Log::info('Fetching Sdpt with filters', $request->query());
        $query = Sdpt::with(['unitPengelola','klasifikasi','tingkatPerkembangan','lokasiArsip','nasibAkhir'])->orderBy('created_at','desc');
        if ($request->filled('nomor_surat')) $query->where('nomor_surat','like','%'.$request->nomor_surat.'%');
        if ($request->filled('tahun_surat')) $query->where('tahun_surat',$request->tahun_surat);
        if ($request->filled('unit_pengelola_id')) $query->where('unit_pengelola_id',$request->unit_pengelola_id);
        if ($request->filled('kode_klasifikasi_id')) $query->where('kode_klasifikasi_id',$request->kode_klasifikasi_id);
        if ($request->filled('keterangan')) $query->where('keterangan',$request->keterangan);
        if ($request->filled('nasib_akhir_id')) $query->where('nasib_akhir_id',$request->nasib_akhir_id);
        $sdpt = $query->paginate(10)->withQueryString();
        Log::info('Fetched Sdpt after filter: '.$sdpt->total().' records');
        $unitPengelolas = UnitPengelola::select('id','unit_pengelola')->orderBy('unit_pengelola')->get();
        $klasifikasis = Klasifikasi::select('id','nama')->orderBy('nama')->get();
        $nasibAkhirs = NasibAkhir::select('id','nasib_akhir')->orderBy('nasib_akhir')->get();
        return view('sdpt.index', compact('sdpt','unitPengelolas','klasifikasis','nasibAkhirs'));
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
        $klasifikasiTree = Klasifikasi::where(function($q){
            $q->whereNull('parent_kode')->orWhere('parent_kode','');
        })->with('children.children')->orderBy('kode')->get();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();

        Log::info('Fetched hierarchical klasifikasi tree for create form');

        return view('sdpt.create', compact('unitPengelola', 'klasifikasiTree', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called for Sdpt');
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
                    $filePath = $this->uploadFile($request->file('file_path'), 'arsip/sdpt');
                    if (!$filePath) {
                        Alert::error('Gagal', 'Upload file gagal.');
                        return redirect()->back()->withInput();
                    }
                    $validated['file_path'] = $filePath;
                    Log::info('File uploaded to: ' . $filePath);
                }

                Sdpt::create($validated);
                Alert::success('Berhasil', 'Data Sdpt berhasil disimpan.');
                Log::info('Sdpt record successfully created');
                return redirect()->route('sdpt.index');
            } catch (\Exception $e) {
                Log::error('Error saving Sdpt: ' . $e->getMessage());
                Alert::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
                return redirect()->back()->withInput();
            }
    }

    public function edit(Sdpt $sdpt)
    {
        Log::info('Fetching data for editing Sdpt with ID: ' . $sdpt->id);
        $unitPengelola = UnitPengelola::all();
        $klasifikasiTree = Klasifikasi::where(function ($q) {
            $q->whereNull('parent_kode')->orWhere('parent_kode', '');
        })->with('children.children')->orderBy('kode')->get();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();

        Log::info('Fetched data for edit form: unitPengelola, klasifikasiTree, tingkatPerkembangan, lokasiArsip, nasibAkhir');

        return view('sdpt.edit', compact('sdpt', 'unitPengelola', 'klasifikasiTree', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'));
    }

    public function update(Request $request, Sdpt $sdpt)
    {
        Log::info('Update method called for Sdpt with ID: ' . $sdpt->id);
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
                    $filePath = $this->uploadFile($request->file('file_path'), 'arsip/sdpt', $sdpt->file_path);
                    if (!$filePath) {
                        Alert::error('Gagal', 'Upload file gagal.');
                        return redirect()->back()->withInput();
                    }
                    $validated['file_path'] = $filePath;
                    Log::info('New file uploaded to: ' . $filePath);
                }

                $sdpt->update($validated);
                Log::info('Sdpt with ID ' . $sdpt->id . ' successfully updated');
                Alert::success('Berhasil', 'Data Sdpt berhasil diupdate.');
                return redirect()->route('sdpt.index');
            } catch (\Exception $e) {
                Log::error('Error updating Sdpt: ' . $e->getMessage());
                Alert::error('Gagal', 'Terjadi kesalahan saat mengupdate data.');
                return redirect()->back()->withInput();
            }
    }

    public function destroy(Sdpt $sdpt)
    {
        Log::info('Destroy method called for Sdpt with ID: ' . $sdpt->id);
            $this->deleteFile($sdpt->file_path);
            $sdpt->delete();
            Log::info('Sdpt with ID ' . $sdpt->id . ' successfully deleted');
            Alert::success('Berhasil', 'Data Sdpt berhasil dihapus.');
            return redirect()->route('sdpt.index');
    }
}
