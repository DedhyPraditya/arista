<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Klasifikasi;

class ShowKlasifikasiTree extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'klasifikasi:show-tree {--depth= : Batas kedalaman (default semua)}';

    /**
     * The console command description.
     */
    protected $description = 'Menampilkan struktur hierarki klasifikasi arsip sebagai pohon.';

    public function handle(): int
    {
        $limitDepth = $this->option('depth');
        $limitDepth = is_numeric($limitDepth) ? (int)$limitDepth : null;

        $roots = Klasifikasi::where(function ($q) {
                $q->whereNull('parent_kode')->orWhere('parent_kode', '');
            })
            ->orderBy('kode')
            ->get();

        if ($roots->isEmpty()) {
            $this->warn('Tidak ada root klasifikasi ditemukan.');
            return self::SUCCESS;
        }

        $this->info("Struktur Hierarki Klasifikasi:");
        $this->line('');

        foreach ($roots as $root) {
            $this->printNode($root, 0, $limitDepth);
        }

        $this->line('');
        $total = Klasifikasi::count();
        $this->info("Total record: $total");
        $leafCount = Klasifikasi::where('is_leaf', true)->count();
        $this->info("Leaf nodes: $leafCount");

        return self::SUCCESS;
    }

    protected function printNode(Klasifikasi $node, int $depth, ?int $limitDepth): void
    {
        if ($limitDepth !== null && $depth > $limitDepth) {
            return; // melebihi batas
        }

        $indent = str_repeat('  ', $depth);
        $marker = $node->is_leaf ? '•' : '▶';
        $retensiPart = $node->is_leaf ? " (retensi: " . ($node->retensi ?? 'null') . ')' : '';

        $this->line(sprintf('%s%s %s - %s%s', $indent, $marker, $node->kode, $node->nama, $retensiPart));

        if ($node->is_leaf) {
            return; // tidak ada anak
        }

        $children = Klasifikasi::where('parent_kode', $node->kode)->orderBy('kode')->get();

        foreach ($children as $child) {
            $this->printNode($child, $depth + 1, $limitDepth);
        }
    }
}
