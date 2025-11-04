# Checklist Deploy Hierarchy ke Server

Jalankan perintah ini di server **secara berurutan**:

## 1. Cek Struktur Tabel (Verifikasi Kolom Baru)
```bash
php artisan tinker --execute "dump(Schema::hasColumns('klasifikasi', ['parent_kode', 'level', 'is_leaf']));"
```
**Expected:** `true`  
**Jika false:** Lanjut ke step 2

## 2. Run Migration (Jika Kolom Belum Ada)
```bash
php artisan migrate
```
Ini akan menambahkan:
- `parent_kode` (string nullable)
- `level` (tinyint default 0)
- `is_leaf` (boolean default false)
- `retensi` jadi nullable

## 3. Backfill Hierarchy (Isi parent_kode, level, is_leaf)
```bash
php artisan klasifikasi:backfill-hierarchy
```
Ini akan:
- Menghitung parent_kode dari pola kode
- Menghitung level
- Menandai is_leaf (cek apakah punya anak)
- Auto-promote orphan ke root

## 4. Seed Data Contoh (Opsional - hanya jika mau contoh HK, PL, KU)
```bash
php artisan db:seed --class=KlasifikasiSeeder
```
Ini menambahkan 18 record contoh hierarki multi-level.

**ATAU jika hanya mau tambah HM secara manual:**
```bash
php artisan tinker
>>> App\Models\Klasifikasi::create(['kode'=>'HM','nama'=>'Kelompok HM','retensi'=>null,'parent_kode'=>null,'level'=>0,'is_leaf'=>false]);
>>> exit
```

## 5. Verifikasi Hasil
```bash
php artisan klasifikasi:show-tree --depth=2
```

## 6. Cek Error
```bash
# Pastikan tidak ada syntax error di controller/view
php artisan route:list | grep klasifikasi
```

---

## Troubleshooting

### Jika `php artisan migrate` tidak ada pending migration:
Berarti file migration belum di-commit/deploy. Cek:
```bash
ls -la database/migrations/*klasifikasi*
```

Harus ada:
- `2025_11_04_120000_add_hierarchy_columns_to_klasifikasi_table.php`
- `2025_11_05_120000_make_retensi_nullable_in_klasifikasi_table.php`

Kalau tidak ada, copy dari lokal ke server.

### Jika backfill command tidak ditemukan:
```bash
php artisan list | grep klasifikasi
```
Harus muncul:
- `klasifikasi:backfill-hierarchy`
- `klasifikasi:show-tree`

Kalau tidak ada, cek file:
```bash
ls -la app/Console/Commands/Backfill* app/Console/Commands/ShowKlasifikasi*
```

### Jika dropdown form masih flat:
Clear view cache:
```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

---

## Quick Fix untuk Server Sekarang

Karena server sudah punya 41 record tapi belum ada kolom hierarchy:

```bash
# 1. Migrasi
php artisan migrate

# 2. Backfill existing data
php artisan klasifikasi:backfill-hierarchy

# 3. Verifikasi
php artisan tinker --execute "dump(App\\Models\\Klasifikasi::whereNull('parent_kode')->pluck('kode'));"
```

Setelah itu aplikasi siap pakai hierarki.
