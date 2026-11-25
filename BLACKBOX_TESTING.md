# HASIL PENGUJIAN BLACK BOX TESTING
## Sistem Pet Area - Klinik Hewan

**Tanggal Pengujian:** 9 Maret 2026  
**Metode:** Black Box Testing  
**Aplikasi:** Pet Area (Laravel 12.18.0, MySQL 8.0.30)

---

## 1. MODUL AUTENTIKASI (LOGIN & LOGOUT)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 1.1 | Login sebagai Dokter dengan data valid | 1. Buka halaman login<br>2. Pilih role "Dokter"<br>3. Masukkan email & password valid<br>4. Klik tombol Login | Email: dokter@example.test, Password: [kata sandi uji], Role: dokter | Berhasil login, redirect ke halaman Dashboard Dokter | ✅ Berhasil |
| 1.2 | Login sebagai Admin dengan data valid | 1. Buka halaman login<br>2. Pilih role "Admin"<br>3. Masukkan username & password valid<br>4. Klik tombol Login | Username: admin, Password: [kata sandi uji], Role: admin | Berhasil login, redirect ke halaman Dashboard Admin | ✅ Berhasil |
| 1.3 | Login dengan password salah | 1. Buka halaman login<br>2. Pilih role "Dokter"<br>3. Masukkan email valid & password salah<br>4. Klik tombol Login | Email: dokter@example.test, Password: [kata sandi salah], Role: dokter | Muncul pesan error "Password salah" | ✅ Berhasil |
| 1.4 | Login dengan email/username tidak terdaftar | 1. Buka halaman login<br>2. Masukkan email yang tidak terdaftar<br>3. Klik tombol Login | Email: tidakada@example.test, Password: [kata sandi uji], Role: dokter | Muncul pesan error "Akun tidak ditemukan" | ✅ Berhasil |
| 1.5 | Login dengan field kosong | 1. Buka halaman login<br>2. Kosongkan semua field<br>3. Klik tombol Login | Email: (kosong), Password: (kosong) | Muncul pesan validasi bahwa field wajib diisi | ✅ Berhasil |
| 1.6 | Login dengan fitur "Remember Me" | 1. Buka halaman login<br>2. Masukkan data valid<br>3. Centang "Ingat Saya"<br>4. Klik Login | Email: dokter@example.test, Password: [kata sandi uji], Centang Remember | Berhasil login, cookie remember tersimpan, saat buka kembali field terisi otomatis | ✅ Berhasil |
| 1.7 | Logout dari sistem | 1. Klik tombol Logout di navigasi | - | Session di-invalidasi, redirect ke halaman Login | ✅ Berhasil |
| 1.8 | Akses halaman dashboard tanpa login | 1. Buka URL dashboard langsung tanpa login | URL: /dokter/dashboard atau /admin/dashboard | Redirect otomatis ke halaman Login | ✅ Berhasil |

---

## 2. MODUL LUPA PASSWORD

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 2.1 | Kirim permintaan lupa password | 1. Klik "Lupa Password" di halaman login<br>2. Masukkan email terdaftar<br>3. Klik Kirim | Email: dokter@example.test | Pesan berhasil dikirim, link reset password tersedia | ✅ Berhasil |
| 2.2 | Reset password dengan password baru valid | 1. Buka halaman reset password<br>2. Masukkan password baru & konfirmasi<br>3. Klik Reset | Password: [kata sandi baru], Konfirmasi: [kata sandi baru] | Password berhasil diubah, redirect ke login | ✅ Berhasil |
| 2.3 | Reset password dengan password kurang dari 8 karakter | 1. Buka halaman reset password<br>2. Masukkan password pendek<br>3. Klik Reset | Password: [kurang dari 8 karakter], Konfirmasi: [sama] | Muncul pesan validasi "Password minimal 8 karakter" | ✅ Berhasil |
| 2.4 | Reset password dengan konfirmasi tidak cocok | 1. Buka halaman reset password<br>2. Masukkan password berbeda di field konfirmasi<br>3. Klik Reset | Password: [kata sandi baru], Konfirmasi: [berbeda] | Muncul pesan error "Konfirmasi password tidak cocok" | ✅ Berhasil |

---

## 3. MODUL GANTI PASSWORD (DOKTER)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 3.1 | Ganti password dengan data valid | 1. Login sebagai Dokter<br>2. Buka menu Ganti Password<br>3. Isi password lama, baru, dan konfirmasi<br>4. Klik Simpan | Password lama: password123, Baru: newpass123, Konfirmasi: newpass123 | Password berhasil diubah, muncul notifikasi sukses | ✅ Berhasil |
| 3.2 | Ganti password dengan password lama salah | 1. Login sebagai Dokter<br>2. Buka menu Ganti Password<br>3. Masukkan password lama yang salah<br>4. Klik Simpan | Password lama: salahpwd, Baru: newpass123, Konfirmasi: newpass123 | Muncul pesan error "Password lama tidak sesuai" | ✅ Berhasil |
| 3.3 | Ganti password tanpa mengisi semua field | 1. Login sebagai Dokter<br>2. Buka menu Ganti Password<br>3. Kosongkan field<br>4. Klik Simpan | Semua field kosong | Muncul pesan validasi field wajib diisi | ✅ Berhasil |

