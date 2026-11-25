# HASIL PENGUJIAN USER ACCEPTANCE TESTING (UAT)
## Sistem Pet Area - Klinik Hewan

**Tanggal Pengujian:** 9 Maret 2026  
**Metode:** User Acceptance Testing (UAT)  
**Aplikasi:** Pet Area (Laravel 12.18.0, MySQL 8.0.30)  
**Penguji:**
- **Dokter:** drh. [Nama Dokter] (Role: Dokter)
- **Admin:** [Nama Admin] (Role: Admin)

**Tujuan:** Memastikan seluruh fitur sistem Pet Area dapat diterima dan berfungsi sesuai kebutuhan pengguna akhir (dokter & admin) dalam skenario penggunaan nyata di klinik hewan.

---

## 1. MODUL AUTENTIKASI

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 1 | Admin | Login dengan username dan password yang valid | Berhasil masuk ke Dashboard Admin, menampilkan statistik ringkasan klinik | ✅ Diterima | Redirect otomatis ke dashboard sesuai role |
| 2 | Admin | Login dengan password yang salah | Sistem menampilkan pesan error "Password salah" dan tetap di halaman login | ✅ Diterima | Pesan error jelas dan mudah dipahami |
| 3 | Admin | Login dengan username yang tidak terdaftar | Sistem menampilkan pesan error "Akun tidak ditemukan" | ✅ Diterima | Tidak mengekspos informasi sensitif |
| 4 | Admin | Login dengan field kosong tanpa mengisi data | Sistem menampilkan pesan validasi bahwa field wajib diisi | ✅ Diterima | Validasi berjalan di sisi client dan server |
| 5 | Admin | Login dengan centang "Ingat Saya" lalu tutup dan buka kembali browser | Field username terisi otomatis saat membuka halaman login kembali | ✅ Diterima | Cookie remember tersimpan dengan benar |
| 6 | Dokter | Login dengan email dan password yang valid | Berhasil masuk ke Dashboard Dokter, menampilkan statistik pemeriksaan | ✅ Diterima | Redirect otomatis ke dashboard dokter |
| 7 | Dokter | Login dengan password yang salah | Sistem menampilkan pesan error "Password salah" dan tetap di halaman login | ✅ Diterima | Pesan error konsisten dengan role Admin |
| 8 | Dokter | Login dengan email yang tidak terdaftar | Sistem menampilkan pesan error "Akun tidak ditemukan" | ✅ Diterima | - |
| 9 | Admin | Logout dari sistem | Session di-invalidasi, redirect ke halaman login, tidak bisa akses dashboard tanpa login ulang | ✅ Diterima | Session dan CSRF token di-regenerasi |
| 10 | Dokter | Logout dari sistem | Session di-invalidasi, redirect ke halaman login | ✅ Diterima | Kedua guard (dokter & admin) di-logout |
| 11 | Dokter | Akses halaman dashboard langsung tanpa login | Redirect otomatis ke halaman login | ✅ Diterima | Middleware auth berfungsi dengan benar |
| 12 | Admin | Akses halaman dashboard langsung tanpa login | Redirect otomatis ke halaman login | ✅ Diterima | Middleware auth berfungsi dengan benar |

---

## 2. MODUL LUPA PASSWORD & GANTI PASSWORD

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 13 | Dokter | Mengakses halaman lupa password dan mengirim permintaan reset | Halaman lupa password tampil, tautan reset dikirim melalui email | ✅ Diterima | Reset password menggunakan token yang kedaluwarsa setelah masa berlaku broker |
| 14 | Dokter | Reset password dengan password baru yang valid (min 8 karakter) | Password berhasil diubah, redirect ke halaman login | ✅ Diterima | Validasi panjang password berfungsi |
| 15 | Dokter | Reset password dengan password kurang dari 8 karakter | Sistem menampilkan pesan "Password minimal 8 karakter" | ✅ Diterima | Validasi minimum karakter berjalan |
| 16 | Dokter | Reset password dengan konfirmasi password yang tidak cocok | Sistem menampilkan pesan "Konfirmasi password tidak cocok" | ✅ Diterima | Validasi confirmed rule berjalan |
| 17 | Dokter | Ganti password dengan memasukkan password lama yang benar | Password berhasil diubah, muncul notifikasi sukses | ✅ Diterima | Menggunakan guard dokter |
| 18 | Dokter | Ganti password dengan memasukkan password lama yang salah | Sistem menampilkan pesan "Password lama tidak sesuai" | ✅ Diterima | current_password validation berfungsi |
| 19 | Dokter | Ganti password tanpa mengisi semua field | Sistem menampilkan pesan validasi field wajib diisi | ✅ Diterima | Semua field required |

---

