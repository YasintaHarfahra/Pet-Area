# Panduan Testing Alur Baru Pet-Area

## Prasyarat
- Database sudah di-migrate
- Seeder sudah dijalankan (ada user admin & dokter)
- Aplikasi dapat diakses di localhost

## Test Flow Step-by-Step

### ✅ TEST 1: Daftar Pasien Baru
**Akses**: Admin Dashboard → Admin Pasien → Tambah Pasien

**Input**:
```
Informasi Hewan:
- Nama Hewan: "Fluffy"
- Jenis Hewan: "Kucing"
- Ras: "Persia"
- Umur: 3 tahun, 5 bulan
- Jenis Kelamin: "Betina"
- Berat: 4.5 kg
- Riwayat Medis: "Alergi makanan tertentu"

Informasi Pemilik:
- Nama Pemilik: "Budi Santoso"
- No. HP: "081234567890"
- Alamat: "Jl. Merdeka No. 123, Jakarta"
- Email: "budi@email.com"
```

**Expected Output**:
- ✅ Pasien tersimpan
- ✅ Nomor RM otomatis generate (format: RM-20251117-00001)
- ✅ Success message: "Data pasien berhasil ditambahkan! Nomor RM: RM-20251117-00001"
- ✅ Redirect ke halaman daftar pasien

---

### ✅ TEST 2: Lihat Data Pasien
**Akses**: Admin Dashboard → Admin Pasien

**Expected Output**:
- ✅ Tabel menampilkan: No. RM, Nama Hewan, Jenis, Ras, Pemilik, Kontak
- ✅ Pasien "Fluffy" muncul di tabel
- ✅ 3 tombol aksi: Lihat Detail, Edit, Hapus

---

### ✅ TEST 3: Lihat Detail Pasien + Riwayat Pemeriksaan
**Akses**: Admin Pasien → Klik tombol mata pada "Fluffy"

**Expected Output**:
- ✅ **Kolom Kiri (Sticky)**: 
  - Data hewan (Nomor RM, nama, jenis, ras, umur, jenis kelamin, berat)
  - Data pemilik (nama, HP, alamat, email)
  - Riwayat medis umum
  - Tombol Edit & Hapus
- ✅ **Kolom Kanan**:
  - Header "Riwayat Pemeriksaan" + total (0 untuk pasien baru)
  - Message: "Belum Ada Riwayat Pemeriksaan"

---

### ✅ TEST 4: Edit & Hapus Pasien
**Akses**: Admin Pasien → Klik tombol edit/hapus

**Edit**:
- ✅ Form pre-filled dengan data pasien
- ✅ Update berat menjadi 5 kg → Simpan
- ✅ Success message: "Data pasien berhasil diperbarui!"

**Hapus**:
- ✅ Confirmation dialog: "Apakah Anda yakin ingin menghapus pasien ini?"
- ✅ Klik OK → Pasien dihapus
- ✅ Success message: "Data pasien berhasil dihapus!"

---

### ✅ TEST 5: Tambah Antrian dengan Search Pasien
**Akses**: Admin Dashboard → Tambah Antrian (atau Admin → Antrian → Tambah)

**Input**:
```
- Search Pasien: Ketik "Fluffy" atau "RM-20251117" dalam input search
- Pilih dari dropdown hasil search
- Tanggal Kunjungan: Hari ini
- Keluhan: "Kucing terlihat malas dan tidak mau makan"
```

**Expected Output**:
- ✅ Search autocomplete bekerja
- ✅ Form show dropdown dengan nama hewan, nomor RM, spesies, pemilik
- ✅ Klik pasien → field terisi otomatis
- ✅ Simpan → Success message
- ✅ Redirect ke halaman Daftar Antrian

---

### ✅ TEST 6: Lihat Daftar Antrian
**Akses**: Admin Dashboard → Antrian Pasien (atau Admin → Antrian)

**Expected Output**:
- ✅ **Status Cards**: Total, Menunggu, Sedang Dilayani, Selesai (counts)
- ✅ **Tabel Antrian** dengan kolom: No. Antrian, Nama Hewan, Keluhan, Status, Aksi
- ✅ Status "Menunggu" untuk antrian yang baru
- ✅ **Tombol Aksi** (untuk status waiting):
  - 🟢 Tombol Mulai Pemeriksaan (lightning icon)
  - 👁️ Tombol Lihat Detail (eye icon)
  - ❌ Tombol Batalkan (trash icon)

---

### ✅ TEST 7: Lihat Detail Antrian
**Akses**: Daftar Antrian → Klik tombol mata

**Expected Output**:
- ✅ Informasi Antrian: No. Antrian, Tanggal, Status (Menunggu)
- ✅ Informasi Pasien: Nama, No. RM, Keluhan
- ✅ Tombol: Kembali ke Daftar Antrian, Batalkan Antrian

---

### ✅ TEST 8: Batalkan Antrian
**Akses**: Detail Antrian → Klik "Batalkan Antrian"

**Expected Output**:
- ✅ Confirmation dialog
- ✅ Status berubah menjadi "Dibatalkan" (merah)
- ✅ Success message: "Antrian dibatalkan."
- ✅ Antrian tidak muncul di dashboard dokter

---

### ✅ TEST 9: Mulai Pemeriksaan (Dashboard Pemeriksaan)
**Akses**: Daftar Antrian → Klik tombol Mulai Pemeriksaan (untuk status waiting)

**Expected Output**:
- ✅ **Header**: Nomor antrian, Tanggal kunjungan, Status (Sedang Dilayani - hijau), Keluhan
- ✅ **Kolom Kiri (1/3) - Identitas Pasien**:
  - Data Hewan: Nomor RM, Nama, Jenis, Ras, Umur, Jenis Kelamin, Berat
  - Data Pemilik: Nama, HP, Alamat, Email
  - Riwayat Medis Umum
