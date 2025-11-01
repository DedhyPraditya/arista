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

            // Data tren upload dokumen 6 bulan terakhir
            $monthlyTrend = $this->getMonthlyDocumentTrend();

            // Data top 5 unit pengelola dengan dokumen terbanyak
            $topUnits = $this->getTopUnitPengelola();

            // Data untuk dikirimkan ke view
            return view('dashboard', compact(
                'userCount',
                'unitPengelolaCount',
                'klasifikasiCount',
                'totalPdfCount',
                'documentCounts',
                'monthlyTrend',
                'topUnits'
            ));

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());

            // Fallback data jika terjadi error
            $userCount = 0;
            $unitPengelolaCount = 0;
            $klasifikasiCount = 0;
            $totalPdfCount = 0;
            $documentCounts = array_fill_keys(['HKT', 'Keuangan', 'Kelembagaan', 'Kemahasiswaan', 'Akademik', 'SDPT'], 0);

            $monthlyTrend = [];
            $topUnits = [];

            return view('dashboard', compact(
                'userCount',
                'unitPengelolaCount',
                'klasifikasiCount',
                'totalPdfCount',
                'documentCounts',
                'monthlyTrend',
                'topUnits'
            ))->with('error', 'Terjadi kesalahan saat memuat data dashboard.');
        }
    }

    /**
     * Mendapatkan tren upload dokumen 6 bulan terakhir
     */
    private function getMonthlyDocumentTrend(): array
    {
        $months = [];
        $data = [];

        // Generate 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->locale('id')->isoFormat('MMM YYYY');

            $months[] = $monthLabel;

            // Hitung dokumen yang dibuat di bulan tersebut
            $count = 0;

            $categories = [
                \App\Models\Hkt::class,
                \App\Models\Keuangan::class,
                \App\Models\Kelembagaan::class,
                \App\Models\Kemahasiswaan::class,
                \App\Models\Akademik::class,
                \App\Models\Sdpt::class,
            ];

            foreach ($categories as $modelClass) {
                $count += $modelClass::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }

            // Tambahkan dari model File
            $count += \App\Models\File::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $data[] = $count;
        }

        return [
            'labels' => $months,
            'data' => $data,
        ];
    }

    /**
     * Mendapatkan top 5 unit pengelola dengan dokumen terbanyak
     */
    private function getTopUnitPengelola(): array
    {
        $unitCounts = [];

        // Ambil semua unit pengelola
        $units = \App\Models\UnitPengelola::all();

        foreach ($units as $unit) {
            $count = 0;

            // Hitung dari setiap kategori
            $count += \App\Models\Hkt::where('unit_pengelola_id', $unit->id)->count();
            $count += \App\Models\Keuangan::where('unit_pengelola_id', $unit->id)->count();
            $count += \App\Models\Kelembagaan::where('unit_pengelola_id', $unit->id)->count();
            $count += \App\Models\Kemahasiswaan::where('unit_pengelola_id', $unit->id)->count();
            $count += \App\Models\Akademik::where('unit_pengelola_id', $unit->id)->count();
            $count += \App\Models\Sdpt::where('unit_pengelola_id', $unit->id)->count();

            if ($count > 0) {
                $unitCounts[$unit->unit_pengelola] = $count;
            }
        }

        // Sort descending dan ambil 5 teratas
        arsort($unitCounts);
        $unitCounts = array_slice($unitCounts, 0, 5, true);

        return [
            'labels' => array_keys($unitCounts),
            'data' => array_values($unitCounts),
        ];
    }
}
