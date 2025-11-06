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
use App\Traits\FileUploadTrait;
use App\Traits\ValidationMessagesTrait;

class KelembagaanController extends Controller
{
    use FileUploadTrait, ValidationMessagesTrait;
    public function index(Request $request)
    {
        Log::info('Fetching Kelembagaan with filters', $request->query());
        $query = Kelembagaan::with(['klasifikasi','tingkatPerkembangan','lokasiArsip','nasibAkhir','unitPengelola'])->orderBy('created_at','desc');
        if ($request->filled('nomor_surat')) $query->where('nomor_surat','like','%'.$request->nomor_surat.'%');
        if ($request->filled('tahun_surat')) $query->where('tahun_surat',$request->tahun_surat);
        if ($request->filled('unit_pengelola_id')) $query->where('unit_pengelola_id',$request->unit_pengelola_id);
        if ($request->filled('kode_klasifikasi_id')) $query->where('kode_klasifikasi_id',$request->kode_klasifikasi_id);
        if ($request->filled('keterangan')) $query->where('keterangan',$request->keterangan);
        if ($request->filled('nasib_akhir_id')) $query->where('nasib_akhir_id',$request->nasib_akhir_id);
        $kelembagaans = $query->paginate(10)->withQueryString();
        Log::info('Fetched Kelembagaans after filter: '.$kelembagaans->total().' records');
        $unitPengelolas = UnitPengelola::select('id','unit_pengelola')->orderBy('unit_pengelola')->get();
        $klasifikasis = Klasifikasi::select('id','nama')->orderBy('nama')->get();
        $nasibAkhirs = NasibAkhir::select('id','nasib_akhir')->orderBy('nasib_akhir')->get();
        return view('kelembagaan.index', compact('kelembagaans','unitPengelolas','klasifikasis','nasibAkhirs'));
    }

    public function show(Kelembagaan $kelembagaan)
    {
        Log::info('Showing details for Kelembagaan with ID: ' . $kelembagaan->id);
        return view('kelembagaan.show', compact('kelembagaan'));
    }

    public function create()
    {
        Log::info('Fetching dropdown data for create form');
        $klasifikasiTree = Klasifikasi::where(function($q){
            $q->whereNull('parent_kode')->orWhere('parent_kode','');
        })->with('children.children')->orderBy('kode')->get();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();
        $unitPengelolas = UnitPengelola::all();

        Log::info('Fetched hierarchical klasifikasi tree for create form');

        return view('kelembagaan.create', compact('klasifikasiTree', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelolas'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called');
        Log::info('Request Data: ', $request->all());
            try {
                $rules = array_merge($this->getCommonValidationRules(), [
                    'file_path' => 'nullable|' . $this->getFileValidationRules(false, 10240),
                ]);

                $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());

                // Validasi klasifikasi & retensi wajib
                $klasifikasi = Klasifikasi::find($validated['kode_klasifikasi_id']);
                if (!$klasifikasi) {
                    return back()->withErrors(['kode_klasifikasi_id' => 'Klasifikasi tidak ditemukan.'])->withInput();
                }
                if (!$klasifikasi->isLeaf()) {
                    return back()->withErrors(['kode_klasifikasi_id' => 'Harus memilih klasifikasi tingkat akhir (leaf).'])->withInput();
                }
                if (is_null($klasifikasi->retensi)) {
                    return back()->withErrors(['kode_klasifikasi_id' => 'Retensi belum diatur pada klasifikasi ini. Setel retensi di menu klasifikasi.'])->withInput();
                }
                $validated['retensi'] = (int)$klasifikasi->retensi;
                if (empty($validated['tahun_surat']) && !empty($validated['tanggal_surat'])) {
                    $validated['tahun_surat'] = (int)\Carbon\Carbon::parse($validated['tanggal_surat'])->year;
                }

                if ($request->hasFile('file_path')) {
                    Log::info('File detected for upload');
                    $filePath = $this->uploadFile($request->file('file_path'), 'arsip/kelembagaan');
                    if (!$filePath) {
                        Alert::error('Gagal', 'Upload file gagal.');
                        return redirect()->back()->withInput();
                    }
                    $validated['file_path'] = $filePath;
                    Log::info('File uploaded to: ' . $filePath);
                }

                $kelembagaan = Kelembagaan::create($validated);
                // Notifikasi CREATE
                if (function_exists('notifyCreate')) {
                    try {
                        notifyCreate('Kelembagaan', $kelembagaan->nomor_surat ?? ('ID '.$kelembagaan->id), route('kelembagaan.index'));
                    } catch (\Throwable $te) {
                        Log::warning('NotifyCreate Kelembagaan gagal: '.$te->getMessage());
                    }
                }
                Alert::success('Berhasil', 'Data berhasil disimpan.');
                Log::info('Kelembagaan record successfully created');
                return redirect()->route('kelembagaan.index');
            } catch (\Exception $e) {
                Log::error('Error saving Kelembagaan: ' . $e->getMessage());
                Alert::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
                return redirect()->back()->withInput();
            }
    }

