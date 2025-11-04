<?php

namespace App\Console\Commands;

use App\Models\Klasifikasi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class BackfillKlasifikasiHierarchy extends Command
{
    /**
     * Artisan signature.
     */
    protected $signature = 'klasifikasi:backfill-hierarchy {--dry-run : Tampilkan hasil tanpa menyimpan}';

    /**
     * Command description.
     */
    protected $description = 'Mengisi kolom parent_kode, level, is_leaf pada tabel klasifikasi berdasarkan pola kode.';

    public function handle(): int
    {
        // Validasi kolom sudah ada
        foreach (['parent_kode','level','is_leaf'] as $col) {
            if (!Schema::hasColumn('klasifikasi', $col)) {
                $this->error("Kolom '$col' belum ada. Jalankan migrasi terlebih dahulu.");
                return Command::FAILURE;
            }
        }

        $dry = $this->option('dry-run');

        $rows = Klasifikasi::orderBy('kode')->get(['id','kode','retensi']);
        if ($rows->isEmpty()) {
            $this->warn('Tidak ada data klasifikasi untuk diproses.');
            return Command::SUCCESS;
        }

        // Siapkan map kode -> children sementara
        $allCodes = $rows->pluck('kode')->all();
        $childrenMap = [];
        foreach ($allCodes as $code) {
            $parent = $this->deriveParentKode($code);
            if ($parent) {
                $childrenMap[$parent] = $childrenMap[$parent] ?? [];
                $childrenMap[$parent][] = $code;
            }
        }

        $preview = [];
        $orphanPromoted = 0;
        foreach ($rows as $row) {
            $parentKode = $this->deriveParentKode($row->kode);
            $level = $this->deriveLevel($row->kode);
            $hasChildren = isset($childrenMap[$row->kode]);
            $isLeaf = !$hasChildren; // default leaf jika tidak punya anak

            // Promosi orphan: jika parentKode ada tetapi parent tidak tercatat
            if ($parentKode && !in_array($parentKode, $allCodes, true)) {
                $parentKode = null; // jadikan root
                $level = 0;
                $orphanPromoted++;
            }

            // Jika pola akhir huruf (mis: a,b,c) anggap leaf
            $lastSegment = $this->lastSegment($row->kode);
            if (preg_match('/[a-zA-Z]$/', $lastSegment)) {
                $isLeaf = true;
            }
            // Jika punya children jangan paksa leaf meski ada retensi
            if ($hasChildren) {
                $isLeaf = false;
            }

            $preview[] = [
                'kode'        => $row->kode,
                'parent_kode' => $parentKode ?? '-',
                'level'       => $level,
                'is_leaf'     => $isLeaf ? '✔' : '',
            ];

            if (!$dry) {
                $row->parent_kode = $parentKode;
                $row->level = $level;
                $row->is_leaf = $isLeaf;
                $row->save();
            }
        }

        if ($orphanPromoted > 0) {
            $this->warn("Total orphan dipromosikan menjadi root: {$orphanPromoted}");
        }

        $this->table(['Kode','Parent','Level','Leaf'], $preview);
        $this->info(($dry ? '[DRY-RUN] ' : '') . 'Backfill selesai untuk ' . count($preview) . ' baris.');
        if ($dry) {
            $this->line('Jalankan lagi tanpa --dry-run untuk menyimpan perubahan.');
        }

        return Command::SUCCESS;
    }

    private function deriveParentKode(string $kode): ?string
    {
        $parts = explode('.', $kode);
        if (count($parts) <= 1) {
            return null; // root
        }
        array_pop($parts); // remove last segment
        return implode('.', $parts);
    }

    private function deriveLevel(string $kode): int
    {
        // PR => 0, PR.00 => 1, PR.00.00 => 2, PR.00.00.a => 3
        $parts = explode('.', $kode);
        return max(count($parts) - 1, 0);
    }

    private function lastSegment(string $kode): string
    {
        $parts = explode('.', $kode);
        return end($parts);
    }
}
