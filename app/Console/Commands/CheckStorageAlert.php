<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;

class CheckStorageAlert extends Command
{
    protected $signature = 'arsip:check-storage';
    protected $description = 'Check storage usage dan buat notifikasi jika hampir penuh';

    public function handle()
    {
        $this->info('Checking storage usage...');
        
        $storagePath = storage_path('app/public');
        
        // Get disk space
        $totalSpace = disk_total_space($storagePath);
        $freeSpace = disk_free_space($storagePath);
        $usedSpace = $totalSpace - $freeSpace;
        $usedPercentage = ($usedSpace / $totalSpace) * 100;

        // Format sizes
        $totalSpaceFormatted = $this->formatBytes($totalSpace);
        $usedSpaceFormatted = $this->formatBytes($usedSpace);
        $freeSpaceFormatted = $this->formatBytes($freeSpace);

        $this->info("Total Space: {$totalSpaceFormatted}");
        $this->info("Used Space: {$usedSpaceFormatted} ({$usedPercentage}%)");
        $this->info("Free Space: {$freeSpaceFormatted}");

        // Create notification based on usage
        if ($usedPercentage >= 90) {
            // Critical - 90%+
            Notification::create([
                'user_id' => null,
                'type' => 'danger',
                'icon' => 'fa-hdd',
                'title' => 'Penyimpanan Hampir Penuh!',
                'message' => "Penyimpanan telah mencapai " . number_format($usedPercentage, 1) . "% ({$usedSpaceFormatted} dari {$totalSpaceFormatted}). Segera lakukan cleanup atau upgrade storage!",
                'url' => route('files.index'),
                'is_read' => false
            ]);
            $this->error("⚠ Critical: Storage usage at {$usedPercentage}%");
            
        } elseif ($usedPercentage >= 80) {
            // Warning - 80-89%
            Notification::create([
                'user_id' => null,
                'type' => 'warning',
                'icon' => 'fa-hdd',
                'title' => 'Penyimpanan Perlu Perhatian',
                'message' => "Penyimpanan telah mencapai " . number_format($usedPercentage, 1) . "% ({$usedSpaceFormatted} dari {$totalSpaceFormatted}). Pertimbangkan untuk cleanup atau upgrade.",
                'url' => route('files.index'),
                'is_read' => false
            ]);
            $this->warn("⚠ Warning: Storage usage at {$usedPercentage}%");
            
        } else {
            $this->info("✓ Storage usage normal ({$usedPercentage}%)");
        }

        $this->info('Storage check completed!');
        return 0;
    }

    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