    public function edit(Kelembagaan $kelembagaan)
    {
        Log::info('Fetching data for editing Kelembagaan with ID: ' . $kelembagaan->id);
        $klasifikasiTree = Klasifikasi::where(function ($q) {
            $q->whereNull('parent_kode')->orWhere('parent_kode', '');
        })->with('children.children')->orderBy('kode')->get();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();
        $unitPengelola = UnitPengelola::all();

        return view('kelembagaan.edit', compact('kelembagaan', 'klasifikasiTree', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelola'));
    }

    public function update(Request $request, Kelembagaan $kelembagaan)
    {
        Log::info('Update method called for Kelembagaan with ID: ' . $kelembagaan->id);
            try {
                $rules = array_merge($this->getCommonValidationRules(), [
                    'file_path' => 'nullable|' . $this->getFileValidationRules(false, 10240),
                ]);

                $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());

                // Auto-fill retensi jika klasifikasi berubah (guard ketat)
                if (isset($validated['kode_klasifikasi_id'])) {
                    $klasifikasi = Klasifikasi::find($validated['kode_klasifikasi_id']);
                    if (!$klasifikasi) {
                        return back()->withErrors(['kode_klasifikasi_id' => 'Klasifikasi tidak ditemukan.'])->withInput();
                    }
                    if (!$klasifikasi->isLeaf()) {
                        return back()->withErrors(['kode_klasifikasi_id' => 'Harus memilih klasifikasi tingkat akhir (leaf).'])->withInput();
                    }
                    if (is_null($klasifikasi->retensi)) {
                        return back()->withErrors(['kode_klasifikasi_id' => 'Retensi belum diatur pada klasifikasi ini. Setel retensi di menu klasifikasi.'])->withInput();
                    }
                    $validated['retensi'] = (int)$klasifikasi->retensi;
                }
                if (isset($validated['tanggal_surat'])) {
                    $validated['tahun_surat'] = (int)\Carbon\Carbon::parse($validated['tanggal_surat'])->year;
                }

                if ($request->hasFile('file_path')) {
                    $filePath = $this->uploadFile($request->file('file_path'), 'arsip/kelembagaan', $kelembagaan->file_path);
                    if (!$filePath) {
                        Alert::error('Gagal', 'Upload file gagal.');
                        return redirect()->back()->withInput();
                    }
                    $validated['file_path'] = $filePath;
                }

                $kelembagaan->update($validated);
                // Notifikasi UPDATE
                if (function_exists('notifyUpdate')) {
                    try {
                        notifyUpdate('Kelembagaan', $kelembagaan->nomor_surat ?? ('ID '.$kelembagaan->id), route('kelembagaan.index'));
                    } catch (\Throwable $te) {
                        Log::warning('NotifyUpdate Kelembagaan gagal: '.$te->getMessage());
                    }
                }
                Alert::success('Berhasil', 'Data Kelembagaan berhasil diupdate.');
                return redirect()->route('kelembagaan.index');
            } catch (\Exception $e) {
                Log::error('Error updating Kelembagaan: ' . $e->getMessage());
                Alert::error('Gagal', 'Terjadi kesalahan saat mengupdate data.');
                return redirect()->back()->withInput();
            }
    }

    public function destroy(Kelembagaan $kelembagaan)
    {
           $nomorSurat = $kelembagaan->nomor_surat; // simpan sebelum delete
           $this->deleteFile($kelembagaan->file_path);
           $kelembagaan->delete();
           // Notifikasi DELETE
           if (function_exists('notifyDelete')) {
                try {
                    notifyDelete('Kelembagaan', $nomorSurat ?? ('ID '.$kelembagaan->id));
                } catch (\Throwable $te) {
                    Log::warning('NotifyDelete Kelembagaan gagal: '.$te->getMessage());
                }
           }
           Alert::success('Berhasil', 'Data Kelembagaan berhasil dihapus.');
           return redirect()->route('kelembagaan.index');
    }
}
