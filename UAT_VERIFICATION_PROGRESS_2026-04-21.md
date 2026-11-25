# UAT Verification Progress (2026-04-21)

## Ringkasan
- Status: Partial verification (belum mencakup semua 46 skenario).
- Metode verifikasi hari ini:
  - Eksekusi test suite Laravel.
  - Eksekusi skrip login end-to-end berbasis database aktif.

## Bukti Eksekusi
1. `php artisan test`
- Hasil akhir: PASS (2 test, 3 assertions).
- Catatan: test default feature sudah disesuaikan dengan alur aplikasi (`/` redirect ke halaman login).

2. `php test_login.php`
- Hasil:
  - [ADMIN VALID] PASS
  - [ADMIN INVALID PASSWORD] PASS
  - [DOKTER VALID] PASS
  - [DOKTER INVALID PASSWORD] PASS
  - [ACCOUNT NOT FOUND] PASS

3. `php test_uat_antrian_pemeriksaan.php`
- Hasil:
  - [PASS] Admin membuat antrian baru
  - [PASS] Nomor antrian berurutan di hari yang sama
  - [PASS] Duplikasi antrian ditolak
  - [PASS] Dokter memulai pemeriksaan dari antrian
  - [PASS] Resep melebihi stok ditolak
  - [PASS] Pemeriksaan valid membuat rekam medis + transaksi + update antrian + kurangi stok

4. `php test_uat_kasir.php`
- Hasil:
  - [PASS] Pembayaran kurang dari total ditolak
  - [PASS] Metode bayar tidak valid ditolak
  - [PASS] Pembayaran tunai valid diproses
  - [PASS] Filter laporan periode hanya menampilkan transaksi pada rentang tanggal

## Skenario yang Tervalidasi Hari Ini
- Login valid Admin dan Dokter.
- Login invalid password.
- Login akun tidak terdaftar.
- Root route redirect ke login (baseline route behavior).
- Admin membuat antrian baru.
- Nomor antrian berurutan untuk hari yang sama.
- Penolakan duplikasi antrian pada hari yang sama.
- Dokter memulai pemeriksaan dari antrian (`waiting` -> `in-progress`).
- Penolakan resep yang melebihi stok obat.
- Pemeriksaan valid menghasilkan rekam medis + transaksi + update status antrian + pengurangan stok.
- Pembayaran kasir ditolak jika jumlah bayar kurang dari total.
- Pembayaran kasir ditolak jika metode bayar tidak valid.
- Pembayaran tunai valid mengubah status menjadi `paid` dan menghitung kembalian.
- Laporan kasir periode memfilter transaksi sesuai rentang tanggal.

## Temuan
- Sebagian skrip lama masih mengacu ke schema lama (contoh: `id`, `medical_record_id`).
- Sudah diperbaiki untuk skrip login agar sesuai schema final (`id_user`, `id_medical_record`).

## Skenario Yang Masih Pending Verifikasi Aktual
- Dashboard admin/dokter (akurasi statistik).
- Pendaftaran owner/pet (validasi wajib, format umur, soft delete).
- Antrian (cancel rules + listing/polling UI).
- Pemeriksaan (edit/snapshot harga lintas perubahan harga + rujukan digital flow).
- Kasir (listing unpaid/paid UI + receipt render + export PDF).
- Stok obat (CRUD/filter/status/soft delete).
- Rujukan (pending/print/nomor otomatis/QR).
- Laporan dokter (filter, statistik, export xlsx/pdf).
- Kelola staff admin oleh dokter.

## Rekomendasi Lanjutan
- Tambahkan automated Feature Test bertahap untuk skenario prioritas tinggi:
  1. Authentication + authorization guards
  2. Queue + examination flow
  3. Payment + report period flow
  4. Medicine stock and overstock validation