---

## 4. MODUL DASHBOARD DOKTER

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 4.1 | Menampilkan statistik dashboard | 1. Login sebagai Dokter<br>2. Buka halaman Dashboard | - | Menampilkan: Pemeriksaan Hari Ini, Total Pasien, Kontrol Mendatang | ✅ Berhasil |
| 4.2 | Menampilkan antrian hari ini | 1. Login sebagai Dokter<br>2. Lihat widget antrian di dashboard | - | Menampilkan daftar antrian hari ini terurut berdasar status | ✅ Berhasil |
| 4.3 | Menampilkan 5 riwayat rekam medis terbaru | 1. Login sebagai Dokter<br>2. Lihat widget rekam medis di dashboard | - | Menampilkan 5 rekam medis terbaru dengan detail treatment | ✅ Berhasil |
| 4.4 | Menampilkan 5 diagnosa terbanyak | 1. Login sebagai Dokter<br>2. Lihat widget diagnosa terbanyak | - | Menampilkan 5 diagnosa paling sering beserta jumlahnya | ✅ Berhasil |
| 4.5 | Menampilkan reminder kontrol mendatang | 1. Login sebagai Dokter<br>2. Lihat widget reminder | - | Menampilkan daftar pasien yang harus kontrol dalam 7 hari ke depan | ✅ Berhasil |

---

## 5. MODUL ANTRIAN PEMERIKSAAN (DOKTER)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 5.1 | Melihat daftar antrian hari ini | 1. Login sebagai Dokter<br>2. Buka menu Antrian | - | Menampilkan daftar antrian terbagi: menunggu, sedang diperiksa, selesai | ✅ Berhasil |
| 5.2 | Memulai pemeriksaan dari antrian | 1. Klik tombol "Mulai Pemeriksaan" pada antrian berstatus waiting | Antrian ID: 1 | Status antrian berubah ke "in-progress", doctor ID terisi, redirect ke form pemeriksaan | ✅ Berhasil |
| 5.3 | Skip/Lewati antrian | 1. Klik tombol "Skip" pada antrian berstatus waiting<br>2. Tambahkan catatan | Antrian ID: 2, Catatan: "Pemilik tidak hadir" | Status antrian berubah ke "skipped", catatan tersimpan dengan timestamp | ✅ Berhasil |
| 5.4 | Melihat detail antrian | 1. Klik salah satu antrian untuk melihat detail | Antrian ID: 1 | Menampilkan detail antrian, info hewan & pemilik, 5 riwayat medis terakhir | ✅ Berhasil |
| 5.5 | Auto-refresh antrian (Polling) | 1. Buka halaman antrian<br>2. Tunggu beberapa detik | - | Data antrian ter-refresh otomatis melalui AJAX polling | ✅ Berhasil |

---

## 6. MODUL PEMERIKSAAN / REKAM MEDIS (DOKTER)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 6.1 | Membuat rekam medis baru dengan data lengkap | 1. Mulai pemeriksaan dari antrian<br>2. Isi semua field<br>3. Tambahkan treatment & resep obat<br>4. Klik Simpan | Pet ID: 1, Keluhan: "Tidak mau makan", Diagnosa: "Gastritis", Berat: 4.5, Suhu: 39.2, Treatment: Injeksi, Obat: Amoxicillin x 10, Biaya periksa: 50000 | Rekam medis tersimpan, transaksi otomatis dibuat, status antrian berubah "completed" | ✅ Berhasil |
| 6.2 | Membuat rekam medis tanpa pet_id | 1. Coba submit form tanpa memilih hewan | Pet ID: (kosong) | Muncul pesan validasi "Pet wajib dipilih" | ✅ Berhasil |
| 6.3 | Perhitungan biaya otomatis | 1. Tambahkan treatment (Operasi Minor Rp 200.000)<br>2. Tambahkan obat (Amoxicillin 10 x Rp 5.000)<br>3. Isi biaya pemeriksaan Rp 50.000 | Biaya periksa: 50000, Treatment: 200000, Obat: 50000 | Total biaya otomatis terhitung: Rp 300.000 (50.000 + 200.000 + 50.000) | ✅ Berhasil |
| 6.4 | Snapshot harga obat saat pemeriksaan | 1. Buat rekam medis dengan obat harga Rp 5.000<br>2. Ubah harga obat menjadi Rp 10.000<br>3. Lihat rekam medis lama | Obat: Amoxicillin, Harga saat periksa: 5000 | Harga di rekam medis tetap Rp 5.000 (snapshot), tidak berubah mengikuti harga baru | ✅ Berhasil |
| 6.5 | Snapshot harga treatment saat pemeriksaan | 1. Buat rekam medis dengan treatment harga Rp 100.000<br>2. Ubah harga treatment<br>3. Lihat rekam medis lama | Treatment: Injeksi, Harga saat periksa: 100000 | Harga di pivot tabel tetap Rp 100.000 (snapshot) | ✅ Berhasil |
| 6.6 | Membuat rekam medis dengan rujukan | 1. Centang opsi "Buat Rujukan"<br>2. Isi tujuan & alasan rujukan<br>3. Klik Simpan | Tujuan: "RS Hewan Jakarta", Alasan: "Perlu operasi lanjutan" | Rekam medis tersimpan + surat rujukan otomatis dibuat dengan nomor RUJ-YYMMDD-XXXX | ✅ Berhasil |
| 6.7 | Pencarian hewan via AJAX | 1. Ketik nama hewan di field pencarian | Keyword: "Milo" | Muncul dropdown hasil pencarian hewan yang sesuai | ✅ Berhasil |
| 6.8 | Pencarian obat via AJAX | 1. Ketik nama obat di field pencarian obat | Keyword: "Amox" | Muncul daftar obat aktif yang memiliki stok | ✅ Berhasil |
| 6.9 | Tambah treatment baru via AJAX | 1. Klik tombol "Tambah Treatment Baru"<br>2. Isi nama, harga, deskripsi<br>3. Klik Simpan | Nama: "Nebulisasi", Harga: 75000, Deskripsi: "Terapi uap" | Treatment baru tersimpan, langsung muncul di daftar pilihan | ✅ Berhasil |
| 6.10 | Hapus treatment via AJAX | 1. Klik tombol hapus pada treatment<br>2. Konfirmasi hapus | Treatment ID: 5 | Treatment berhasil di-soft delete, data riwayat medis tetap utuh | ✅ Berhasil |