## 3. MODUL DASHBOARD

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 20 | Admin | Melihat statistik dashboard: Total Pemilik, Total Hewan, Transaksi Belum Bayar, Pendapatan Hari Ini | Semua data statistik tampil dengan angka yang akurat dan terkini | ✅ Diterima | Data di-cache selama 60 detik |
| 21 | Admin | Melihat widget obat stok rendah di dashboard | Menampilkan obat dengan stok ≤ 10 unit untuk alert restock | ✅ Diterima | Membantu admin memantau ketersediaan obat |
| 22 | Admin | Melihat widget rujukan pending di dashboard | Menampilkan jumlah surat rujukan yang belum dicetak | ✅ Diterima | - |
| 23 | Admin | Melihat widget 5 transaksi belum bayar terbaru | Menampilkan daftar transaksi pending dengan nama pasien dan total biaya | ✅ Diterima | Quick access ke transaksi yang perlu diproses |
| 24 | Admin | Melihat widget 10 log WhatsApp reminder terbaru | Menampilkan riwayat pengiriman reminder beserta status | ✅ Diterima | Terintegrasi dengan Fonnte WhatsApp API |
| 25 | Dokter | Melihat statistik dashboard: Pemeriksaan Hari Ini, Total Pasien, Kontrol Mendatang | Semua data statistik tampil akurat | ✅ Diterima | Data di-cache selama 60 detik |
| 26 | Dokter | Melihat daftar antrian hari ini di dashboard | Antrian tampil terurut berdasarkan status (menunggu → diperiksa → selesai) | ✅ Diterima | Sorting berdasar status dan nomor antrian |
| 27 | Dokter | Melihat 5 diagnosa terbanyak di dashboard | Menampilkan 5 diagnosa paling sering beserta jumlah kasusnya | ✅ Diterima | Membantu dokter melihat tren penyakit |
| 28 | Dokter | Melihat reminder kontrol mendatang di dashboard | Menampilkan pasien yang harus kontrol dalam 7 hari ke depan | ✅ Diterima | Menampilkan nama hewan, pemilik, tanggal kontrol |
| 29 | Dokter | Melihat 5 rekam medis terbaru di dashboard | Menampilkan pemeriksaan terakhir dengan ringkasan diagnosa dan treatment | ✅ Diterima | Termasuk relasi treatment |

---

## 4. MODUL PENDAFTARAN PEMILIK HEWAN

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 30 | Admin | Mendaftarkan pemilik baru dengan data lengkap (Nama, No HP, Alamat) | Pemilik tersimpan dengan kode otomatis OWN-XXXX dan No HP terformat +62 | ✅ Diterima | Auto-generate kode & auto-format HP 08xx → +628xx |
| 31 | Admin | Mendaftarkan pemilik tanpa mengisi nama (field wajib) | Sistem menampilkan pesan validasi "Nama wajib diisi" | ✅ Diterima | Field nama: required, string, max:100 |
| 32 | Admin | Mendaftarkan pemilik tanpa mengisi No HP (field wajib) | Sistem menampilkan pesan validasi "No HP wajib diisi" | ✅ Diterima | Field no_hp: required, string, max:20 |
| 33 | Admin | Mencari pemilik berdasarkan nama atau nomor HP | Hasil pencarian menampilkan pemilik yang sesuai keyword | ✅ Diterima | Pencarian mendukung nama dan nomor HP |
| 34 | Admin | Melihat detail pemilik beserta daftar semua hewannya | Halaman detail tampil lengkap dengan daftar hewan dan link WhatsApp | ✅ Diterima | Termasuk whatsapp_link accessor |
| 35 | Admin | Mengedit data pemilik (mengubah alamat) | Data pemilik berhasil diperbarui tanpa mengubah data lainnya | ✅ Diterima | - |
| 36 | Admin | Menghapus pemilik yang sudah tidak aktif | Pemilik di-soft delete, data riwayat medis hewan tetap bisa diakses | ✅ Diterima | Soft delete menjaga integritas data historis |
| 37 | Admin | Mencari pemilik via AJAX (auto-complete) | Dropdown hasil pencarian muncul termasuk pemilik yang sudah dihapus | ✅ Diterima | Menggunakan withTrashed untuk akses data lama |

---

