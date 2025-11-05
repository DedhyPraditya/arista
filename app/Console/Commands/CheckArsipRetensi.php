<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\Akademik;
use App\Models\Hkt;
use App\Models\Keuangan;
use App\Models\Kelembagaan;
use App\Models\Kemahasiswaan;
use App\Models\Sdpt;
use Carbon\Carbon;

class CheckArsipRetensi extends Command
{
    protected $signature = 'arsip:check-retensi';
    protected $description = 'Check arsip yang mendekati masa retensi dan buat notifikasi';

    public function handle()
    {
        $this->info('Checking arsip retensi...');
        
        $models = [
            'Akademik' => Akademik::class,
            'HKT' => Hkt::class,
            'Keuangan' => Keuangan::class,
            'Kelembagaan' => Kelembagaan::class,
            'Kemahasiswaan' => Kemahasiswaan::class,
            'SDPT' => Sdpt::class,
        ];

        $totalExpiringSoon = 0;
        $totalExpired = 0;

        foreach ($models as $name => $modelClass) {
            // Arsip yang akan jatuh tempo dalam 30 hari
            $expiringSoon = $modelClass::whereNotNull('retensi')
                ->whereNotNull('tanggal_surat')
                ->get()
                ->filter(function ($item) {
                    if (!$item->retensi || !$item->tanggal_surat) return false;
                    
                    $expiryDate = Carbon::parse($item->tanggal_surat)->addYears($item->retensi);
                    $daysUntilExpiry = now()->diffInDays($expiryDate, false);
                    
                    // Jatuh tempo dalam 30 hari
                    return $daysUntilExpiry > 0 && $daysUntilExpiry <= 30;
                });

            // Arsip yang sudah kadaluarsa
            $expired = $modelClass::whereNotNull('retensi')
                ->whereNotNull('tanggal_surat')
                ->get()
                ->filter(function ($item) {
                    if (!$item->retensi || !$item->tanggal_surat) return false;
                    
                    $expiryDate = Carbon::parse($item->tanggal_surat)->addYears($item->retensi);
                    return now()->isAfter($expiryDate);
                });

            $expiringSoonCount = $expiringSoon->count();
            $expiredCount = $expired->count();

            $totalExpiringSoon += $expiringSoonCount;
            $totalExpired += $expiredCount;

            if ($expiringSoonCount > 0) {
                $this->warn("{$name}: {$expiringSoonCount} arsip akan jatuh tempo dalam 30 hari");
            }

            if ($expiredCount > 0) {
                $this->error("{$name}: {$expiredCount} arsip sudah kadaluarsa");
            }
        }

        // Buat notifikasi global untuk arsip yang akan jatuh tempo
        if ($totalExpiringSoon > 0) {
            Notification::create([
                'user_id' => null, // Global notification
                'type' => 'warning',
                'icon' => 'fa-clock',
                'title' => 'Pengingat Retensi Arsip',
                'message' => "{$totalExpiringSoon} arsip akan jatuh tempo dalam 30 hari ke depan. Segera persiapkan disposisi.",
                'url' => route('dashboard'),
                'is_read' => false
            ]);
            $this->info("✓ Notifikasi dibuat untuk {$totalExpiringSoon} arsip yang akan jatuh tempo");
        }

        // Buat notifikasi untuk arsip kadaluarsa
        if ($totalExpired > 0) {
            Notification::create([
                'user_id' => null, // Global notification
                'type' => 'danger',
                'icon' => 'fa-exclamation-circle',
                'title' => 'Arsip Kadaluarsa Perlu Disposisi',
                'message' => "{$totalExpired} arsip telah melewati masa retensi dan menunggu disposisi (musnah/permanen).",
                'url' => route('dashboard'),
                'is_read' => false
            ]);
            $this->info("✓ Notifikasi dibuat untuk {$totalExpired} arsip kadaluarsa");
        }

        if ($totalExpiringSoon == 0 && $totalExpired == 0) {
            $this->info('✓ Semua arsip dalam status normal');
        }

        $this->info('Retensi check completed!');
        return 0;
    }
}
