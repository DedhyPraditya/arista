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
use App\Traits\FileUploadTrait;
use App\Traits\ValidationMessagesTrait;

class AkademikController extends Controller
{
    use FileUploadTrait, ValidationMessagesTrait;
    public function index(Request $request)
    {
        Log::info('Fetching Akademik with filters', $request->query());
        $query = Akademik::with(['unitPengelola','klasifikasi','tingkatPerkembangan','lokasiArsip','nasibAkhir'])->orderBy('created_at','desc');
        if ($request->filled('nomor_surat')) $query->where('nomor_surat','like','%'.$request->nomor_surat.'%');
        if ($request->filled('tahun_surat')) $query->where('tahun_surat',$request->tahun_surat);
        if ($request->filled('unit_pengelola_id')) $query->where('unit_pengelola_id',$request->unit_pengelola_id);
        if ($request->filled('kode_klasifikasi_id')) $query->where('kode_klasifikasi_id',$request->kode_klasifikasi_id);
        if ($request->filled('keterangan')) $query->where('keterangan',$request->keterangan);
        if ($request->filled('nasib_akhir_id')) $query->where('nasib_akhir_id',$request->nasib_akhir_id);
        $akademik = $query->paginate(10)->withQueryString();
        Log::info('Fetched Akademik after filter: '.$akademik->total().' records');
        $unitPengelolas = UnitPengelola::select('id','unit_pengelola')->orderBy('unit_pengelola')->get();
        $klasifikasis = Klasifikasi::select('id','nama')->orderBy('nama')->get();
        $nasibAkhirs = NasibAkhir::select('id','nasib_akhir')->orderBy('nasib_akhir')->get();
        return view('akademik.index', compact('akademik','unitPengelolas','klasifikasis','nasibAkhirs'));
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
            try {
                $rules = array_merge($this->getCommonValidationRules(), [
                    'jumlah_item' => 'required|integer|min:0',
                    'lampiran' => 'nullable|string|max:255',
                    'file_path' => 'nullable|' . $this->getFileValidationRules(false, 10240),
                ]);

                $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());

                // Ambil retensi dari klasifikasi (snapshot)
                $klasifikasi = Klasifikasi::find($validated['kode_klasifikasi_id']);
                if ($klasifikasi) {
                    $validated['retensi'] = $klasifikasi->retensi; // override agar tidak diinput manual
                }
                // Tahun surat sinkron dari tanggal_surat jika belum diberikan / ingin dipastikan konsisten
                if (empty($validated['tahun_surat']) && !empty($validated['tanggal_surat'])) {
                    $validated['tahun_surat'] = (int)\Carbon\Carbon::parse($validated['tanggal_surat'])->year;
                }

                if ($request->hasFile('file_path')) {
                    Log::info('File detected for upload');
                    $filePath = $this->uploadFile($request->file('file_path'), 'arsip/akademik');
                    if (!$filePath) {
                        Alert::error('Gagal', 'Upload file gagal.');
                        return redirect()->back()->withInput();
                    }
                    $validated['file_path'] = $filePath;
                    Log::info('File uploaded to: ' . $filePath);
                }

                Akademik::create($validated);
                Alert::success('Berhasil', 'Data Akademik berhasil disimpan.');
                Log::info('Akademik record successfully created');
                return redirect()->route('akademik.index');
            } catch (\Exception $e) {
                Log::error('Error saving Akademik: ' . $e->getMessage());
                Alert::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
                return redirect()->back()->withInput();
            }
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
            try {
                $rules = array_merge($this->getCommonValidationRules(), [
                    'jumlah_item' => 'required|integer|min:0',
                    'lampiran' => 'nullable|string|max:255',
                    'file_path' => 'nullable|' . $this->getFileValidationRules(false, 10240),
                ]);

                $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());
                Log::info('Validated Data for Update: ', $validated);

                // Sinkron retensi dari klasifikasi jika kode berubah
                if (isset($validated['kode_klasifikasi_id'])) {
                    $klasifikasi = Klasifikasi::find($validated['kode_klasifikasi_id']);
                    if ($klasifikasi) {
                        $validated['retensi'] = $klasifikasi->retensi;
                    }
                }
                // Pastikan tahun_surat konsisten jika tanggal_surat diubah
                if (isset($validated['tanggal_surat'])) {
                    $validated['tahun_surat'] = (int)\Carbon\Carbon::parse($validated['tanggal_surat'])->year;
                }

                if ($request->hasFile('file_path')) {
                    Log::info('New file detected for update');
                    $filePath = $this->uploadFile($request->file('file_path'), 'arsip/akademik', $akademik->file_path);
                    if (!$filePath) {
                        Alert::error('Gagal', 'Upload file gagal.');
                        return redirect()->back()->withInput();
                    }
                    $validated['file_path'] = $filePath;
                    Log::info('New file uploaded to: ' . $filePath);
                }

                $akademik->update($validated);
                Log::info('Akademik with ID ' . $akademik->id . ' successfully updated');
                Alert::success('Berhasil', 'Data Akademik berhasil diupdate.');
                return redirect()->route('akademik.index');
            } catch (\Exception $e) {
                Log::error('Error updating Akademik: ' . $e->getMessage());
                Alert::error('Gagal', 'Terjadi kesalahan saat mengupdate data.');
                return redirect()->back()->withInput();
            }
    }

    public function destroy(Akademik $akademik)
    {
        Log::info('Destroy method called for Akademik with ID: ' . $akademik->id);
            $this->deleteFile($akademik->file_path);
            $akademik->delete();
            Log::info('Akademik with ID ' . $akademik->id . ' successfully deleted');
            Alert::success('Berhasil', 'Data Akademik berhasil dihapus.');
            return redirect()->route('akademik.index');
    }
}