---

## 7. MODUL RIWAYAT PASIEN (DOKTER)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 7.1 | Melihat daftar semua riwayat rekam medis | 1. Login sebagai Dokter<br>2. Buka menu Riwayat Pasien | - | Menampilkan daftar rekam medis dengan paginasi (15/halaman) | ✅ Berhasil |
| 7.2 | Pencarian riwayat berdasarkan nama hewan | 1. Masukkan nama hewan di field pencarian<br>2. Klik Cari | nama_hewan: "Milo" | Menampilkan rekam medis milik hewan bernama "Milo" | ✅ Berhasil |
| 7.3 | Pencarian riwayat berdasarkan nama pemilik | 1. Masukkan nama pemilik di field pencarian<br>2. Klik Cari | nama_pemilik: "Budi" | Menampilkan rekam medis semua hewan milik pemilik "Budi" | ✅ Berhasil |
| 7.4 | Filter riwayat berdasarkan tanggal | 1. Pilih tanggal pada filter<br>2. Klik Cari | tanggal: "2026-03-09" | Menampilkan rekam medis pada tanggal 9 Maret 2026 | ✅ Berhasil |
| 7.5 | Melihat detail rekam medis | 1. Klik salah satu rekam medis | Record ID: 1 | Menampilkan detail lengkap: diagnosis, treatment, resep, rujukan | ✅ Berhasil |
| 7.6 | Edit rekam medis | 1. Klik tombol Edit pada rekam medis<br>2. Ubah diagnosis<br>3. Klik Simpan | Diagnosa baru: "Dermatitis Alergi" | Rekam medis terupdate, biaya dihitung ulang, transaksi tersinkron | ✅ Berhasil |
| 7.7 | Hapus rekam medis | 1. Klik tombol Hapus pada rekam medis<br>2. Konfirmasi hapus | Record ID: 5 | Rekam medis di-soft delete, tidak muncul di daftar aktif | ✅ Berhasil |
| 7.8 | Melihat riwayat per hewan (by pet) | 1. Klik nama hewan untuk melihat semua riwayat | Pet ID: 1 | Menampilkan semua rekam medis untuk hewan tersebut (dengan paginasi) | ✅ Berhasil |
| 7.9 | Mode pencarian pasien | 1. Aktifkan mode pencarian pasien<br>2. Cari berdasarkan nama/pemilik/no HP | Keyword: "Milo" | Menampilkan daftar hewan yang memiliki riwayat medis | ✅ Berhasil |
| 7.10 | Sorting riwayat (ascending/descending) | 1. Klik header kolom untuk sorting<br>2. Pilih asc atau desc | sort_field: "visit_date", sort: "desc" | Data terurut berdasarkan tanggal kunjungan terbaru | ✅ Berhasil |
| 7.11 | Export riwayat medis | 1. Klik tombol Export | - | File riwayat medis berhasil diunduh | ✅ Berhasil |

---

