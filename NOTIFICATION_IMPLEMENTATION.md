# Notification System Implementation - Complete Guide

## ✅ YANG SUDAH DIIMPLEMENTASIKAN

### 1. **CRUD Notifications** ✅
**Status:** LIVE & WORKING

**Fitur:**
- Notifikasi otomatis setelah **CREATE** data arsip
- Notifikasi otomatis setelah **UPDATE** data arsip  
- Notifikasi otomatis setelah **DELETE** data arsip

**Contoh Notifikasi:**
```
CREATE:
✓ Type: Success (Hijau)
✓ Icon: fa-plus-circle
✓ Title: "Data Berhasil Ditambahkan"
✓ Message: "Data Akademik 'No.Surat-123' telah berhasil ditambahkan ke sistem"
✓ Link: Ke halaman akademik index

UPDATE:
✓ Type: Info (Biru)
✓ Icon: fa-edit
✓ Title: "Data Berhasil Diperbarui"
✓ Message: "Data Akademik 'No.Surat-123' telah berhasil diperbarui"

DELETE:
✓ Type: Warning (Kuning)
✓ Icon: fa-trash-alt
✓ Title: "Data Berhasil Dihapus"
✓ Message: "Data Akademik 'No.Surat-123' telah dihapus dari sistem"
```

**Implementasi:**
- ✅ AkademikController (store, update, destroy)
- ⏳ TODO: HKT, Keuangan, Kelembagaan, Kemahasiswaan, SDPT (copy implementasi yang sama)

---

### 2. **Download Notification** ✅
**Status:** LIVE & WORKING

**Fitur:**
- Notifikasi otomatis setelah user download file
- Mencatat nama file dan ukuran file

**Contoh Notifikasi:**
```
✓ Type: Info (Biru)
✓ Icon: fa-download
✓ Title: "File Berhasil Diunduh"
✓ Message: "File 'dokumen-arsip.pdf' (2.5 MB) telah berhasil diunduh"
```

**Implementasi:**
- ✅ FileController->download()

---

### 3. **Retensi Reminder** ✅
**Status:** SCHEDULED (Jalan Otomatis Setiap Hari)

**Fitur:**
- Check semua arsip setiap hari jam 08:00 pagi
- Notifikasi untuk arsip yang **akan jatuh tempo dalam 30 hari**
- Notifikasi untuk arsip yang **sudah kadaluarsa**

**Contoh Notifikasi:**
```
AKAN JATUH TEMPO:
✓ Type: Warning (Kuning)
✓ Icon: fa-clock
✓ Title: "Pengingat Retensi Arsip"
✓ Message: "15 arsip akan jatuh tempo dalam 30 hari ke depan. Segera persiapkan disposisi."
✓ Global: Semua user bisa lihat

SUDAH KADALUARSA:
✓ Type: Danger (Merah)
✓ Icon: fa-exclamation-circle
✓ Title: "Arsip Kadaluarsa Perlu Disposisi"
✓ Message: "8 arsip telah melewati masa retensi dan menunggu disposisi (musnah/permanen)."
✓ Global: Semua user bisa lihat
```

**Command:**
```bash
php artisan arsip:check-retensi
```

**Schedule:** Otomatis jalan setiap hari jam 08:00

**Implementasi:**
- ✅ CheckArsipRetensi Command
- ✅ Registered di Kernel.php

---

### 4. **Storage Alert** ✅
**Status:** SCHEDULED (Jalan Otomatis Setiap Hari)

**Fitur:**
- Check storage usage setiap hari jam 09:00 pagi
- Alert jika storage mencapai **80%** (Warning)
- Alert jika storage mencapai **90%** (Critical)

**Contoh Notifikasi:**
```
WARNING (80-89%):
✓ Type: Warning (Kuning)
✓ Icon: fa-hdd
✓ Title: "Penyimpanan Perlu Perhatian"
✓ Message: "Penyimpanan telah mencapai 85% (250GB dari 300GB). Pertimbangkan untuk cleanup atau upgrade."
✓ Link: Ke halaman file management

CRITICAL (90%+):
✓ Type: Danger (Merah)
✓ Icon: fa-hdd
✓ Title: "Penyimpanan Hampir Penuh!"
✓ Message: "Penyimpanan telah mencapai 92% (276GB dari 300GB). Segera lakukan cleanup atau upgrade storage!"
✓ Link: Ke halaman file management
```

**Command:**
```bash
php artisan arsip:check-storage
```

**Schedule:** Otomatis jalan setiap hari jam 09:00

**Implementasi:**
- ✅ CheckStorageAlert Command
- ✅ Registered di Kernel.php