## 5. MODUL PENDAFTARAN HEWAN

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 38 | Admin | Mendaftarkan kucing baru milik pemilik terdaftar | Hewan tersimpan dengan No RM otomatis RMK0001 (K=Kucing) | ✅ Diterima | Auto-generate RM berdasarkan jenis hewan |
| 39 | Admin | Mendaftarkan anjing baru milik pemilik terdaftar | Hewan tersimpan dengan No RM otomatis RMA0001 (A=Anjing) | ✅ Diterima | Prefix berbeda per jenis: K, A, L, H, B, R, X |
| 40 | Admin | Mendaftarkan hewan jenis lain (Kelinci, Hamster, Burung, Reptil) | No RM tergenerate sesuai kode jenis: RML, RMH, RMB, RMR | ✅ Diterima | Semua jenis hewan didukung |
| 41 | Admin | Mendaftarkan hewan tanpa mengisi nama (field wajib) | Sistem menampilkan pesan "Nama hewan wajib diisi" | ✅ Diterima | Field nama_hewan: required, string, max:100 |
| 42 | Admin | Mendaftarkan hewan tanpa memilih jenis (field wajib) | Sistem menampilkan pesan "Jenis hewan wajib diisi" | ✅ Diterima | Field jenis: required, string, max:50 |
| 43 | Admin | Mendaftarkan hewan dengan jenis kelamin tidak valid | Sistem menampilkan pesan validasi (hanya jantan/betina) | ✅ Diterima | Validasi in:jantan,betina |
| 44 | Admin | Melihat umur hewan yang memiliki tanggal lahir | Umur tampil otomatis dalam format "X tahun Y bulan" | ✅ Diterima | Dihitung dari accessor umur di model Pet |
| 45 | Admin | Mengedit data hewan (mengubah ras) | Data hewan berhasil diperbarui | ✅ Diterima | - |
| 46 | Admin | Menghapus hewan yang sudah meninggal | Hewan di-soft delete, riwayat medis tetap bisa diakses | ✅ Diterima | Soft delete menjaga kelengkapan riwayat medis |

---

## 6. MODUL ANTRIAN PASIEN

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 47 | Admin | Membuat antrian baru untuk hewan yang datang ke klinik | Antrian tersimpan dengan nomor urut otomatis, status "Menunggu" | ✅ Diterima | Auto-generate nomor antrian per hari (1, 2, 3, ...) |
| 48 | Admin | Membuat antrian kedua di hari yang sama | Nomor antrian berurutan dari antrian sebelumnya | ✅ Diterima | Nomor antrian increment per tanggal |
| 49 | Admin | Membuat antrian untuk hewan yang sudah ada di antrian hari ini | Sistem menolak dengan pesan "Hewan sudah memiliki antrian pada tanggal tersebut" | ✅ Diterima | Validasi duplikasi per tanggal (non-cancelled/completed) |
| 50 | Admin | Membuat antrian untuk tanggal yang sudah berlalu | Sistem menampilkan pesan "Tanggal tidak boleh sebelum hari ini" | ✅ Diterima | Validasi: after_or_equal:today |
| 51 | Admin | Membuat antrian tanpa memilih hewan | Sistem menampilkan pesan validasi "Hewan wajib dipilih" | ✅ Diterima | Field pet_id: required, exists:pets |
| 52 | Admin | Membuat antrian dengan keluhan melebihi 500 karakter | Sistem menampilkan pesan validasi "Keluhan maksimal 500 karakter" | ✅ Diterima | Field complaint: max:500 |
| 53 | Admin | Melihat daftar antrian hari ini | Semua antrian tampil terurut berdasarkan status dan nomor antrian | ✅ Diterima | Urutan: waiting → in-progress → completed |
| 54 | Admin | Membatalkan antrian yang berstatus "Menunggu" | Status antrian berubah ke "Cancelled" | ✅ Diterima | Hanya bisa cancel status waiting/in-progress |
| 55 | Admin | Mencoba membatalkan antrian yang sudah selesai | Sistem menolak, antrian completed tidak bisa dibatalkan | ✅ Diterima | Validasi status sebelum cancel |
| 56 | Admin | Pencarian hewan via AJAX untuk membuat antrian | Dropdown muncul menampilkan hewan beserta info pemilik | ✅ Diterima | Search by nama hewan, jenis, nama pemilik, no HP |
| 57 | Admin | Melihat auto-refresh antrian tanpa reload halaman | Data antrian ter-update otomatis via AJAX polling | ✅ Diterima | Real-time update untuk monitoring antrian |
| 58 | Dokter | Melihat daftar antrian hari ini terbagi per status | Antrian tampil rapi: menunggu, sedang diperiksa, selesai | ✅ Diterima | Partisi berdasarkan status |
| 59 | Dokter | Melihat detail antrian sebelum memulai pemeriksaan | Info hewan, pemilik, keluhan, dan 5 riwayat medis terakhir tampil | ✅ Diterima | Membantu dokter review riwayat pasien |
| 60 | Dokter | Memulai pemeriksaan dari antrian (klik "Mulai Pemeriksaan") | Status berubah "In-Progress", doctor ID tercatat, redirect ke form pemeriksaan | ✅ Diterima | Transisi status waiting → in-progress |
| 61 | Dokter | Melewati/skip antrian karena pemilik tidak hadir | Status berubah "Skipped", catatan & timestamp tersimpan | ✅ Diterima | Hanya bisa skip status waiting |
| 62 | Dokter | Auto-refresh daftar antrian | Perubahan status terlihat otomatis tanpa reload | ✅ Diterima | AJAX polling untuk sinkronisasi real-time |