## 8. MODUL LAPORAN MEDIS (DOKTER)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 8.1 | Melihat laporan medis dengan filter tanggal | 1. Buka menu Laporan<br>2. Isi tanggal mulai & akhir<br>3. Klik Filter | start_date: 2026-03-01, end_date: 2026-03-09 | Menampilkan laporan rekam medis dalam rentang tanggal beserta statistik | ✅ Berhasil |
| 8.2 | Filter laporan berdasarkan diagnosa | 1. Buka menu Laporan<br>2. Pilih diagnosa dari dropdown<br>3. Klik Filter | diagnosis: "Gastritis" | Menampilkan rekam medis dengan diagnosa Gastritis saja | ✅ Berhasil |
| 8.3 | Filter laporan berdasarkan dokter | 1. Buka menu Laporan<br>2. Pilih dokter dari dropdown<br>3. Klik Filter | doctor_id: 1 | Menampilkan rekam medis dari dokter terpilih | ✅ Berhasil |
| 8.4 | Filter laporan berdasarkan nama hewan | 1. Buka menu Laporan<br>2. Masukkan nama hewan<br>3. Klik Filter | pet_name: "Milo" | Menampilkan rekam medis hewan bernama Milo | ✅ Berhasil |
| 8.5 | Menampilkan statistik laporan | 1. Filter laporan dengan rentang tanggal | - | Menampilkan: total pasien unik, jumlah kucing, jumlah anjing, diagnosa terbanyak | ✅ Berhasil |
| 8.6 | Export laporan ke Excel (.xlsx) | 1. Klik tombol "Export Excel" | Filter aktif | File Excel berhasil diunduh sesuai filter yang diterapkan | ✅ Berhasil |
| 8.7 | Export laporan ke PDF | 1. Klik tombol "Export PDF" | Filter aktif | File PDF berhasil diunduh dengan statistik dan tabel rekam medis | ✅ Berhasil |

---

## 9. MODUL KELOLA STAFF / ADMIN (DOKTER)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 9.1 | Melihat daftar staff admin | 1. Login sebagai Dokter<br>2. Buka menu Kelola Staff | - | Menampilkan daftar admin dengan paginasi (10/halaman) | ✅ Berhasil |
| 9.2 | Menambah admin baru dengan data valid | 1. Klik Tambah Staff<br>2. Isi nama, email, username, password<br>3. Klik Simpan | Nama: "Admin Baru", Email: admin2@example.test, Username: admin2, Password: [kata sandi uji], Konfirmasi: [kata sandi uji] | Admin baru berhasil ditambahkan, muncul di daftar | ✅ Berhasil |
| 9.3 | Menambah admin dengan email duplikat | 1. Klik Tambah Staff<br>2. Gunakan email yang sudah terdaftar<br>3. Klik Simpan | Email: admin@example.test (sudah ada) | Muncul pesan error "Email sudah digunakan" | ✅ Berhasil |
| 9.4 | Menambah admin dengan username duplikat | 1. Klik Tambah Staff<br>2. Gunakan username yang sudah terdaftar<br>3. Klik Simpan | Username: admin (sudah ada) | Muncul pesan error "Username sudah digunakan" | ✅ Berhasil |
| 9.5 | Menambah admin dengan password < 8 karakter | 1. Klik Tambah Staff<br>2. Isi password pendek<br>3. Klik Simpan | Password: [kurang dari 8 karakter], Konfirmasi: [sama] | Muncul pesan validasi "Password minimal 8 karakter" | ✅ Berhasil |
| 9.6 | Edit data admin | 1. Klik tombol Edit pada admin<br>2. Ubah nama<br>3. Klik Simpan | Nama baru: "Admin Updated" | Data admin berhasil diupdate | ✅ Berhasil |
| 9.7 | Hapus admin | 1. Klik tombol Hapus pada admin<br>2. Konfirmasi hapus | Staff ID: 2 | Admin berhasil dihapus dari daftar | ✅ Berhasil |

---

## 10. MODUL DASHBOARD ADMIN

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 10.1 | Menampilkan statistik dashboard admin | 1. Login sebagai Admin<br>2. Buka halaman Dashboard | - | Menampilkan: Total Pemilik, Total Hewan, Transaksi Belum Bayar, Pendapatan Hari Ini | ✅ Berhasil |
| 10.2 | Menampilkan obat stok rendah | 1. Lihat widget stok obat di dashboard | - | Menampilkan 5 obat dengan stok ≤ 10 unit | ✅ Berhasil |
| 10.3 | Menampilkan rujukan pending | 1. Lihat widget rujukan di dashboard | - | Menampilkan jumlah rujukan berstatus pending | ✅ Berhasil |
| 10.4 | Menampilkan transaksi belum bayar terbaru | 1. Lihat widget transaksi di dashboard | - | Menampilkan 5 transaksi belum bayar terbaru | ✅ Berhasil |
| 10.5 | Menampilkan log WhatsApp | 1. Lihat widget WhatsApp di dashboard | - | Menampilkan 10 log WhatsApp reminder terbaru | ✅ Berhasil |

---