---

## 📝 HELPER FUNCTIONS TERSEDIA

### 1. `createNotification($userId, $type, $title, $message, $url, $icon)`
Buat notifikasi custom

### 2. `notifyCreate($modelName, $identifier, $url)`
Notifikasi setelah create data

### 3. `notifyUpdate($modelName, $identifier, $url)`
Notifikasi setelah update data

### 4. `notifyDelete($modelName, $identifier)`
Notifikasi setelah delete data

### 5. `notifyDownload($fileName, $fileSize)`
Notifikasi setelah download file

---

## 🎯 CARA TESTING

### Test CRUD Notifications:
1. Buka halaman Akademik
2. **Tambah data baru** → Lihat notifikasi hijau muncul
3. **Edit data** → Lihat notifikasi biru muncul
4. **Hapus data** → Lihat notifikasi kuning muncul

### Test Download Notification:
1. Buka halaman Daftar File
2. **Download file** → Lihat notifikasi biru muncul

### Test Retensi Checker:
```bash
php artisan arsip:check-retensi
```
Akan tampilkan summary arsip yang akan/sudah jatuh tempo

### Test Storage Alert:
```bash
php artisan arsip:check-storage
```
Akan tampilkan usage storage saat ini

---

## ⚙️ SETUP SCHEDULER (PRODUCTION)

Untuk menjalankan scheduled jobs di production, tambahkan cron job:

### Linux/Mac:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Windows (Task Scheduler):
1. Buka Task Scheduler
2. Create Basic Task
3. Trigger: Daily
4. Action: Start a program
5. Program: `C:\path\to\php.exe`
6. Arguments: `artisan schedule:run`
7. Start in: `D:\laragon\www\arista`

---

## 📋 TODO - IMPLEMENTASI SELANJUTNYA

### Phase 2: Extend ke Module Lain
Copy implementasi CRUD notification ke controller lain:

**HKT:**
```php
// di HktController
$hkt = Hkt::create($validated);
notifyCreate('HKT', $hkt->nomor_surat, route('hkt.index'));
```

**Keuangan:**
```php
$keuangan = Keuangan::create($validated);
notifyCreate('Keuangan', $keuangan->nomor_surat, route('keuangan.index'));
```

**Kelembagaan:**
```php
$kelembagaan = Kelembagaan::create($validated);
notifyCreate('Kelembagaan', $kelembagaan->nomor_surat, route('kelembagaan.index'));
```

**Kemahasiswaan:**
```php
$kemahasiswaan = Kemahasiswaan::create($validated);
notifyCreate('Kemahasiswaan', $kemahasiswaan->nomor_surat, route('kemahasiswaan.index'));
```

**SDPT:**
```php
$sdpt = Sdpt::create($validated);
notifyCreate('SDPT', $sdpt->nomor_surat, route('sdpt.index'));
```

### Phase 3: Advanced Features (Optional)
- Email notification untuk alert penting
- Push notification browser
- Sound alert
- WebSocket real-time notification
- Notification preferences per user

---

## 🔧 TROUBLESHOOTING

### Notifikasi tidak muncul?
1. Clear cache: `php artisan cache:clear`
2. Reload autoload: `composer dump-autoload`
3. Check database: pastikan table notifications ada
4. Check browser console untuk JS errors

### Scheduled jobs tidak jalan?
1. Test manual: `php artisan arsip:check-retensi`
2. Check cron: `php artisan schedule:list`
3. Test schedule: `php artisan schedule:run`

### Badge counter tidak update?
1. Refresh halaman
2. Check JavaScript console
3. Pastikan route `/notifications/unread` accessible

---

## 📊 MONITORING & LOGS

### Check notification count:
```bash
php artisan tinker
>>> App\Models\Notification::count()
>>> App\Models\Notification::unread()->count()
```

### View recent notifications:
```bash
php artisan tinker
>>> App\Models\Notification::latest()->limit(5)->get()
```

### Clear old notifications (>30 days):
```bash
php artisan tinker
>>> App\Models\Notification::where('created_at', '<', now()->subDays(30))->delete()
```

---

## ✨ SUMMARY

**Total Notifications Implemented:** 4
1. ✅ CRUD Notifications (Create, Update, Delete)
2. ✅ Download Notification
3. ✅ Retensi Reminder (Scheduled)
4. ✅ Storage Alert (Scheduled)

**Helper Functions:** 5
**Scheduled Jobs:** 2
**Status:** Production Ready! 🚀

---

**Next Step:** Copy implementasi CRUD ke module lain (HKT, Keuangan, dll)

Mau saya bantu implement ke module lain juga? 😊
