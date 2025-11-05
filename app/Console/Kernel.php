<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array<int, class-string>
     */
    protected $commands = [
        \App\Console\Commands\BackfillFileMetadata::class,
        \App\Console\Commands\BackfillKlasifikasiHierarchy::class,
        \App\Console\Commands\ShowKlasifikasiTree::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check retensi arsip setiap hari jam 08:00 pagi
        $schedule->command('arsip:check-retensi')->dailyAt('08:00');
        
        // Check storage setiap hari jam 09:00 pagi
        $schedule->command('arsip:check-storage')->dailyAt('09:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