## 11. MODUL PENDAFTARAN PEMILIK (ADMIN)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 11.1 | Menambah pemilik baru dengan data valid | 1. Buka menu Pendaftaran > Pemilik<br>2. Klik Tambah Pemilik<br>3. Isi data lengkap<br>4. Klik Simpan | Nama: "Pemilik Uji", No HP: 08XXXXXXXXXX, Alamat: "Jl. Contoh", Email: pemilik@example.test | Pemilik tersimpan, kode otomatis OWN-XXXX, nomor HP otomatis format +62 | ✅ Berhasil |
| 11.2 | Auto-format nomor HP dari 08xx ke +628xx | 1. Tambah pemilik baru<br>2. Masukkan no HP format lokal | No HP: 08XXXXXXXXXX | Nomor tersimpan sebagai +628XXXXXXXXXX | ✅ Berhasil |
| 11.3 | Auto-generate kode pemilik (OWN-XXXX) | 1. Tambah pemilik baru<br>2. Simpan | - | Kode pemilik otomatis terisi (contoh: OWN-0001, OWN-0002) | ✅ Berhasil |
| 11.4 | Menambah pemilik tanpa no HP (wajib) | 1. Tambah pemilik baru<br>2. Kosongkan field No HP<br>3. Klik Simpan | Nama: "Test", No HP: (kosong) | Muncul pesan validasi "No HP wajib diisi" | ✅ Berhasil |
| 11.5 | Menambah pemilik tanpa nama (wajib) | 1. Tambah pemilik baru<br>2. Kosongkan field Nama<br>3. Klik Simpan | Nama: (kosong), No HP: 08XXXXXXXXXX | Muncul pesan validasi "Nama wajib diisi" | ✅ Berhasil |
| 11.6 | Melihat detail pemilik | 1. Klik nama pemilik pada daftar | Owner ID: 1 | Menampilkan detail pemilik dan daftar semua hewan yang dimiliki | ✅ Berhasil |
| 11.7 | Edit data pemilik | 1. Klik tombol Edit<br>2. Ubah alamat<br>3. Klik Simpan | Alamat baru: "Jl. Kenari 5" | Data pemilik berhasil diupdate | ✅ Berhasil |
| 11.8 | Hapus pemilik (soft delete) | 1. Klik tombol Hapus<br>2. Konfirmasi hapus | Owner ID: 5 | Pemilik di-soft delete, data riwayat medis hewan tetap tersedia | ✅ Berhasil |
| 11.9 | Pencarian pemilik berdasarkan nama/no HP | 1. Masukkan keyword di field pencarian | Keyword: "Budi" atau "0812" | Menampilkan pemilik yang sesuai keyword | ✅ Berhasil |
| 11.10 | Pencarian pemilik via API (AJAX) | 1. Ketik di field pencarian AJAX | Keyword: "San" | Muncul dropdown pemilik yang cocok termasuk soft-deleted | ✅ Berhasil |

---

## 12. MODUL PENDAFTARAN HEWAN (ADMIN)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 12.1 | Menambah hewan baru untuk pemilik | 1. Buka detail pemilik<br>2. Klik Tambah Hewan<br>3. Isi data lengkap<br>4. Klik Simpan | Nama: "Milo", Jenis: "Kucing", Ras: "Persia", Tgl Lahir: 2024-01-15, Kelamin: "jantan" | Hewan tersimpan, No RM otomatis tergenerate (RMK0001) | ✅ Berhasil |
| 12.2 | Auto-generate No RM berdasarkan jenis hewan | 1. Tambah hewan jenis Kucing<br>2. Tambah hewan jenis Anjing | Jenis: Kucing, lalu Jenis: Anjing | No RM Kucing: RMK0001, No RM Anjing: RMA0001 | ✅ Berhasil |
| 12.3 | No RM untuk berbagai jenis hewan | 1. Tambah hewan berbagai jenis | Kelinci, Hamster, Burung, Reptil, Lainnya | RML (Kelinci), RMH (Hamster), RMB (Burung), RMR (Reptil), RMX (Lainnya) | ✅ Berhasil |
| 12.4 | Menambah hewan tanpa nama (wajib) | 1. Kosongkan field nama<br>2. Klik Simpan | Nama: (kosong), Jenis: "Kucing" | Muncul pesan validasi "Nama hewan wajib diisi" | ✅ Berhasil |
| 12.5 | Menambah hewan tanpa jenis (wajib) | 1. Kosongkan field jenis<br>2. Klik Simpan | Nama: "Milo", Jenis: (kosong) | Muncul pesan validasi "Jenis hewan wajib diisi" | ✅ Berhasil |
| 12.6 | Menambah hewan dengan jenis kelamin invalid | 1. Masukkan jenis kelamin selain jantan/betina | Jenis kelamin: "laki-laki" | Muncul pesan validasi "Jenis kelamin harus jantan atau betina" | ✅ Berhasil |
| 12.7 | Edit data hewan | 1. Klik tombol Edit pada hewan<br>2. Ubah ras<br>3. Klik Simpan | Ras baru: "Anggora" | Data hewan berhasil diupdate | ✅ Berhasil |
| 12.8 | Hapus hewan (soft delete) | 1. Klik tombol Hapus<br>2. Konfirmasi hapus | Pet ID: 3 | Hewan di-soft delete, riwayat medis tetap bisa diakses | ✅ Berhasil |
| 12.9 | Menampilkan umur hewan otomatis | 1. Lihat detail hewan yang punya tgl lahir | Tgl Lahir: 2024-01-15 | Menampilkan umur: "2 tahun 1 bulan" (dihitung otomatis) | ✅ Berhasil |

---

