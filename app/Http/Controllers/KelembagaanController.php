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
    public function index()
    {
        Log::info('Fetching all Kelembagaans');
        $kelembagaans = Kelembagaan::with(['klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelola'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        Log::info('Fetched Kelembagaans: ' . $kelembagaans->total() . ' records');

        return view('kelembagaan.index', compact('kelembagaans'));
    }

    public function show(Kelembagaan $kelembagaan)
    {
        Log::info('Showing details for Kelembagaan with ID: ' . $kelembagaan->id);
        return view('kelembagaan.show', compact('kelembagaan'));
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

        return view('kelembagaan.create', compact('klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelolas'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called');
        Log::info('Request Data: ', $request->all());
            try {
                $rules = array_merge($this->getCommonValidationRules(), [
                    'file_path' => $this->getFileValidationRules(false, 10240),
                ]);

                $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());

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

                Kelembagaan::create($validated);
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
        $klasifikasi = Klasifikasi::all();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();
        $unitPengelola = UnitPengelola::all();

        return view('kelembagaan.edit', compact('kelembagaan', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelola'));
    }

    public function update(Request $request, Kelembagaan $kelembagaan)
    {
        Log::info('Update method called for Kelembagaan with ID: ' . $kelembagaan->id);
            try {
                $rules = array_merge($this->getCommonValidationRules(), [
                    'file_path' => $this->getFileValidationRules(false, 10240),
                ]);

                $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());

                if ($request->hasFile('file_path')) {
                    $filePath = $this->uploadFile($request->file('file_path'), 'arsip/kelembagaan', $kelembagaan->file_path);
                    if (!$filePath) {
                        Alert::error('Gagal', 'Upload file gagal.');
                        return redirect()->back()->withInput();
                    }
                    $validated['file_path'] = $filePath;
                }

                $kelembagaan->update($validated);
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
           $this->deleteFile($kelembagaan->file_path);
           $kelembagaan->delete();
           Alert::success('Berhasil', 'Data Kelembagaan berhasil dihapus.');
           return redirect()->route('kelembagaan.index');
    }
}
