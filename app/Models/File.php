<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    protected $fillable = [
        'filename',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
        'fileable_type',
        'fileable_id',
        'uploaded_by',
        'description',
        'is_public',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_public' => 'boolean',
        'uploaded_at' => 'datetime',
    ];

    /**
     * Get the parent fileable model (Hkt, Keuangan, etc.)
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who uploaded the file
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the file URL
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if file is PDF
     */
    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    /**
     * Check if file is image
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }
}