## 13. MODUL ANTRIAN PASIEN (ADMIN)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 13.1 | Membuat antrian baru | 1. Buka menu Antrian<br>2. Klik Tambah Antrian<br>3. Pilih hewan & tanggal<br>4. Klik Simpan | Pet ID: 1, Tanggal: hari ini, Keluhan: "Batuk-batuk" | Antrian tersimpan, nomor antrian otomatis, status: waiting | ✅ Berhasil |
| 13.2 | Auto-generate nomor antrian per hari | 1. Buat 3 antrian di hari yang sama | 3 hewan berbeda di tanggal sama | Nomor antrian: 1, 2, 3 (urut per hari) | ✅ Berhasil |
| 13.3 | Duplikasi hewan di tanggal sama | 1. Buat antrian untuk hewan yang sudah terdaftar hari ini | Pet ID: 1 (sudah ada antrian hari ini) | Muncul pesan error "Hewan sudah memiliki antrian pada tanggal tersebut" | ✅ Berhasil |
| 13.4 | Membuat antrian untuk tanggal lampau | 1. Pilih tanggal sebelum hari ini<br>2. Klik Simpan | Tanggal: 2026-03-01 (lampau) | Muncul pesan validasi "Tanggal tidak boleh sebelum hari ini" | ✅ Berhasil |
| 13.5 | Membuat antrian tanpa memilih hewan | 1. Kosongkan field hewan<br>2. Klik Simpan | Pet ID: (kosong) | Muncul pesan validasi "Hewan wajib dipilih" | ✅ Berhasil |
| 13.6 | Keluhan melebihi 500 karakter | 1. Masukkan keluhan lebih dari 500 karakter | Keluhan: (teks 501 karakter) | Muncul pesan validasi "Keluhan maksimal 500 karakter" | ✅ Berhasil |
| 13.7 | Melihat daftar antrian hari ini | 1. Buka menu Antrian<br>2. Pilih tanggal hari ini | - | Menampilkan antrian terurut berdasar status dan nomor antrian | ✅ Berhasil |
| 13.8 | Cancel/Batalkan antrian (status waiting) | 1. Klik tombol Batalkan pada antrian waiting | Antrian ID: 3 | Status antrian berubah ke "cancelled" | ✅ Berhasil |
| 13.9 | Cancel antrian yang sudah completed | 1. Coba batalkan antrian berstatus completed | Antrian ID: 1 (completed) | Gagal, antrian yang sudah selesai tidak bisa dibatalkan | ✅ Berhasil |
| 13.10 | Pencarian hewan via API untuk antrian | 1. Ketik nama hewan/pemilik di field pencarian | Keyword: "Milo" | Muncul daftar hewan yang sesuai beserta info pemilik | ✅ Berhasil |
| 13.11 | Auto-refresh antrian via polling | 1. Buka halaman antrian<br>2. Tunggu perubahan | - | Data antrian ter-refresh otomatis via AJAX | ✅ Berhasil |

---

## 14. MODUL KASIR / PEMBAYARAN (ADMIN)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 14.1 | Melihat daftar transaksi belum bayar | 1. Buka menu Kasir<br>2. Filter status: unpaid | - | Menampilkan daftar transaksi belum bayar (default) dengan paginasi 15/halaman | ✅ Berhasil |
| 14.2 | Melihat daftar transaksi lunas | 1. Buka menu Kasir<br>2. Filter status: paid | - | Menampilkan daftar transaksi yang sudah dibayar | ✅ Berhasil |
| 14.3 | Filter transaksi berdasarkan tanggal | 1. Pilih tanggal pada filter<br>2. Klik Filter | Tanggal: 2026-03-09 | Menampilkan transaksi pada tanggal tersebut | ✅ Berhasil |
| 14.4 | Melihat detail transaksi | 1. Klik salah satu transaksi | Transaction ID: 1 | Menampilkan detail: biaya pemeriksaan, tindakan, obat, total, info pasien | ✅ Berhasil |
| 14.5 | Proses pembayaran tunai dengan data valid | 1. Buka detail transaksi<br>2. Masukkan jumlah bayar ≥ total<br>3. Pilih metode "Tunai"<br>4. Klik Bayar | Total: 300000, Jumlah Bayar: 350000, Metode: tunai | Pembayaran berhasil, kembalian: Rp 50.000, status: paid, tgl_bayar terisi | ✅ Berhasil |
| 14.6 | Proses pembayaran transfer | 1. Masukkan jumlah bayar<br>2. Pilih metode "Transfer"<br>3. Klik Bayar | Total: 300000, Jumlah Bayar: 300000, Metode: transfer | Pembayaran berhasil, status: paid | ✅ Berhasil |
| 14.7 | Proses pembayaran QRIS | 1. Masukkan jumlah bayar<br>2. Pilih metode "QRIS"<br>3. Klik Bayar | Total: 300000, Jumlah Bayar: 300000, Metode: qris | Pembayaran berhasil, status: paid | ✅ Berhasil |
| 14.8 | Pembayaran dengan jumlah kurang dari total | 1. Masukkan jumlah bayar < total<br>2. Klik Bayar | Total: 300000, Jumlah Bayar: 200000 | Muncul pesan validasi "Jumlah bayar harus ≥ total biaya" | ✅ Berhasil |
| 14.9 | Pembayaran dengan metode tidak valid | 1. Manipulasi field metode bayar | Metode: "bitcoin" | Muncul pesan validasi "Metode bayar harus tunai, transfer, atau qris" | ✅ Berhasil |
| 14.10 | Perhitungan kembalian otomatis | 1. Proses pembayaran dengan jumlah lebih | Total: 150000, Bayar: 200000 | Kembalian otomatis terhitung: Rp 50.000 | ✅ Berhasil |
| 14.11 | Cetak struk/receipt | 1. Setelah pembayaran berhasil<br>2. Klik tombol Cetak Struk | Transaction ID: 1 | Menampilkan halaman struk siap cetak dengan detail lengkap | ✅ Berhasil |
| 14.12 | Hapus transaksi | 1. Klik tombol Hapus pada transaksi<br>2. Konfirmasi hapus | Transaction ID: 5 | Transaksi berhasil dihapus | ✅ Berhasil |

