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
use App\Traits\FileUploadTrait;
use App\Traits\ValidationMessagesTrait;

class HktController extends Controller
{
    use FileUploadTrait, ValidationMessagesTrait;
    public function index(Request $request)
    {
        Log::info('Fetching HKTs with filters', $request->query());

        $query = Hkt::with(['klasifikasi', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelola'])->orderBy('created_at', 'desc');

        // Apply filters jika ada
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

        $hkts = $query->paginate(10)->withQueryString();
        Log::info('Fetched HKTs after filter: ' . $hkts->total() . ' records');

        // Data untuk dropdown filter
        $unitPengelolas = UnitPengelola::select('id', 'unit_pengelola')->orderBy('unit_pengelola')->get();
        $klasifikasis = Klasifikasi::select('id', 'nama')->orderBy('nama')->get();
        $nasibAkhirs = NasibAkhir::select('id', 'nasib_akhir')->orderBy('nasib_akhir')->get();

        return view('hkt.index', compact('hkts', 'unitPengelolas', 'klasifikasis', 'nasibAkhirs'));
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

        // Validasi input dengan pesan custom
        $rules = $this->getCommonValidationRules();
        $rules['file_path'] = $this->getFileValidationRules(false, 10240); // 10MB max

        $validated = $request->validate(
            $rules,
            $this->getValidationMessages(),
            $this->getAttributeNames()
        );

        // Upload file menggunakan trait
        if ($request->hasFile('file_path')) {
            $filePath = $this->uploadFile($request->file('file_path'), 'arsip/hkt');

            if ($filePath) {
                $validated['file_path'] = $filePath;
                Log::info('File uploaded successfully to: ' . $filePath);
            } else {
                Alert::error('Gagal', 'Terjadi kesalahan saat mengupload file.');
                return redirect()->back()->withInput();
            }
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

            $filePath = $this->uploadFile($request->file('file_path'), 'arsip/hkt', $hkt->file_path);

            if ($filePath) {
                $validated['file_path'] = $filePath;
                Log::info('New file uploaded to: ' . $filePath);
            } else {
                Alert::error('Gagal', 'Terjadi kesalahan saat mengupload file.');
                return redirect()->back()->withInput();
            }
        }

        $hkt->update($validated);
        Log::info('HKT with ID ' . $hkt->id . ' successfully updated');
        Alert::success('Berhasil', 'Data HKT berhasil diupdate.');
        return redirect()->route('hkt.index');
    }

    public function destroy(Hkt $hkt)
    {
        Log::info('Destroy method called for HKT with ID: ' . $hkt->id);

            // Delete file from storage menggunakan trait
            $this->deleteFile($hkt->file_path);

        $hkt->delete();
        Log::info('HKT with ID ' . $hkt->id . ' successfully deleted');
        Alert::success('Berhasil', 'Data HKT berhasil dihapus.');
        return redirect()->route('hkt.index');
    }
}
