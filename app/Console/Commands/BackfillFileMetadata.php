<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

class BackfillFileMetadata extends Command
{
    protected $signature = 'files:backfill {--dry-run : Tampilkan perubahan tanpa menyimpan}';
    protected $description = 'Backfill metadata file: perbaiki file_size dan normalisasi filename jika perlu.';

    public function handle(): int
    {
        $dry = $this->option('dry-run');
        $count = 0;
        $updated = 0;

        $this->info('Memulai backfill metadata file' . ($dry ? ' (DRY RUN)' : ''));

        File::chunk(200, function ($files) use (&$count, &$updated, $dry) {
            foreach ($files as $file) {
                $count++;
                $originalSize = $file->file_size;
                $originalFilename = $file->filename;

                // Hitung ulang size jika storage masih ada
                if (Storage::disk('public')->exists($file->file_path)) {
                    $newSize = Storage::disk('public')->size($file->file_path);
                } else {
                    $newSize = 0; // File hilang
                }

                // Deteksi pola timestamp_awalNama (ex: 1730412345_filename.pdf)
                $needsNameNormalization = preg_match('/^\d{10,}_.+$/', $originalFilename) === 1;
                $newFilename = $originalFilename;

                if ($needsNameNormalization) {
                    // Gunakan original_name jika lebih bersih
                    $newFilename = $file->original_name;
                }

                $dirty = ($newSize !== $originalSize) || ($newFilename !== $originalFilename);

                if ($dirty) {
                    $updated++;
                    if ($dry) {
                        $this->line("[DRY] ID {$file->id}: size {$originalSize} -> {$newSize}; filename '{$originalFilename}' -> '{$newFilename}'");
                    } else {
                        $file->file_size = $newSize;
                        $file->filename = $newFilename;
                        $file->save();
                        $this->line("[OK] ID {$file->id} diperbarui.");
                    }
                }
            }
        });

        $this->info("Total diproses: {$count}; Perlu diperbarui: {$updated}" . ($dry ? ' (DRY RUN, tidak disimpan)' : ''));
        return Command::SUCCESS;
    }
}
