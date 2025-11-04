<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Klasifikasi;

class KlasifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder untuk contoh data hierarki klasifikasi arsip multi-level
     */
    public function run(): void
    {
        // Data hierarki klasifikasi arsip multi-level
        // Format: kode, nama, retensi (null untuk parent, integer untuk leaf)
        
        $data = [
            // Root: HUKUM DAN PERATURAN PERUNDANG-UNDANGAN
            [
                'kode' => 'HK',
                'nama' => 'HUKUM DAN PERATURAN PERUNDANG-UNDANGAN',
                'parent_kode' => null,
                'level' => 0,
                'is_leaf' => false,
                'retensi' => null,
            ],
            
            // Level 1: HK.00 - PERATURAN PERUNDANG-UNDANGAN
            [
                'kode' => 'HK.00',
                'nama' => 'PERATURAN PERUNDANG-UNDANGAN',
                'parent_kode' => 'HK',
                'level' => 1,
                'is_leaf' => false,
                'retensi' => null,
            ],
            
            // Level 2: HK.00.01 - UNDANG-UNDANG
            [
                'kode' => 'HK.00.01',
                'nama' => 'UNDANG-UNDANG',
                'parent_kode' => 'HK.00',
                'level' => 2,
                'is_leaf' => true,
                'retensi' => 10,
            ],
            
            // Level 2: HK.00.02 - PERATURAN PEMERINTAH
            [
                'kode' => 'HK.00.02',
                'nama' => 'PERATURAN PEMERINTAH',
                'parent_kode' => 'HK.00',
                'level' => 2,
                'is_leaf' => true,
                'retensi' => 10,
            ],
            
            // Level 2: HK.00.03 - PERATURAN PRESIDEN
            [
                'kode' => 'HK.00.03',
                'nama' => 'PERATURAN PRESIDEN',
                'parent_kode' => 'HK.00',
                'level' => 2,
                'is_leaf' => true,
                'retensi' => 10,
            ],
            
            // Level 1: HK.01 - KEPUTUSAN DAN SURAT KEPUTUSAN
            [
                'kode' => 'HK.01',
                'nama' => 'KEPUTUSAN DAN SURAT KEPUTUSAN',
                'parent_kode' => 'HK',
                'level' => 1,
                'is_leaf' => false,
                'retensi' => null,
            ],
            
            // Level 2: HK.01.01 - KEPUTUSAN MENTERI
            [
                'kode' => 'HK.01.01',
                'nama' => 'KEPUTUSAN MENTERI',
                'parent_kode' => 'HK.01',
                'level' => 2,
                'is_leaf' => true,
                'retensi' => 5,
            ],
            
            // Level 2: HK.01.02 - KEPUTUSAN DIREKTUR JENDERAL
            [
                'kode' => 'HK.01.02',
                'nama' => 'KEPUTUSAN DIREKTUR JENDERAL',
                'parent_kode' => 'HK.01',
                'level' => 2,
                'is_leaf' => true,
                'retensi' => 5,
            ],
            
            // Root: PERLENGKAPAN
            [
                'kode' => 'PL',
                'nama' => 'PERLENGKAPAN',
                'parent_kode' => null,
                'level' => 0,
                'is_leaf' => false,
                'retensi' => null,
            ],
            
            // Level 1: PL.00 - PERALATAN KANTOR
            [
                'kode' => 'PL.00',
                'nama' => 'PERALATAN KANTOR',
                'parent_kode' => 'PL',
                'level' => 1,
                'is_leaf' => false,
                'retensi' => null,
            ],
            
            // Level 2: PL.00.01 - MESIN TIK/KOMPUTER
            [
                'kode' => 'PL.00.01',
                'nama' => 'MESIN TIK/KOMPUTER',
                'parent_kode' => 'PL.00',
                'level' => 2,
                'is_leaf' => false,
                'retensi' => null,
            ],
            
            // Level 3: PL.00.01.A - PENGADAAN KOMPUTER
            [
                'kode' => 'PL.00.01.A',
                'nama' => 'PENGADAAN KOMPUTER',
                'parent_kode' => 'PL.00.01',
                'level' => 3,
                'is_leaf' => true,
                'retensi' => 3,
            ],
            
            // Level 3: PL.00.01.B - PEMELIHARAAN KOMPUTER
            [
                'kode' => 'PL.00.01.B',
                'nama' => 'PEMELIHARAAN KOMPUTER',
                'parent_kode' => 'PL.00.01',
                'level' => 3,
                'is_leaf' => true,
                'retensi' => 2,
            ],
            
            // Level 2: PL.00.02 - ALAT TULIS KANTOR
            [
                'kode' => 'PL.00.02',
                'nama' => 'ALAT TULIS KANTOR',
                'parent_kode' => 'PL.00',
                'level' => 2,
                'is_leaf' => true,
                'retensi' => 2,
            ],
            
            // Root: KEUANGAN
            [
                'kode' => 'KU',
                'nama' => 'KEUANGAN',
                'parent_kode' => null,
                'level' => 0,
                'is_leaf' => false,
                'retensi' => null,
            ],
            
            // Level 1: KU.00 - ANGGARAN
            [
                'kode' => 'KU.00',
                'nama' => 'ANGGARAN',
                'parent_kode' => 'KU',
                'level' => 1,
                'is_leaf' => false,
                'retensi' => null,
            ],
            
            // Level 2: KU.00.01 - RENCANA ANGGARAN
            [
                'kode' => 'KU.00.01',
                'nama' => 'RENCANA ANGGARAN',
                'parent_kode' => 'KU.00',
                'level' => 2,
                'is_leaf' => true,
                'retensi' => 5,
            ],
            
            // Level 2: KU.00.02 - REALISASI ANGGARAN
            [
                'kode' => 'KU.00.02',
                'nama' => 'REALISASI ANGGARAN',
                'parent_kode' => 'KU.00',
                'level' => 2,
                'is_leaf' => true,
                'retensi' => 10,
            ],
        ];

        // Insert data
        foreach ($data as $item) {
            Klasifikasi::updateOrCreate(
                ['kode' => $item['kode']],
                $item
            );
        }

        $this->command->info('✅ Klasifikasi seeder completed successfully!');
        $this->command->info('📊 Total records: ' . count($data));
        $this->command->info('🌲 Hierarchy structure:');
        $this->command->info('   - 4 root nodes (HK, PL, KU)');
        $this->command->info('   - Multiple levels (up to level 3)');
        $this->command->info('   - Parent nodes have retensi = null');
        $this->command->info('   - Leaf nodes have retensi values');
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