---

## 15. MODUL LAPORAN HARIAN KASIR (ADMIN)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 15.1 | Melihat laporan harian | 1. Buka menu Kasir > Laporan Harian<br>2. Pilih tanggal | Tanggal: 2026-03-09 | Menampilkan semua transaksi paid hari itu dengan ringkasan | ✅ Berhasil |
| 15.2 | Menampilkan total pendapatan harian | 1. Buka laporan harian | - | Menampilkan total pendapatan dari semua transaksi lunas | ✅ Berhasil |
| 15.3 | Menampilkan breakdown metode pembayaran | 1. Buka laporan harian | - | Menampilkan rincian: total Tunai, total Transfer, total QRIS | ✅ Berhasil |
| 15.4 | Export laporan harian ke PDF | 1. Klik tombol "Export PDF" | Tanggal: 2026-03-09 | File PDF laporan harian berhasil diunduh dengan detail transaksi | ✅ Berhasil |

---

## 16. MODUL STOK OBAT (ADMIN)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 16.1 | Menambah obat baru dengan data lengkap | 1. Buka menu Stok Obat<br>2. Klik Tambah Obat<br>3. Isi semua field<br>4. Klik Simpan | Nama: "Amoxicillin 250mg", Dosis: "250mg", Satuan: "tablet", Stok: 100, Stok Min: 10, Harga: 5000 | Obat berhasil ditambahkan dan muncul di daftar | ✅ Berhasil |
| 16.2 | Menambah obat tanpa nama (wajib) | 1. Kosongkan field nama<br>2. Klik Simpan | Nama: (kosong) | Muncul pesan validasi "Nama obat wajib diisi" | ✅ Berhasil |
| 16.3 | Menambah obat tanpa satuan (wajib) | 1. Kosongkan field satuan<br>2. Klik Simpan | Satuan: (kosong) | Muncul pesan validasi "Satuan wajib diisi" | ✅ Berhasil |
| 16.4 | Menambah obat dengan stok negatif | 1. Masukkan stok negatif<br>2. Klik Simpan | Stok: -5 | Muncul pesan validasi "Stok minimal 0" | ✅ Berhasil |
| 16.5 | Menambah obat dengan harga negatif | 1. Masukkan harga negatif<br>2. Klik Simpan | Harga: -1000 | Muncul pesan validasi "Harga minimal 0" | ✅ Berhasil |
| 16.6 | Edit data obat | 1. Klik tombol Edit<br>2. Ubah harga<br>3. Klik Simpan | Harga baru: 7500 | Data obat berhasil diupdate | ✅ Berhasil |
| 16.7 | Tambah stok obat | 1. Klik tombol Tambah Stok<br>2. Masukkan jumlah<br>3. Klik Simpan | Jumlah tambah: 50 | Stok obat bertambah dari 100 menjadi 150 | ✅ Berhasil |
| 16.8 | Menampilkan status stok "Tersedia" | 1. Lihat obat dengan stok > 10 | Stok: 100 | Label status: "Tersedia" (hijau) | ✅ Berhasil |
| 16.9 | Menampilkan status stok "Stok Rendah" | 1. Lihat obat dengan stok 1-10 | Stok: 5 | Label status: "Stok Rendah" (kuning) | ✅ Berhasil |
| 16.10 | Menampilkan status stok "Habis" | 1. Lihat obat dengan stok 0 | Stok: 0 | Label status: "Habis" (merah) | ✅ Berhasil |
| 16.11 | Filter obat berdasarkan stok rendah | 1. Pilih filter "Stok Rendah" | - | Menampilkan hanya obat dengan stok ≤ 10 | ✅ Berhasil |
| 16.12 | Filter obat habis | 1. Pilih filter "Habis" | - | Menampilkan hanya obat dengan stok 0 | ✅ Berhasil |
| 16.13 | Pencarian obat berdasarkan nama | 1. Masukkan keyword pencarian | Keyword: "Amox" | Menampilkan obat yang mengandung kata "Amox" | ✅ Berhasil |
| 16.14 | Hapus obat (soft delete) | 1. Klik tombol Hapus<br>2. Konfirmasi hapus | Medicine ID: 5 | Obat di-soft delete, riwayat resep tetap tersedia | ✅ Berhasil |
| 16.15 | Menampilkan statistik obat | 1. Buka halaman daftar obat | - | Menampilkan: total obat, jumlah stok rendah, jumlah stok habis | ✅ Berhasil |

