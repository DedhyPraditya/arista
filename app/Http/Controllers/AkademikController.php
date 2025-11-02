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
    public function index()
    {
        Log::info('Fetching all Akademik records');
        $akademik = Akademik::with(['unitPengelola', 'klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        Log::info('Fetched Akademik: ' . $akademik->total() . ' records');

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
            try {
                $rules = array_merge($this->getCommonValidationRules(), [
                    'jumlah_item' => 'required|integer|min:0',
                    'lampiran' => 'nullable|string|max:255',
                    'file_path' => $this->getFileValidationRules(false, 10240),
                ]);

                $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());

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
                    'file_path' => $this->getFileValidationRules(false, 10240),
                ]);

                $validated = $request->validate($rules, $this->getValidationMessages(), $this->getAttributeNames());
                Log::info('Validated Data for Update: ', $validated);

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