- ✅ **Kolom Kanan (2/3) - Riwayat Pemeriksaan**:
  - Tombol hijau "Tambah Pemeriksaan"
  - Message: "Belum Ada Riwayat Pemeriksaan" (untuk pasien baru)
- ✅ **Footer**: Tombol "Kembali", Tombol "Batalkan Antrian"
- ✅ **Queue Status**: Otomatis berubah menjadi "in-progress"

---

### ✅ TEST 10: Tambah Pemeriksaan (Dari Dashboard)
**Akses**: Dashboard Pemeriksaan → Klik "Tambah Pemeriksaan"

**Input** (di form dokter):
```
- Tanggal Pemeriksaan: Hari ini
- Gejala: "Malas, tidak makan, diare"
- Diagnosis: "Gastroenteritis akut"
- Tindakan: "Injeksi antibiotik, IV fluid"
- Resep Obat: "Amoxicillin 500mg 2x sehari selama 7 hari"
- Catatan: "Pantau kondisi, perlu follow-up 3 hari"
```

**Expected Output**:
- ✅ Form dokter terbuka dengan riwayat pemeriksaan sebelumnya (ada info patient)
- ✅ Semua field terisi
- ✅ Simpan → Success message: "Rekam medis berhasil disimpan!"
- ✅ Redirect ke halaman Daftar Antrian (dokter)
- ✅ **Queue Status**: Otomatis berubah menjadi "completed"
- ✅ **Medical Record**: Tersimpan dan terhubung dengan queue

---

### ✅ TEST 11: Lihat Riwayat Pemeriksaan Pasien
**Akses**: Admin Pasien → Klik "Lihat Detail" pasien

**Expected Output**:
- ✅ **Kolom Kanan - Riwayat Pemeriksaan**:
  - 1 record pemeriksaan muncul
  - Tanggal: Hari ini
  - Dokter: Nama dokter yang membuat
  - Gejala, Diagnosis, Tindakan, Resep, Catatan semuanya tertampil dengan styling warna berbeda

---

### ✅ TEST 12: Lihat Data Pasien dari Dashboard Dokter
**Akses**: Dokter Dashboard → Data Pasien (atau Dokter → Pasien)

**Expected Output**:
- ✅ Tabel pasien dengan info: Nomor RM, Nama, Jenis, Ras, Pemilik, Kontak
- ✅ Tombol aksi: Lihat Detail, Tambah Rekam Medis Baru
- ✅ Klik "Lihat Detail" → Sama dengan admin (identitas + riwayat)

---

### ✅ TEST 13: Antrian Harian Status
**Akses**: Admin Antrian / Dokter Antrian

**Expected Output**:
- ✅ Antrian baru: Status "Menunggu" (kuning)
- ✅ Antrian dibatalkan: Status "Dibatalkan" (merah) - tidak muncul di dokter
- ✅ Antrian setelah mulai pemeriksaan: Status "Sedang Dilayani" (hijau)
- ✅ Antrian setelah tambah pemeriksaan: Status "Selesai" (abu-abu)

---

### ✅ TEST 14: Auto-Refresh Antrian
**Akses**: Admin Antrian

**Expected Output**:
- ✅ Buka halaman di 2 tab/browser
- ✅ Tambah antrian baru di tab 1
- ✅ Tab 2 otomatis update setiap 30 detik (lihat nomor antrian baru)
- ✅ Tidak perlu refresh manual

---

### ✅ TEST 15: Multiple Examination History
**Akses**: Tambah pasien ke antrian lagi, lalu tambah pemeriksaan

**Expected Output**:
- ✅ Lihat detail pasien menampilkan riwayat pemeriksaan > 1
- ✅ Riwayat diurutkan dari terbaru ke lama
- ✅ Setiap pemeriksaan terlihat dengan info lengkap

---

## Edge Cases Testing

### Edge Case 1: Batalkan Antrian yang Sudah In-Progress
**Expected**: Tombol batalkan tidak muncul (hanya untuk status waiting)

### Edge Case 2: Delete Pasien yang Memiliki Riwayat Pemeriksaan
**Expected**: Data akan di-cascade delete (sesuai foreign key)

### Edge Case 3: Search Pasien dengan Query Kosong
**Expected**: Show all patients

### Edge Case 4: Multiple Antrian Hari Sama
**Expected**: Nomor antrian ter-increment (1, 2, 3, dst)
- Antrian dibatalkan tidak dihitung ke nomor berikutnya

---

## Test Results Summary

| Test | Status | Notes |
|------|--------|-------|
| Daftar Pasien | ✅ |  |
| Lihat Pasien | ✅ |  |
| Detail Pasien | ✅ |  |
| Edit Pasien | ✅ |  |
| Hapus Pasien | ✅ |  |
| Tambah Antrian | ✅ |  |
| Daftar Antrian | ✅ |  |
| Detail Antrian | ✅ |  |
| Batalkan Antrian | ✅ |  |
| Mulai Pemeriksaan | ✅ |  |
| Tambah Pemeriksaan | ✅ |  |
| Lihat Riwayat | ✅ |  |
| Dokter - Data Pasien | ✅ |  |
| Status Updates | ✅ |  |
| Auto-Refresh | ✅ |  |

---

## Notes
- Jika ada error, cek di `storage/logs/laravel.log`
- Pastikan browser JS enabled (untuk search autocomplete)
- Clear browser cache jika ada styling issues
- Nomor RM format: `RM-YYYYMMDD-XXXXX` (auto-generated)
