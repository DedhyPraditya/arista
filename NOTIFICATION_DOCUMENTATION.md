# Alert Center / Notification System - Documentation

## ✅ Yang Sudah Dibuat:

### 1. Database & Model
- ✅ Tabel `notifications` dengan kolom:
  - `id`, `user_id`, `type`, `icon`, `title`, `message`, `url`, `is_read`, `read_at`, `created_at`, `updated_at`
- ✅ Model `Notification` dengan relationships dan methods
- ✅ Sample notifications (4 contoh notifikasi)

### 2. UI Components
- ✅ Bell icon dengan badge counter di navbar
- ✅ Dropdown notifications (max 5 terbaru)
- ✅ Halaman "Pusat Notifikasi" lengkap dengan pagination
- ✅ Auto-refresh setiap 30 detik

### 3. Features
- ✅ Real-time notification badge counter
- ✅ Mark as read (single & bulk)
- ✅ Delete notification
- ✅ Time ago formatting (Baru saja, 5 menit lalu, dll)
- ✅ Color coding (info/success/warning/danger)
- ✅ Link ke detail page (optional)
- ✅ Global notifications (user_id = null)

### 4. API Endpoints
```
GET    /notifications              → Halaman semua notifikasi
GET    /notifications/unread       → Get unread notifications (JSON)
POST   /notifications/{id}/read    → Mark as read
POST   /notifications/mark-all-read → Mark all as read
DELETE /notifications/{id}         → Delete notification
```

---

## 📝 Cara Membuat Notifikasi Baru

### Option 1: Manual Create (Untuk Testing)
```php
use App\Models\Notification;

Notification::create([
    'user_id' => 1, // atau null untuk global
    'type' => 'success', // info, success, warning, danger
    'icon' => 'fa-check-circle',
    'title' => 'Judul Notifikasi',
    'message' => 'Pesan lengkap notifikasi',
    'url' => route('akademik.index'), // optional
    'is_read' => false
]);
```

### Option 2: Buat Helper Function
Buat file `app/Helpers/NotificationHelper.php`:
```php
<?php

use App\Models\Notification;

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
```

Daftarkan di `composer.json`:
```json
"autoload": {
    "files": [
        "app/Helpers/NotificationHelper.php"
    ]
}
```

Lalu jalankan: `composer dump-autoload`

### Option 3: Integrasi dengan Controller
Contoh di AkademikController setelah create data:
```php
public function store(Request $request)
{
    // ... validasi dan simpan data ...
    
    $akademik = Akademik::create($validatedData);
    
    // Buat notifikasi
    Notification::create([
        'user_id' => Auth::id(),
        'type' => 'success',
        'icon' => 'fa-file-alt',
        'title' => 'Arsip Akademik Baru',
        'message' => 'Arsip dengan nomor ' . $akademik->nomor_surat . ' telah ditambahkan',
        'url' => route('akademik.show', $akademik->id)
    ]);
    
    Alert::success('Berhasil', 'Data berhasil disimpan!');
    return redirect()->route('akademik.index');
}
```

---

## 🎨 Jenis Notifikasi

### Info (Biru)
```php
'type' => 'info',
'icon' => 'fa-info-circle'
```

### Success (Hijau)
```php
'type' => 'success',
'icon' => 'fa-check-circle'
```

### Warning (Kuning)
```php
'type' => 'warning',
'icon' => 'fa-exclamation-triangle'
```

### Danger (Merah)
```php
'type' => 'danger',
'icon' => 'fa-times-circle'
```

---

## 🌍 Global vs User-Specific Notifications

### User-Specific (hanya untuk user tertentu)
```php
'user_id' => 1 // ID user
```

### Global (semua user bisa lihat)
```php
'user_id' => null
```

---

## 🔔 Contoh Use Cases

### 1. Notifikasi Setelah Create Data
```php
Notification::create([
    'user_id' => Auth::id(),
    'type' => 'success',
    'icon' => 'fa-plus-circle',
    'title' => 'Data Ditambahkan',
    'message' => 'Data ' . $model->nama . ' berhasil ditambahkan',
    'url' => route('model.show', $model->id)
]);
```

### 2. Notifikasi Reminder/Pengingat
```php
Notification::create([
    'user_id' => Auth::id(),
    'type' => 'warning',
    'icon' => 'fa-clock',
    'title' => 'Arsip Jatuh Tempo',
    'message' => '5 arsip akan jatuh tempo minggu ini',
    'url' => route('dashboard')
]);
```

### 3. Notifikasi System (Global)
```php
Notification::create([
    'user_id' => null, // global
    'type' => 'info',
    'icon' => 'fa-tools',
    'title' => 'Maintenance System',
    'message' => 'System akan maintenance Minggu pukul 02:00 WIB',
    'url' => null
]);
```

---

## 📱 Testing

1. **Lihat Bell Icon**: Buka halaman manapun, lihat icon bell di navbar kanan atas
2. **Check Badge**: Seharusnya ada badge merah dengan angka 4
3. **Click Bell**: Klik icon bell, akan muncul dropdown dengan 4 notifikasi contoh
4. **Mark as Read**: Klik notifikasi, otomatis marked as read
5. **View All**: Klik "Lihat Semua Notifikasi" untuk halaman lengkap
6. **Mark All**: Klik tombol "Tandai Semua Sudah Dibaca"
7. **Delete**: Klik tombol trash untuk hapus notifikasi

---

## 🚀 Next Steps (Optional)

1. **Email Notification**: Kirim email jika ada notifikasi penting
2. **Push Notification**: Browser push notification
3. **Sound Alert**: Bunyi saat ada notifikasi baru
4. **WebSocket**: Real-time notification tanpa refresh
5. **Notification Preferences**: User bisa setting mau terima notifikasi apa aja

---

## 🎯 Icon FontAwesome yang Bisa Dipakai

- `fa-check-circle` - Success/Berhasil
- `fa-info-circle` - Info
- `fa-exclamation-triangle` - Warning/Peringatan
- `fa-times-circle` - Error/Gagal
- `fa-file-alt` - Document/File
- `fa-clock` - Reminder/Waktu
- `fa-user-plus` - User baru
- `fa-edit` - Edit/Update
- `fa-trash` - Delete/Hapus
- `fa-download` - Download
- `fa-upload` - Upload
- `fa-bell` - Notifikasi
- `fa-bullhorn` - Pengumuman
- `fa-tools` - Maintenance
- `fa-calendar` - Event/Tanggal

Lihat lebih lengkap di: https://fontawesome.com/v5/search

---

**Selamat! Alert Center sudah siap digunakan!** 🎉
