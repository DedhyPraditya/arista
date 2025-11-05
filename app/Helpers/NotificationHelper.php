<?php

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

if (!function_exists('createNotification')) {
    function createNotification($userId, $type, $title, $message, $url = null, $icon = null)
    {
        $icons = [
            'info' => 'fa-info-circle',
            'success' => 'fa-check-circle',
            'warning' => 'fa-exclamation-triangle',
            'danger' => 'fa-times-circle'
        ];

        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'icon' => $icon ?? $icons[$type] ?? 'fa-info-circle',
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'is_read' => false
        ]);
    }
}

if (!function_exists('notifyCreate')) {
    function notifyCreate($modelName, $identifier, $url = null)
    {
        return createNotification(
            Auth::id(),
            'success',
            'Data Berhasil Ditambahkan',
            "Data {$modelName} '{$identifier}' telah berhasil ditambahkan ke sistem",
            $url,
            'fa-plus-circle'
        );
    }
}

if (!function_exists('notifyUpdate')) {
    function notifyUpdate($modelName, $identifier, $url = null)
    {
        return createNotification(
            Auth::id(),
            'info',
            'Data Berhasil Diperbarui',
            "Data {$modelName} '{$identifier}' telah berhasil diperbarui",
            $url,
            'fa-edit'
        );
    }
}

if (!function_exists('notifyDelete')) {
    function notifyDelete($modelName, $identifier)
    {
        return createNotification(
            Auth::id(),
            'warning',
            'Data Berhasil Dihapus',
            "Data {$modelName} '{$identifier}' telah dihapus dari sistem",
            null,
            'fa-trash-alt'
        );
    }
}

if (!function_exists('notifyDownload')) {
    function notifyDownload($fileName, $fileSize = null)
    {
        $sizeText = $fileSize ? " ({$fileSize})" : '';
        return createNotification(
            Auth::id(),
            'info',
            'File Berhasil Diunduh',
            "File '{$fileName}'{$sizeText} telah berhasil diunduh",
            null,
            'fa-download'
        );
    }
}