---

## 7. MODUL PEMERIKSAAN / REKAM MEDIS

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 63 | Dokter | Mengisi form pemeriksaan lengkap (keluhan, diagnosa, BB, suhu, treatment, resep obat, biaya) | Rekam medis tersimpan, transaksi otomatis dibuat, status antrian "Completed" | ✅ Diterima | Satu form menyimpan ke banyak tabel sekaligus |
| 64 | Dokter | Menyimpan pemeriksaan tanpa memilih hewan (pet_id kosong) | Sistem menampilkan pesan "Pet wajib dipilih" | ✅ Diterima | Field pet_id: required, exists:pets |
| 65 | Dokter | Perhitungan biaya otomatis: Periksa Rp 50.000 + Treatment Rp 100.000 + Obat 10 x Rp 5.000 | Total biaya otomatis terhitung Rp 200.000 | ✅ Diterima | total = biaya_pemeriksaan + biaya_tindakan + biaya_obat |
| 66 | Dokter | Harga obat tersnapshot saat pemeriksaan (tidak berubah jika harga master diupdate) | Harga di rekam medis tetap sesuai harga saat pemeriksaan dilakukan | ✅ Diterima | Snapshot harga di tabel prescription_items |
| 67 | Dokter | Harga treatment tersnapshot saat pemeriksaan | Harga di pivot table tetap sesuai harga saat pemeriksaan dilakukan | ✅ Diterima | Snapshot harga di pivot medical_record_treatments |
| 68 | Dokter | Membuat surat rujukan bersamaan dengan pemeriksaan | Rekam medis tersimpan + rujukan otomatis tergenerate dengan nomor RUJ-YYMMDD-XXXX | ✅ Diterima | Centang checkbox "Buat Rujukan" |
| 69 | Dokter | Menentukan jadwal kontrol berikutnya | Tanggal kontrol tersimpan, muncul di reminder dan widget dashboard | ✅ Diterima | Field next_control_date |
| 70 | Dokter | Mencari hewan via AJAX di form pemeriksaan | Dropdown hasil pencarian muncul dengan info hewan & pemilik | ✅ Diterima | Endpoint api.pets.search |
| 71 | Dokter | Mencari obat via AJAX di form resep | Daftar obat aktif yang memiliki stok tampil | ✅ Diterima | Hanya menampilkan obat aktif & in-stock |
| 72 | Dokter | Menambah treatment baru via AJAX saat pemeriksaan | Treatment tersimpan dan langsung muncul di dropdown pilihan tanpa reload | ✅ Diterima | Dynamic AJAX create treatment |
| 73 | Dokter | Menghapus treatment via AJAX | Treatment di-soft delete, tidak muncul di pilihan baru, riwayat lama tetap utuh | ✅ Diterima | Soft delete menjaga integritas data historis |

---

## 8. MODUL RIWAYAT PASIEN

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 74 | Dokter | Melihat daftar semua riwayat rekam medis | Daftar rekam medis tampil dengan paginasi (15/halaman) | ✅ Diterima | Mendukung paginasi untuk performa |
| 75 | Dokter | Mencari riwayat berdasarkan nama hewan | Menampilkan rekam medis milik hewan yang sesuai keyword | ✅ Diterima | Filter nama_hewan |
| 76 | Dokter | Mencari riwayat berdasarkan nama pemilik | Menampilkan rekam medis semua hewan milik pemilik tersebut | ✅ Diterima | Filter nama_pemilik |
| 77 | Dokter | Filter riwayat berdasarkan tanggal kunjungan | Hanya menampilkan rekam medis pada tanggal yang dipilih | ✅ Diterima | Filter tanggal visit_date |
| 78 | Dokter | Melihat detail rekam medis lengkap | Menampilkan diagnosa, treatment, resep obat, rujukan, info pasien & pemilik | ✅ Diterima | Termasuk data soft-deleted pet/owner |
| 79 | Dokter | Melihat seluruh riwayat medis satu hewan | Semua rekam medis hewan tersebut tampil kronologis dengan paginasi | ✅ Diterima | Route riwayat.show (by pet) |
| 80 | Dokter | Mengedit rekam medis yang perlu dikoreksi | Rekam medis terupdate, biaya dihitung ulang, transaksi tersinkron | ✅ Diterima | Recalculate biaya + sync transaksi |
| 81 | Dokter | Menghapus rekam medis | Rekam medis di-soft delete, tidak muncul di daftar aktif | ✅ Diterima | Soft delete untuk audit trail |
| 82 | Dokter | Sorting riwayat ascending/descending | Data terurut sesuai field dan arah yang dipilih | ✅ Diterima | Sort by: visit_date, nama_hewan, dll |
| 83 | Dokter | Menggunakan mode pencarian pasien | Menampilkan daftar hewan yang memiliki riwayat medis | ✅ Diterima | Mode alternatif pencarian per pasien |
| 84 | Dokter | Export riwayat medis | File riwayat medis berhasil diunduh | ✅ Diterima | - |

