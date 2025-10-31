Deploy dari GitHub ke server (SSH) — panduan singkat

Ringkasan
- Workflow GitHub Actions yang disediakan akan SSH ke server dan menjalankan:
  - git pull pada branch yang ditentukan
  - composer install
  - php artisan migrate --force
  - cache config/route/view
  - php artisan storage:link (jika perlu)

Syarat di server
1. SSH akses dengan private key yang sesuai.
2. Direktori target (`TARGET_DIR`) sudah ada dan berisi repo git (git clone dilakukan sekali secara manual), atau Anda bisa menyesuaikan agar workflow men-clone repo.
3. PHP, Composer, dan ekstensi yang diperlukan terpasang di server.
4. User SSH punya hak untuk menjalankan perintah di folder target dan menjalankan composer/artisan.

Menambahkan Secrets di GitHub
Tambahkan secrets repository berikut (Settings > Secrets & variables > Actions):
- SERVER_HOST: alamat IP atau domain server
- SERVER_USER: username SSH (contoh: deploy)
- SERVER_PORT: (opsional) default 22
- SERVER_PRIVATE_KEY: isi private key (format PEM). Pastikan public key ditambahkan di `~/.ssh/authorized_keys` untuk `SERVER_USER` di server.
- TARGET_DIR: path absolut ke folder aplikasi di server (contoh: /var/www/arista)
- GIT_BRANCH: (opsional) branch yang ingin dideploy, default `main`

Pertama kali (manual) di server
1. Clone repo di `TARGET_DIR` atau pastikan repo sudah ada:
   git clone git@github.com:OWNER/REPO.git /var/www/arista
2. Atur permissions dan buat `.env` di server (jangan simpan secret di repo). Anda bisa menyalin `.env.example` dan mengedit.
3. Pasang composer & php, lalu jalankan `composer install` sekali.

Men-trigger deploy
- Push ke branch `main` (atau branch yang Anda set di `GIT_BRANCH`) -> akan otomatis menjalankan workflow.

Catatan keamanan
- Jangan menyimpan private key dalam repo. Gunakan GitHub Secrets.
- Pertimbangkan membuat user deploy dengan hak terbatas.

Jika Anda mau, saya bisa:
- Mengubah workflow agar men-clone repo jika folder target kosong.
- Membuat versi workflow yang men-deploy hanya ke staging.
- Membuat skrip rollback.

Berikan saya `SERVER_HOST`, `SERVER_USER`, `TARGET_DIR` dan konfirmasi apakah Anda ingin workflow men-clone bila folder kosong. Jika ya, saya akan perbarui workflow.
