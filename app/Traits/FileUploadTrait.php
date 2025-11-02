<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait FileUploadTrait
{
    /**
     * Upload file dengan validasi dan path yang konsisten
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string|null $oldPath
     * @return string|null
     */
    protected function uploadFile(UploadedFile $file, string $folder, ?string $oldPath = null): ?string
    {
        try {
            // Hapus file lama jika ada
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            // Generate nama file unik dengan timestamp
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            // Upload file
            $path = $file->storeAs($folder, $filename, 'public');

            return $path;
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Hapus file dari storage
     *
     * @param string|null $path
     * @return bool
     */
    protected function deleteFile(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Get file validation rules
     *
     * @param bool $required
     * @param int $maxSize in KB (default 10MB)
     * @return string
     */
    protected function getFileValidationRules(bool $required = false, int $maxSize = 10240): string
    {
        $rules = ($required ? 'required' : 'nullable') . '|file|mimes:pdf|max:' . $maxSize;
        return $rules;
    }
}
