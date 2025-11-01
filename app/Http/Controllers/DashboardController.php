<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Menghitung total user
            $userCount = User::count();

            // Menghitung total unit pengelola
            $unitPengelolaCount = \App\Models\UnitPengelola::count();

            // Menghitung total klasifikasi arsip
            $klasifikasiCount = \App\Models\Klasifikasi::count();

            // Menggunakan model Eloquent untuk menghitung dokumen
            $categories = [
                'HKT' => \App\Models\Hkt::class,
                'Keuangan' => \App\Models\Keuangan::class,
                'Kelembagaan' => \App\Models\Kelembagaan::class,
                'Kemahasiswaan' => \App\Models\Kemahasiswaan::class,
                'Akademik' => \App\Models\Akademik::class,
                'SDPT' => \App\Models\Sdpt::class,
            ];

            $documentCounts = [];
            $totalPdfCount = 0;

            // Menghitung dokumen per kategori menggunakan Eloquent
            foreach ($categories as $categoryName => $modelClass) {
                $count = $modelClass::whereNotNull('file_path')
                    ->where('file_path', 'like', '%.pdf')
                    ->count();

                $documentCounts[$categoryName] = $count;
                $totalPdfCount += $count;
            }

            // Menghitung total file dari model File jika ada
            $fileCount = \App\Models\File::where('mime_type', 'application/pdf')->count();
            $totalPdfCount += $fileCount;

            // Data untuk dikirimkan ke view
            return view('dashboard', compact('userCount', 'unitPengelolaCount', 'klasifikasiCount', 'totalPdfCount', 'documentCounts'));

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());

            // Fallback data jika terjadi error
            $userCount = 0;
            $unitPengelolaCount = 0;
            $klasifikasiCount = 0;
            $totalPdfCount = 0;
            $documentCounts = array_fill_keys(['HKT', 'Keuangan', 'Kelembagaan', 'Kemahasiswaan', 'Akademik', 'SDPT'], 0);

            return view('dashboard', compact('userCount', 'unitPengelolaCount', 'klasifikasiCount', 'totalPdfCount', 'documentCounts'))
                ->with('error', 'Terjadi kesalahan saat memuat data dashboard.');
        }
    }
}