---

## 9. MODUL LAPORAN MEDIS

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 85 | Dokter | Melihat laporan medis dengan filter rentang tanggal | Laporan rekam medis tampil beserta statistik (total pasien, jumlah kucing/anjing, diagnosa terbanyak) | ✅ Diterima | Filter start_date & end_date |
| 86 | Dokter | Filter laporan berdasarkan diagnosa tertentu | Hanya menampilkan rekam medis dengan diagnosa yang dipilih | ✅ Diterima | Filter diagnosis dropdown |
| 87 | Dokter | Filter laporan berdasarkan dokter | Menampilkan rekam medis dari dokter yang dipilih saja | ✅ Diterima | Filter doctor_id (termasuk opsi "Semua") |
| 88 | Dokter | Filter laporan berdasarkan nama hewan | Menampilkan rekam medis hewan yang sesuai keyword | ✅ Diterima | Filter pet_name |
| 89 | Dokter | Melihat statistik laporan | Total pasien unik, jumlah kucing, jumlah anjing, diagnosa terbanyak tampil | ✅ Diterima | Kalkulasi agregat dari data terfilter |
| 90 | Dokter | Export laporan ke Excel (.xlsx) | File Excel berhasil diunduh sesuai filter yang diterapkan | ✅ Diterima | Menggunakan Maatwebsite Excel |
| 91 | Dokter | Export laporan ke PDF | File PDF berhasil diunduh dengan statistik dan tabel rekam medis | ✅ Diterima | Menggunakan DomPDF |

---

## 10. MODUL KELOLA STAFF

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 92 | Dokter | Melihat daftar staff admin yang terdaftar | Daftar admin tampil dengan paginasi (10/halaman) | ✅ Diterima | Dokter mengelola admin |
| 93 | Dokter | Menambah admin baru dengan data lengkap (Nama, Email, Username, Password) | Admin baru berhasil ditambahkan dan bisa langsung login | ✅ Diterima | Password di-hash, role admin otomatis |
| 94 | Dokter | Menambah admin dengan email yang sudah terdaftar | Sistem menampilkan pesan "Email sudah digunakan" | ✅ Diterima | Validasi unique:users,email |
| 95 | Dokter | Menambah admin dengan username yang sudah terdaftar | Sistem menampilkan pesan "Username sudah digunakan" | ✅ Diterima | Validasi unique:users,username |
| 96 | Dokter | Menambah admin dengan password kurang dari 8 karakter | Sistem menampilkan pesan "Password minimal 8 karakter" | ✅ Diterima | Validasi min:8 |
| 97 | Dokter | Mengedit data admin (mengubah nama) | Data admin berhasil diperbarui | ✅ Diterima | - |
| 98 | Dokter | Menghapus admin yang sudah resign | Admin berhasil dihapus, tidak bisa login lagi | ✅ Diterima | Hard delete pada model User |

---

## 11. MODUL KASIR / PEMBAYARAN

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 99 | Admin | Melihat daftar transaksi belum bayar | Daftar transaksi unpaid tampil dengan nomor TRX-YYMMDD-XXXX, nama pasien, total biaya | ✅ Diterima | Default filter: status unpaid, paginasi 15 |
| 100 | Admin | Melihat daftar transaksi yang sudah lunas | Daftar transaksi paid tampil lengkap dengan tanggal dan metode bayar | ✅ Diterima | Filter status: paid |
| 101 | Admin | Filter transaksi berdasarkan tanggal | Hanya menampilkan transaksi pada tanggal yang dipilih | ✅ Diterima | - |
| 102 | Admin | Melihat detail rincian biaya transaksi | Rincian tampil: biaya pemeriksaan, tindakan, obat, dan total (menggunakan harga snapshot) | ✅ Diterima | Harga snapshot, bukan harga master terkini |
| 103 | Admin | Memproses pembayaran tunai (jumlah bayar ≥ total) | Pembayaran berhasil, kembalian dihitung otomatis, status "Lunas" | ✅ Diterima | Kembalian = jumlah_bayar - total_biaya |
| 104 | Admin | Memproses pembayaran via Transfer | Pembayaran berhasil dicatat dengan metode Transfer, status "Lunas" | ✅ Diterima | Metode: transfer |
| 105 | Admin | Memproses pembayaran via QRIS | Pembayaran berhasil dicatat dengan metode QRIS, status "Lunas" | ✅ Diterima | Metode: qris |
| 106 | Admin | Memproses pembayaran dengan jumlah kurang dari total | Sistem menolak dengan pesan "Jumlah bayar harus ≥ total biaya" | ✅ Diterima | Validasi min:{total_biaya} |
| 107 | Admin | Memproses pembayaran dengan metode tidak valid | Sistem menampilkan pesan validasi metode bayar | ✅ Diterima | Validasi in:tunai,transfer,qris |
| 108 | Admin | Mencetak struk/receipt setelah pembayaran | Halaman struk tampil dengan detail lengkap siap cetak | ✅ Diterima | Termasuk nomor transaksi, rincian biaya, metode, kembalian |
| 109 | Admin | Menghapus transaksi | Transaksi berhasil dihapus dari sistem | ✅ Diterima | - |

