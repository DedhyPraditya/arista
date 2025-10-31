<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Http\Requests\StoreFileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class FileController extends Controller
{
    public function index()
    {
        // Mengambil semua file dari model File
        $files = File::with(['fileable', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Mengambil file dari semua model arsip
        $archiveFiles = collect();
        
        // HKT Files
        $hktFiles = \App\Models\Hkt::whereNotNull('file_path')
            ->with(['unitPengelola', 'klasifikasi'])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id' => 'hkt_' . $item->id,
                    'filename' => basename($item->file_path),
                    'original_name' => basename($item->file_path),
                    'file_path' => $item->file_path,
                    'file_size' => file_exists(public_path('storage/' . $item->file_path)) ? filesize(public_path('storage/' . $item->file_path)) : 0,
                    'mime_type' => 'application/pdf',
                    'archive_type' => 'HKT',
                    'archive_title' => $item->prihal,
                    'archive_number' => $item->nomor_surat,
                    'unit_pengelola' => $item->unitPengelola->nama_unit ?? 'N/A',
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'uploader' => (object) ['name' => 'System'],
                ];
            });

        // Keuangan Files
        $keuanganFiles = \App\Models\Keuangan::whereNotNull('file_path')
            ->with(['unitPengelola', 'klasifikasi'])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id' => 'keuangan_' . $item->id,
                    'filename' => basename($item->file_path),
                    'original_name' => basename($item->file_path),
                    'file_path' => $item->file_path,
                    'file_size' => file_exists(public_path('storage/' . $item->file_path)) ? filesize(public_path('storage/' . $item->file_path)) : 0,
                    'mime_type' => 'application/pdf',
                    'archive_type' => 'Keuangan',
                    'archive_title' => $item->prihal,
                    'archive_number' => $item->nomor_surat,
                    'unit_pengelola' => $item->unitPengelola->nama_unit ?? 'N/A',
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'uploader' => (object) ['name' => 'System'],
                ];
            });

        // Kelembagaan Files
        $kelembagaanFiles = \App\Models\Kelembagaan::whereNotNull('file_path')
            ->with(['unitPengelola', 'klasifikasi'])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id' => 'kelembagaan_' . $item->id,
                    'filename' => basename($item->file_path),
                    'original_name' => basename($item->file_path),
                    'file_path' => $item->file_path,
                    'file_size' => file_exists(public_path('storage/' . $item->file_path)) ? filesize(public_path('storage/' . $item->file_path)) : 0,
                    'mime_type' => 'application/pdf',
                    'archive_type' => 'Kelembagaan',
                    'archive_title' => $item->prihal,
                    'archive_number' => $item->nomor_surat,
                    'unit_pengelola' => $item->unitPengelola->nama_unit ?? 'N/A',
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'uploader' => (object) ['name' => 'System'],
                ];
            });

        // Kemahasiswaan Files
        $kemahasiswaanFiles = \App\Models\Kemahasiswaan::whereNotNull('file_path')
            ->with(['unitPengelola', 'klasifikasi'])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id' => 'kemahasiswaan_' . $item->id,
                    'filename' => basename($item->file_path),
                    'original_name' => basename($item->file_path),
                    'file_path' => $item->file_path,
                    'file_size' => file_exists(public_path('storage/' . $item->file_path)) ? filesize(public_path('storage/' . $item->file_path)) : 0,
                    'mime_type' => 'application/pdf',
                    'archive_type' => 'Kemahasiswaan',
                    'archive_title' => $item->prihal,
                    'archive_number' => $item->nomor_surat,
                    'unit_pengelola' => $item->unitPengelola->nama_unit ?? 'N/A',
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'uploader' => (object) ['name' => 'System'],
                ];
            });

        // Akademik Files
        $akademikFiles = \App\Models\Akademik::whereNotNull('file_path')
            ->with(['unitPengelola', 'klasifikasi'])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id' => 'akademik_' . $item->id,
                    'filename' => basename($item->file_path),
                    'original_name' => basename($item->file_path),
                    'file_path' => $item->file_path,
                    'file_size' => file_exists(public_path('storage/' . $item->file_path)) ? filesize(public_path('storage/' . $item->file_path)) : 0,
                    'mime_type' => 'application/pdf',
                    'archive_type' => 'Akademik',
                    'archive_title' => $item->prihal,
                    'archive_number' => $item->nomor_surat,
                    'unit_pengelola' => $item->unitPengelola->nama_unit ?? 'N/A',
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'uploader' => (object) ['name' => 'System'],
                ];
            });

        // SDPT Files
        $sdptFiles = \App\Models\Sdpt::whereNotNull('file_path')
            ->with(['unitPengelola', 'klasifikasi'])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id' => 'sdpt_' . $item->id,
                    'filename' => basename($item->file_path),
                    'original_name' => basename($item->file_path),
                    'file_path' => $item->file_path,
                    'file_size' => file_exists(public_path('storage/' . $item->file_path)) ? filesize(public_path('storage/' . $item->file_path)) : 0,
                    'mime_type' => 'application/pdf',
                    'archive_type' => 'SDPT',
                    'archive_title' => $item->prihal,
                    'archive_number' => $item->nomor_surat,
                    'unit_pengelola' => $item->unitPengelola->nama_unit ?? 'N/A',
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'uploader' => (object) ['name' => 'System'],
                ];
            });

        // Gabungkan semua file
        $allFiles = $archiveFiles
            ->merge($hktFiles)
            ->merge($keuanganFiles)
            ->merge($kelembagaanFiles)
            ->merge($kemahasiswaanFiles)
            ->merge($akademikFiles)
            ->merge($sdptFiles)
            ->sortByDesc('created_at');

        // Pagination manual
        $perPage = 20;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedFiles = $allFiles->slice($offset, $perPage)->values();
        
        // Buat pagination object
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedFiles,
            $allFiles->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );

        return view('files.index', compact('paginatedData'));
    }

    public function show(File $file)
    {
        $file->load(['fileable', 'uploader']);
        return view('files.show', compact('file'));
    }

    public function create()
    {
        return view('files.create');
    }

    public function store(StoreFileRequest $request)
    {
        $validated = $request->validated();

        try {
            $uploadedFile = $request->file('file');
            $filename = time() . '_' . $uploadedFile->getClientOriginalName();
            $filePath = $uploadedFile->storeAs('files', $filename, 'public');

            $file = File::create([
                'filename' => $filename,
                'original_name' => $uploadedFile->getClientOriginalName(),
                'file_path' => $filePath,
                'file_size' => $uploadedFile->getSize(),
                'mime_type' => $uploadedFile->getMimeType(),
                'uploaded_by' => auth()->id(),
                'description' => $validated['description'] ?? null,
                'is_public' => $validated['is_public'] ?? false,
            ]);

            Alert::success('Berhasil', 'File berhasil diupload.');
            return redirect()->route('files.show', $file);

        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            Alert::error('Gagal', 'Terjadi kesalahan saat mengupload file.');
            return redirect()->back()->withInput();
        }
    }

    public function edit(File $file)
    {
        return view('files.edit', compact('file'));
    }

    public function update(Request $request, File $file)
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:500',
            'is_public' => 'boolean',
        ]);

        try {
            $file->update($validated);
            Alert::success('Berhasil', 'File berhasil diupdate.');
            return redirect()->route('files.show', $file);

        } catch (\Exception $e) {
            Log::error('File update error: ' . $e->getMessage());
            Alert::error('Gagal', 'Terjadi kesalahan saat mengupdate file.');
            return redirect()->back()->withInput();
        }
    }

    public function destroy(File $file)
    {
        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            $file->delete();
            Alert::success('Berhasil', 'File berhasil dihapus.');
            return redirect()->route('files.index');

        } catch (\Exception $e) {
            Log::error('File deletion error: ' . $e->getMessage());
            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus file.');
            return redirect()->back();
        }
    }

    public function download(File $file)
    {
        try {
            if (!Storage::disk('public')->exists($file->file_path)) {
                Alert::error('Gagal', 'File tidak ditemukan.');
                return redirect()->back();
            }

            return Storage::disk('public')->download($file->file_path, $file->original_name);

        } catch (\Exception $e) {
            Log::error('File download error: ' . $e->getMessage());
            Alert::error('Gagal', 'Terjadi kesalahan saat mengunduh file.');
            return redirect()->back();
        }
    }
}
