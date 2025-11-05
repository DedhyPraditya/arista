<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HktController;
use App\Http\Controllers\AkademikController;
use App\Http\Controllers\SdptController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NasibAkhirController;
use App\Http\Controllers\KelembagaanController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\LokasiArsipController;
use App\Http\Controllers\KemahasiswaanController;
use App\Http\Controllers\PenciptaArsipController;
use App\Http\Controllers\UnitPengelolaController;
use App\Http\Controllers\TingkatPerkembanganController;
use App\Http\Controllers\FileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Email verifikasi telah dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Protected routes - require authentication
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    // 'verified' // middleware verifikasi email dihapus
])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Custom Profile Route (using SBAdmin2 layout)
    Route::get('/profile', function () {
        return view('profile.custom-show');
    })->name('profile.custom');

    // Two Factor Authentication Setup
    Route::get('/profile/two-factor-setup', function () {
        return view('profile.two-factor-setup');
    })->name('profile.two-factor-setup');

    // Master data routes
    Route::resource('klasifikasi', KlasifikasiController::class);
    Route::resource('pencipta_arsip', PenciptaArsipController::class);
    Route::resource('lokasi', LokasiArsipController::class);
    Route::resource('unit', UnitPengelolaController::class);
    Route::resource('tingkat', TingkatPerkembanganController::class);
    Route::resource('nasib', NasibAkhirController::class);

    // Document management routes
    Route::resource('hkt', HktController::class);
    Route::resource('keuangan', KeuanganController::class);
    Route::resource('kelembagaan', KelembagaanController::class);
    Route::resource('kemahasiswaan', KemahasiswaanController::class);
    Route::resource('akademik', AkademikController::class);
    Route::resource('sdpt', SdptController::class);

    // File management routes
    Route::resource('files', FileController::class);
    Route::get('/files/download/{file}', [FileController::class, 'download'])->name('files.download');

    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [App\Http\Controllers\NotificationController::class, 'getUnread']);
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy']);
});