---

## 12. MODUL LAPORAN HARIAN KASIR

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 110 | Admin | Melihat laporan pendapatan harian berdasarkan tanggal | Semua transaksi lunas hari itu tampil dengan ringkasan total pendapatan | ✅ Diterima | Filter by date, default hari ini |
| 111 | Admin | Melihat breakdown metode pembayaran di laporan harian | Rincian tampil: total Tunai, total Transfer, total QRIS | ✅ Diterima | Distribusi per metode pembayaran |
| 112 | Admin | Export laporan harian ke PDF | File PDF berhasil diunduh dengan tabel transaksi dan ringkasan pendapatan | ✅ Diterima | Format profesional untuk laporan harian |

---

## 13. MODUL STOK OBAT

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 113 | Admin | Menambah obat baru dengan data lengkap (Nama, Satuan, Stok, Harga, Stok Min) | Obat tersimpan dengan status "Tersedia" (badge hijau) | ✅ Diterima | Semua field tersimpan dengan benar |
| 114 | Admin | Menambah obat tanpa mengisi nama (field wajib) | Sistem menampilkan pesan "Nama obat wajib diisi" | ✅ Diterima | Validasi required |
| 115 | Admin | Menambah obat tanpa mengisi satuan (field wajib) | Sistem menampilkan pesan "Satuan wajib diisi" | ✅ Diterima | Validasi required |
| 116 | Admin | Menambah obat dengan stok bernilai negatif | Sistem menampilkan pesan "Stok minimal 0" | ✅ Diterima | Validasi min:0 |
| 117 | Admin | Menambah obat dengan harga bernilai negatif | Sistem menampilkan pesan "Harga minimal 0" | ✅ Diterima | Validasi min:0 |
| 118 | Admin | Mengedit data obat (mengubah harga jual) | Harga obat terupdate, harga di rekam medis lama tetap (snapshot) | ✅ Diterima | Snapshot menjaga integritas data historis |
| 119 | Admin | Menambah stok obat yang hampir habis | Stok bertambah sesuai jumlah, status berubah dari "Stok Rendah" ke "Tersedia" | ✅ Diterima | Menggunakan method increaseStock() |
| 120 | Admin | Melihat obat berstatus "Tersedia" (stok > 10) | Label badge hijau "Tersedia" tampil | ✅ Diterima | Threshold > stok_minimum (default 10) |
| 121 | Admin | Melihat obat berstatus "Stok Rendah" (stok 1-10) | Label badge kuning "Stok Rendah" tampil | ✅ Diterima | 0 < stok ≤ stok_minimum |
| 122 | Admin | Melihat obat berstatus "Habis" (stok 0) | Label badge merah "Habis" tampil | ✅ Diterima | stok ≤ 0 |
| 123 | Admin | Filter obat berdasarkan status stok (rendah/habis) | Hanya menampilkan obat sesuai filter status | ✅ Diterima | Scope: lowStock(), inStock() |
| 124 | Admin | Mencari obat berdasarkan nama | Menampilkan obat yang mengandung keyword pencarian | ✅ Diterima | Scope: search(keyword) |
| 125 | Admin | Menonaktifkan obat yang sudah tidak dijual | Obat tidak muncul saat dokter mencari obat untuk resep | ✅ Diterima | Field is_active, scope active() |
| 126 | Admin | Menghapus obat (soft delete) | Obat dihapus, riwayat resep tetap bisa diakses | ✅ Diterima | Soft delete menjaga data prescription |
| 127 | Admin | Melihat statistik keseluruhan stok obat | Menampilkan: total obat, jumlah stok rendah, jumlah stok habis | ✅ Diterima | Ringkasan di header halaman |

---

## 14. MODUL SURAT RUJUKAN

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 128 | Admin | Melihat daftar surat rujukan yang berstatus pending | Daftar rujukan pending tampil dengan nomor RUJ-YYMMDD-XXXX, tujuan, alasan | ✅ Diterima | Filter status: pending |
| 129 | Admin | Melihat daftar surat rujukan yang sudah dicetak | Daftar rujukan printed tampil dengan tanggal cetak dan nama admin | ✅ Diterima | Filter status: printed |
| 130 | Admin | Melihat detail lengkap surat rujukan | Menampilkan: nomor, pasien, dokter, tujuan, alasan, diagnosa, tindakan | ✅ Diterima | Relasi ke medical record & doctor |
| 131 | Admin | Mencetak surat rujukan | Surat rujukan tampil dengan QR code, status berubah "Printed" dengan timestamp | ✅ Diterima | QR code berisi: nomor rujukan, dokter, tanggal |
| 132 | Admin | Nomor rujukan tergenerate otomatis | Nomor format RUJ-YYMMDD-XXXX berurutan per hari | ✅ Diterima | Auto-increment per tanggal |

