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

    public function index()
    {
        Log::info('Fetching all Keuangans');
        $keuangans = Keuangan::with(['klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelola'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        Log::info('Fetched Keuangans: ' . $keuangans->total() . ' records');

        return view('keuangan.index', compact('keuangans'));
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
                    'file_path' => $this->getFileValidationRules(false, 10240), // Optional, max 10MB
                ]);

                $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());

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
                    'file_path' => $this->getFileValidationRules(false, 10240), // Optional, max 10MB
                ]);

                $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());
                Log::info('Validated Data for Update: ', $validated);

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