---

## 17. MODUL SURAT RUJUKAN (ADMIN)

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 17.1 | Melihat daftar surat rujukan | 1. Buka menu Surat Rujukan | - | Menampilkan daftar rujukan dengan status pending & printed beserta statistik | ✅ Berhasil |
| 17.2 | Filter rujukan berdasarkan status pending | 1. Pilih filter "Pending" | - | Menampilkan hanya rujukan berstatus pending | ✅ Berhasil |
| 17.3 | Filter rujukan berdasarkan status printed | 1. Pilih filter "Printed" | - | Menampilkan hanya rujukan yang sudah dicetak | ✅ Berhasil |
| 17.4 | Melihat detail surat rujukan | 1. Klik salah satu rujukan | Referral ID: 1 | Menampilkan detail: nomor rujukan, tujuan, alasan, diagnosa, dokter | ✅ Berhasil |
| 17.5 | Cetak surat rujukan | 1. Klik tombol Cetak pada rujukan<br>2. Konfirmasi cetak | Referral ID: 1 | Rujukan dicetak dengan QR code, status berubah ke "printed", timestamp tercatat | ✅ Berhasil |
| 17.6 | Auto-generate nomor rujukan | 1. Buat rujukan melalui pemeriksaan | - | Nomor otomatis: RUJ-260309-0001 (format RUJ-YYMMDD-XXXX) | ✅ Berhasil |
| 17.7 | QR Code pada surat rujukan | 1. Cetak surat rujukan<br>2. Periksa QR code | - | QR code berisi: nomor rujukan, nama dokter, ID dokter, tanggal | ✅ Berhasil |

---

## 18. MODUL REMINDER WHATSAPP

| No | Skenario Pengujian | Langkah Pengujian | Data Uji | Hasil yang Diharapkan | Status |
|----|---------------------|-------------------|----------|----------------------|--------|
| 18.1 | Kirim reminder manual (H-1 kontrol) | 1. Klik tombol "Kirim Reminder" di dashboard Admin | - | Sistem mencari rekam medis dengan kontrol besok, WhatsApp terkirim, log tersimpan | ✅ Berhasil |
| 18.2 | Log reminder tersimpan | 1. Kirim reminder<br>2. Cek widget log WhatsApp | - | Log WhatsApp menampilkan: penerima, status "Terkirim (H-1)", waktu kirim | ✅ Berhasil |
| 18.3 | Reminder otomatis via scheduler | 1. Jalankan artisan command send:reminder-kontrol | - | Reminder otomatis terkirim untuk pasien yang kontrol besok | ✅ Berhasil |

---

## RINGKASAN HASIL PENGUJIAN

| No | Modul | Jumlah Test Case | Berhasil | Gagal |
|----|-------|:-----------------:|:--------:|:-----:|
| 1 | Autentikasi (Login/Logout) | 8 | 8 | 0 |
| 2 | Lupa Password | 4 | 4 | 0 |
| 3 | Ganti Password (Dokter) | 3 | 3 | 0 |
| 4 | Dashboard Dokter | 5 | 5 | 0 |
| 5 | Antrian Pemeriksaan (Dokter) | 5 | 5 | 0 |
| 6 | Pemeriksaan / Rekam Medis | 10 | 10 | 0 |
| 7 | Riwayat Pasien | 11 | 11 | 0 |
| 8 | Laporan Medis | 7 | 7 | 0 |
| 9 | Kelola Staff | 7 | 7 | 0 |
| 10 | Dashboard Admin | 5 | 5 | 0 |
| 11 | Pendaftaran Pemilik | 10 | 10 | 0 |
| 12 | Pendaftaran Hewan | 9 | 9 | 0 |
| 13 | Antrian Pasien (Admin) | 11 | 11 | 0 |
| 14 | Kasir / Pembayaran | 12 | 12 | 0 |
| 15 | Laporan Harian Kasir | 4 | 4 | 0 |
| 16 | Stok Obat | 15 | 15 | 0 |
| 17 | Surat Rujukan | 7 | 7 | 0 |
| 18 | Reminder WhatsApp | 3 | 3 | 0 |
| **TOTAL** | **18 Modul** | **136** | **136** | **0** |

---

**Kesimpulan:**  
Seluruh fitur pada sistem Pet Area telah diuji menggunakan metode Black Box Testing dengan total **136 skenario pengujian** yang mencakup **18 modul**. Semua skenario pengujian meliputi pengujian data valid, data invalid, validasi input, perhitungan otomatis, dan alur bisnis utama. Hasil pengujian menunjukkan bahwa semua fitur berfungsi sesuai dengan yang diharapkan.