---

## 15. MODUL REMINDER WHATSAPP

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 133 | Admin | Mengirim reminder kontrol H-1 secara manual | Sistem mencari pasien kontrol besok, WhatsApp terkirim, log tercatat "Terkirim (H-1)" | ✅ Diterima | Integrasi Fonnte WhatsApp API |
| 134 | Admin | Memeriksa log pengiriman reminder | Log menampilkan: penerima, status pengiriman, waktu kirim | ✅ Diterima | Tersimpan di tabel whatsapp_logs |
| 135 | Admin | Reminder otomatis via scheduler (artisan command) | Reminder terkirim otomatis untuk pasien yang kontrol besok | ✅ Diterima | Command: send:reminder-kontrol |

---

## 16. PENGUJIAN ALUR END-TO-END

### Alur 1: Kunjungan Pasien Baru (Registrasi → Antrian → Periksa → Bayar → Struk → Reminder)

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 136 | Admin | Registrasi pemilik baru "Pemilik Uji" HP 08XXXXXXXXXX | Pemilik terdaftar: OWN-XXXX, HP +628XXXXXXXXXX | ✅ Diterima | Langkah 1: Registrasi |
| 137 | Admin | Registrasi kucing "Kitty" milik Ibu Rina | Hewan terdaftar: No RM RMK-XXXX | ✅ Diterima | Langkah 2: Tambah hewan |
| 138 | Admin | Buat antrian untuk "Kitty" dengan keluhan "Muntah-muntah" | Antrian tercipta dengan nomor urut otomatis | ✅ Diterima | Langkah 3: Buat antrian |
| 139 | Dokter | Mulai pemeriksaan "Kitty" dari antrian | Form periksa terbuka dengan data hewan & keluhan terisi | ✅ Diterima | Langkah 4: Mulai periksa |
| 140 | Dokter | Isi hasil: Diagnosa "Hairball", Treatment Rp 100.000, Obat 5xRp 15.000, Periksa Rp 50.000, Kontrol 16 Maret | Rekam medis + transaksi tersimpan, total Rp 225.000, antrian "Completed" | ✅ Diterima | Langkah 5: Simpan pemeriksaan |
| 141 | Admin | Proses pembayaran tunai Rp 250.000 | Lunas, kembalian Rp 25.000 terhitung otomatis | ✅ Diterima | Langkah 6: Bayar |
| 142 | Admin | Cetak struk pembayaran | Struk tampil dengan detail lengkap siap cetak | ✅ Diterima | Langkah 7: Cetak struk |
| 143 | Admin | Kirim reminder H-1 kontrol (15 Maret 2026) | WhatsApp terkirim ke +628XXXXXXXXXX, log tercatat | ✅ Diterima | Langkah 8: Reminder |

### Alur 2: Pemeriksaan dengan Rujukan

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 144 | Admin | Buat antrian untuk anjing "Bobby" (pasien lama) | Antrian tercipta dengan nomor urut otomatis | ✅ Diterima | Pasien sudah terdaftar sebelumnya |
| 145 | Dokter | Periksa Bobby, diagnosa "Tumor Kulit", buat rujukan ke RS Hewan Spesialis | Rekam medis + rujukan RUJ-YYMMDD-XXXX tersimpan, status rujukan Pending | ✅ Diterima | Checkbox rujukan aktif |
| 146 | Admin | Proses pembayaran via QRIS | Pembayaran berhasil dengan metode QRIS, status "Lunas" | ✅ Diterima | - |
| 147 | Admin | Cetak surat rujukan Bobby | Surat rujukan tampil dengan QR code, status "Printed" | ✅ Diterima | QR code + timestamp cetak |

### Alur 3: Multiple Pasien dalam Sehari + Laporan

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 148 | Admin | Buat 3 antrian untuk 3 hewan berbeda | 3 antrian tercipta dengan nomor 1, 2, 3 | ✅ Diterima | Nomor antrian berurutan |
| 149 | Dokter | Periksa pasien pertama (antrian no 1) | Antrian 1 "In-Progress", antrian lain tetap "Waiting" | ✅ Diterima | Tidak mempengaruhi antrian lain |
| 150 | Dokter | Selesai pasien 1, lanjut periksa pasien 2 | Antrian 1 "Completed", antrian 2 "In-Progress" | ✅ Diterima | Transisi status berjalan lancar |
| 151 | Dokter | Skip pasien ketiga (pemilik tidak hadir) | Antrian 3 "Skipped" dengan catatan & timestamp | ✅ Diterima | - |
| 152 | Admin | Proses semua pembayaran hari ini | Semua transaksi berstatus "Lunas" | ✅ Diterima | - |
| 153 | Admin | Lihat laporan harian | Total pendapatan terhitung, breakdown per metode tampil | ✅ Diterima | Ringkasan pendapatan harian |
| 154 | Admin | Export laporan harian ke PDF | PDF berhasil diunduh dengan detail semua transaksi | ✅ Diterima | Format print-ready |

