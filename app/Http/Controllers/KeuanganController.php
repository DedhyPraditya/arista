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
use App\Traits\FileUploadTrait;
use App\Traits\ValidationMessagesTrait;

class KeuanganController extends Controller
{
    use FileUploadTrait, ValidationMessagesTrait;

    public function index(Request $request)
    {
        Log::info('Fetching Keuangan with filters', $request->query());

        $query = Keuangan::with(['klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelola'])->orderBy('created_at', 'desc');

        if ($request->filled('nomor_surat')) {
            $query->where('nomor_surat', 'like', '%' . $request->nomor_surat . '%');
        }
        if ($request->filled('tahun_surat')) {
            $query->where('tahun_surat', $request->tahun_surat);
        }
        if ($request->filled('unit_pengelola_id')) {
            $query->where('unit_pengelola_id', $request->unit_pengelola_id);
        }
        if ($request->filled('kode_klasifikasi_id')) {
            $query->where('kode_klasifikasi_id', $request->kode_klasifikasi_id);
        }
        if ($request->filled('keterangan')) {
            $query->where('keterangan', $request->keterangan);
        }
        if ($request->filled('nasib_akhir_id')) {
            $query->where('nasib_akhir_id', $request->nasib_akhir_id);
        }

        $keuangans = $query->paginate(10)->withQueryString();
        Log::info('Fetched Keuangans after filter: ' . $keuangans->total() . ' records');

        $unitPengelolas = UnitPengelola::select('id','unit_pengelola')->orderBy('unit_pengelola')->get();
        $klasifikasis = Klasifikasi::select('id','nama')->orderBy('nama')->get();
        $nasibAkhirs = NasibAkhir::select('id','nasib_akhir')->orderBy('nasib_akhir')->get();

        return view('keuangan.index', compact('keuangans','unitPengelolas','klasifikasis','nasibAkhirs'));
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

            try {
                // Validate input dengan pesan Indonesia
                $rules = array_merge($this->getCommonValidationRules(), [
                    'file_path' => 'nullable|' . $this->getFileValidationRules(false, 10240), // Optional, max 10MB
                ]);

                $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());

                // Auto-fill retensi dari klasifikasi
                $klasifikasi = Klasifikasi::find($validated['kode_klasifikasi_id']);
                if ($klasifikasi) {
                    $validated['retensi'] = $klasifikasi->retensi;
                }
                // Sinkron tahun_surat
                if (empty($validated['tahun_surat']) && !empty($validated['tanggal_surat'])) {
                    $validated['tahun_surat'] = (int)\Carbon\Carbon::parse($validated['tanggal_surat'])->year;
                }

                // Handle file upload menggunakan trait
                if ($request->hasFile('file_path')) {
                    Log::info('File detected for upload');
                    $filePath = $this->uploadFile($request->file('file_path'), 'arsip/keuangan');

                    if (!$filePath) {
                        Alert::error('Gagal', 'Upload file gagal. Silakan coba lagi.');
                        return redirect()->back()->withInput();
                    }

                    $validated['file_path'] = $filePath;
                    Log::info('File uploaded to: ' . $filePath);
                }

                // Create the Keuangan record
                Keuangan::create($validated);
                Alert::success('Berhasil', 'Data berhasil disimpan.');
                Log::info('KEUANGAN record successfully created');
                return redirect()->route('keuangan.index');

            } catch (\Exception $e) {
                Log::error('Error saving Keuangan: ' . $e->getMessage());
                Alert::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
                return redirect()->back()->withInput();
        }

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

            try {
                // Validate input dengan pesan Indonesia
                $rules = array_merge($this->getCommonValidationRules(), [
                    'file_path' => 'nullable|' . $this->getFileValidationRules(false, 10240), // Optional, max 10MB
                ]);

                $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());
                Log::info('Validated Data for Update: ', $validated);

                // Auto-fill retensi jika klasifikasi berubah
                if (isset($validated['kode_klasifikasi_id'])) {
                    $klasifikasi = Klasifikasi::find($validated['kode_klasifikasi_id']);
                    if ($klasifikasi) {
                        $validated['retensi'] = $klasifikasi->retensi;
                    }
                }
                // Sinkron tahun_surat
                if (isset($validated['tanggal_surat'])) {
                    $validated['tahun_surat'] = (int)\Carbon\Carbon::parse($validated['tanggal_surat'])->year;
                }

                // Handle file upload menggunakan trait (otomatis hapus file lama)
                if ($request->hasFile('file_path')) {
                    Log::info('New file detected for update');
                    $filePath = $this->uploadFile($request->file('file_path'), 'arsip/keuangan', $keuangan->file_path);

                    if (!$filePath) {
                        Alert::error('Gagal', 'Upload file gagal. Silakan coba lagi.');
                        return redirect()->back()->withInput();
                    }

                    $validated['file_path'] = $filePath;
                    Log::info('New file uploaded to: ' . $filePath);
            }

                $keuangan->update($validated);
                Log::info('Keuangan with ID ' . $keuangan->id . ' successfully updated');
                Alert::success('Berhasil', 'Data Keuangan berhasil diupdate.');
                return redirect()->route('keuangan.index');

            } catch (\Exception $e) {
                Log::error('Error updating Keuangan: ' . $e->getMessage());
                Alert::error('Gagal', 'Terjadi kesalahan saat mengupdate data.');
                return redirect()->back()->withInput();
        }

    }

    public function destroy(Keuangan $keuangan)
    {
        Log::info('Destroy method called for Keuangan with ID: ' . $keuangan->id);

            // Delete file from storage menggunakan trait
            $this->deleteFile($keuangan->file_path);

        $keuangan->delete();
        Log::info('Keuangan with ID ' . $keuangan->id . ' successfully deleted');
        Alert::success('Berhasil', 'Data Keuangan berhasil dihapus.');
        return redirect()->route('keuangan.index');
    }
}
