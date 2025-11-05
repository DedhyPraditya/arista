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
        $klasifikasiTree = Klasifikasi::where(function($q){
            $q->whereNull('parent_kode')->orWhere('parent_kode','');
        })->with('children.children')->orderBy('kode')->get();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();
        $unitPengelolas = UnitPengelola::all();

        Log::info('Fetched hierarchical klasifikasi tree for create form');

        return view('hkt.create', compact('klasifikasiTree', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelolas'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called');
        Log::info('Request Data: ', $request->all());

        // Validasi input dengan pesan custom
        $rules = $this->getCommonValidationRules();
        $rules['file_path'] = 'nullable|' . $this->getFileValidationRules(false, 10240); // 10MB max

        $validated = $request->validate(
            $rules,
            $this->getValidationMessages(),
            $this->getAttributeNames()
        );

        // Validasi leaf & ambil retensi
        $klasifikasi = Klasifikasi::find($validated['kode_klasifikasi_id']);
        if ($klasifikasi) {
            if (!$klasifikasi->isLeaf()) {
                return back()->withErrors(['kode_klasifikasi_id' => 'Harus memilih klasifikasi tingkat akhir (leaf).'])->withInput();
            }
            $validated['retensi'] = $klasifikasi->retensi;
        }
        // Sinkron tahun_surat dari tanggal_surat
        if (empty($validated['tahun_surat']) && !empty($validated['tanggal_surat'])) {
            $validated['tahun_surat'] = (int)\Carbon\Carbon::parse($validated['tanggal_surat'])->year;
        }

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
            $hkt = Hkt::create($validated);
            Log::info('HKT record successfully created');
            
            // Notifikasi Create
            notifyCreate('HKT', $hkt->nomor_surat, route('hkt.index'));
            
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
        $klasifikasiTree = Klasifikasi::where(function ($q) {
            $q->whereNull('parent_kode')->orWhere('parent_kode', '');
        })->with('children.children')->orderBy('kode')->get();
        $tingkatPerkembangan = TingkatPerkembangan::all();
        $lokasiArsip = LokasiArsip::all();
        $nasibAkhir = NasibAkhir::all();
        $unitPengelola = UnitPengelola::all();

        Log::info('Fetched data for edit form: klasifikasiTree, tingkatPerkembangan, lokasiArsip, nasibAkhir, unitPengelola');

        return view('hkt.edit', compact('hkt', 'klasifikasiTree', 'tingkatPerkembangan', 'lokasiArsip', 'nasibAkhir', 'unitPengelola'));
    }

    public function update(Request $request, Hkt $hkt)
    {
        Log::info('Update method called for HKT with ID: ' . $hkt->id);
        Log::info('Request Data for Update: ', $request->all());

        $rules = $this->getCommonValidationRules();
        $rules['file_path'] = 'nullable|' . $this->getFileValidationRules(false, 10240);

        $validated = $request->validate(
            $rules,
            $this->getValidationMessages(),
            $this->getAttributeNames()
        );

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
        // Sinkron tahun_surat
        if (isset($validated['tanggal_surat'])) {
            $validated['tahun_surat'] = (int)\Carbon\Carbon::parse($validated['tanggal_surat'])->year;
        }

        $oldValidated = $validated; // Keep for later comparison
        $validated = array_merge([], [
            'nomor_surat' => $validated['nomor_surat'] ?? $hkt->nomor_surat,
            'tanggal_surat' => $validated['tanggal_surat'] ?? $hkt->tanggal_surat,
            'tahun_surat' => $validated['tahun_surat'] ?? $hkt->tahun_surat,
            'pencipta_arsip' => $validated['pencipta_arsip'] ?? $hkt->pencipta_arsip,
            'unit_pengelola_id' => $validated['unit_pengelola_id'] ?? $hkt->unit_pengelola_id,
            'kode_klasifikasi_id' => $validated['kode_klasifikasi_id'] ?? $hkt->kode_klasifikasi_id,
            'prihal' => $validated['prihal'] ?? $hkt->prihal,
            'uraian_informasi' => $validated['uraian_informasi'] ?? $hkt->uraian_informasi,
            'tingkat_perkembangan_id' => $validated['tingkat_perkembangan_id'] ?? $hkt->tingkat_perkembangan_id,
            'lokasi_arsip_id' => $validated['lokasi_arsip_id'] ?? $hkt->lokasi_arsip_id,
            'jumlah_item' => $validated['jumlah_item'] ?? $hkt->jumlah_item,
            'lampiran' => $validated['lampiran'] ?? $hkt->lampiran,
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
        
        // Notifikasi Update
        notifyUpdate('HKT', $hkt->nomor_surat, route('hkt.index'));
        
    public function destroy(Hkt $hkt)
    {
        Log::info('Destroy method called for HKT with ID: ' . $hkt->id);
        
        // Simpan nomor surat sebelum dihapus
        $nomorSurat = $hkt->nomor_surat;

            // Delete file from storage menggunakan trait
            $this->deleteFile($hkt->file_path);

        $hkt->delete();
        Log::info('HKT with ID ' . $hkt->id . ' successfully deleted');
        
        // Notifikasi Delete
        notifyDelete('HKT', $nomorSurat);
        
        Alert::success('Berhasil', 'Data HKT berhasil dihapus.');
        return redirect()->route('hkt.index');
    }   $hkt->delete();
        Log::info('HKT with ID ' . $hkt->id . ' successfully deleted');
        Alert::success('Berhasil', 'Data HKT berhasil dihapus.');
        return redirect()->route('hkt.index');
    }
}