### Alur 4: Manajemen Stok Obat

| No | Peran | Skenario Uji | Kriteria Penerimaan | Status | Catatan |
|----|-------|-------------|---------------------|--------|---------|
| 155 | Admin | Input obat baru "Metronidazole 500mg" stok 200, harga Rp 8.000 | Obat terdaftar, status "Tersedia" (badge hijau) | ✅ Diterima | Obat baru dari supplier |
| 156 | Dokter | Resepkan Metronidazole 10 tablet saat pemeriksaan | Resep tersimpan dengan harga snapshot Rp 8.000/tablet | ✅ Diterima | Harga tersnapshot |
| 157 | Admin | Cek dashboard saat stok tinggal 5 (di bawah minimum) | Obat muncul di widget "Stok Rendah" dengan badge kuning | ✅ Diterima | Alert otomatis stok rendah |
| 158 | Admin | Tambah stok 100 unit | Stok dari 5 menjadi 105, status kembali "Tersedia" | ✅ Diterima | Badge kembali hijau |

---

## RINGKASAN HASIL UAT

### Ringkasan Per Modul

| No | Modul | Jumlah Skenario | Diterima | Ditolak |
|----|-------|:---------------:|:--------:|:-------:|
| 1 | Autentikasi (Login/Logout) | 12 | 12 | 0 |
| 2 | Lupa Password & Ganti Password | 7 | 7 | 0 |
| 3 | Dashboard (Admin & Dokter) | 10 | 10 | 0 |
| 4 | Pendaftaran Pemilik Hewan | 8 | 8 | 0 |
| 5 | Pendaftaran Hewan | 9 | 9 | 0 |
| 6 | Antrian Pasien | 16 | 16 | 0 |
| 7 | Pemeriksaan / Rekam Medis | 11 | 11 | 0 |
| 8 | Riwayat Pasien | 11 | 11 | 0 |
| 9 | Laporan Medis | 7 | 7 | 0 |
| 10 | Kelola Staff | 7 | 7 | 0 |
| 11 | Kasir / Pembayaran | 11 | 11 | 0 |
| 12 | Laporan Harian Kasir | 3 | 3 | 0 |
| 13 | Stok Obat | 15 | 15 | 0 |
| 14 | Surat Rujukan | 5 | 5 | 0 |
| 15 | Reminder WhatsApp | 3 | 3 | 0 |
| 16 | Alur End-to-End | 23 | 23 | 0 |
| **TOTAL** | **16 Modul** | **158** | **158** | **0** |

### Ringkasan Per Role

| Peran | Jumlah Skenario | Diterima | Ditolak |
|-------|:---------------:|:--------:|:-------:|
| Admin | 95 | 95 | 0 |
| Dokter | 63 | 63 | 0 |
| **TOTAL** | **158** | **158** | **0** |

### Persentase Keberhasilan: **100%**

---

### Tanda Tangan Persetujuan

| Penguji | Role | Tanda Tangan | Tanggal |
|---------|------|:------------:|:-------:|
| drh. __________________ | Dokter | __________________ | __ / __ / 2026 |
| __________________ | Admin | __________________ | __ / __ / 2026 |

---

**Kesimpulan:**  
Berdasarkan pengujian User Acceptance Testing (UAT) yang telah dilakukan dengan total **158 skenario pengujian** mencakup **16 modul** yang diuji dari perspektif **Admin** dan **Dokter**, termasuk **4 alur kerja end-to-end**, seluruh fitur pada sistem Pet Area **dinyatakan DITERIMA** dan berfungsi sesuai dengan kebutuhan pengguna akhir. Sistem siap digunakan dalam operasional klinik hewan sehari-hari.

**Catatan:**
- Semua pengujian dilakukan pada lingkungan lokal (Laragon, MySQL 8.0.30)
- Pengujian meliputi validasi input, perhitungan otomatis, alur bisnis, dan pengalaman pengguna
- Fitur snapshot harga memastikan integritas data finansial historis
- Soft delete pada data master menjaga kelengkapan riwayat medis
- Sistem auto-numbering (OWN, RM, TRX, RUJ, Antrian) berfungsi dengan baik
- Format nomor HP otomatis mendukung integrasi WhatsApp
