<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Klasifikasi;
use Illuminate\Support\Facades\File;

class KlasifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * SEEDER INI SUDAH TIDAK DIGUNAKAN
     * Data klasifikasi akan diinput manual oleh admin
     */
    public function run(): void
    {
        $this->command->warn("Seeder klasifikasi sudah dinonaktifkan.");
        $this->command->info("Data klasifikasi akan diinput manual oleh admin melalui form.");
        return;

        /* COMMENTED - Tidak digunakan lagi
        // Path ke file JSON
        $jsonPath = base_path('klasifikasi_arsip.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("File klasifikasi_arsip.json tidak ditemukan!");
            return;
        }

        // Baca file JSON
        $jsonData = File::get($jsonPath);
        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error("Error parsing JSON: " . json_last_error_msg());
            return;
        }

        $this->command->info("Memproses " . count($data) . " data klasifikasi...");

        $inserted = 0;
        $skipped = 0;

        foreach ($data as $item) {
            // Cek apakah kode sudah ada
            $exists = Klasifikasi::where('kode', $item['kode'])->first();

            if ($exists) {
                $skipped++;
                continue;
            }

            // Insert data - sesuaikan dengan field yang ada di JSON
            Klasifikasi::create([
                'kode' => $item['kode'] ?? null,
                'urusan' => $item['urusan'] ?? null,
                'sub_urusan' => $item['sub_urusan'] ?? null,
                'nama' => $item['judul'] ?? null, // Gunakan 'judul' dari JSON sebagai 'nama'
                'retensi' => $item['retensi'] ?? 5, // Default 5 tahun jika tidak ada di JSON
            ]);

            $inserted++;
        }

        $this->command->info("Selesai! Inserted: {$inserted}, Skipped: {$skipped}");
        */
    }
}
