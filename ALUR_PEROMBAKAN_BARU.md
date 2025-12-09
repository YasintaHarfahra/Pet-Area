# Ringkasan Perombakan Alur Pet-Area

## Perubahan Besar
Sistem Pet-Area telah diperombak sesuai dengan rancangan alur baru untuk meningkatkan pengalaman admin dan dokter.

## Alur Baru yang Diimplementasikan

### 1. **ADMIN - Daftar Pasien Baru** ✅
- **Route**: `GET/POST /admin/pasien` → `admin.patients.create` & `admin.patients.store`
- **Form Input**:
  - Informasi Hewan: nama, jenis, ras, umur (dropdown tahun & bulan), jenis kelamin, berat
  - Informasi Pemilik: nama pemilik, no. HP, alamat, email
- **Output**: Pasien disimpan dengan nomor RM otomatis (format: RM-YYYYMMDD-XXXXX)
- **View**: `admin/patients/create.blade.php`

### 2. **ADMIN - Lihat/Edit/Hapus Data Pasien** ✅
- **Route**: `GET /admin/pasien` → `admin.patients.index`
- **Features**:
  - Tabel lengkap dengan nomor RM, nama hewan, jenis, ras, pemilik, kontak
  - Tombol aksi: Lihat detail, Edit, Hapus
  - Search functionality
- **Detail Pasien** (`admin/patients/show.blade.php`):
  - Kolom kiri: Data hewan + data pemilik (sticky position)
  - Kolom kanan: Riwayat pemeriksaan lengkap (tanggal, dokter, gejala, diagnosis, tindakan, resep, catatan)
  - Tombol: Edit, Hapus
- **View**: `admin/patients/index.blade.php` & `admin/patients/show.blade.php`

### 3. **ADMIN - Tambah Antrian** ✅
- **Route**: `GET/POST /admin/antrian/create` → `admin.queues.create` & `admin.queues.store`
- **Form Input**:
  - Cari Pasien (with autocomplete search nama atau no RM)
  - Tanggal Kunjungan (datepicker)
  - Keluhan Umum (textarea)
- **Nomor Antrian**: Auto-generated per tanggal
- **View**: `admin/queues/create.blade.php`
- **Search API**: `admin.queues.search-patients` (AJAX)

### 4. **ADMIN - Daftar Antrian & Detail Antrian** ✅
- **Route**: `GET /admin/antrian` → `admin.queues.index`
- **Features**:
  - Status Cards: Total, Menunggu, Sedang Dilayani, Selesai
  - Tabel antrian dengan filter status
  - Tombol aksi per baris:
    - **Mulai Pemeriksaan** (hanya untuk status waiting) → membuka dashboard pemeriksaan
    - Lihat Detail
    - Batalkan (hanya untuk status waiting)
  - Auto-refresh setiap 30 detik
- **Detail Antrian** (`admin/queues/show.blade.php`):
  - Informasi antrian (nomor, tgl, status)
  - Informasi pasien (nama, nomor RM, keluhan)
  - Tombol: Kembali, Batalkan
- **View**: `admin/queues/index.blade.php` & `admin/queues/show.blade.php`

### 5. **ADMIN - Dashboard Mulai Pemeriksaan** ✅
- **Route**: `GET /admin/antrian/{queue}/mulai-pemeriksaan` → `admin.queues.start-examination`
- **Status Update**: Queue status otomatis berubah menjadi "in-progress"
- **Layout**:
  - **Kolom Atas**: Info antrian (nomor, tgl, status, keluhan)
  - **Kolom Kiri (1/3)**: Identitas pasien lengkap (data hewan + data pemilik + riwayat medis)
  - **Kolom Kanan (2/3)**: 
    - Riwayat pemeriksaan sebelumnya (per tanggal: tgl, dokter, gejala, diagnosis, tindakan, resep, catatan)
    - Tombol: "Tambah Pemeriksaan" (green button)
- **Tombol Footer**: Kembali, Batalkan Antrian
- **View**: `admin/queues/start-examination.blade.php`

### 6. **BATALKAN ANTRIAN** ✅
- **Fitur**: Status antrian berubah menjadi "cancelled"
- **Dampak**: Antrian tidak masuk ke dashboard dokter
- **Akses**: Dari halaman daftar antrian atau detail antrian

### 7. **TAMBAH PEMERIKSAAN (dari Dashboard Mulai Pemeriksaan)** ✅
- **Route**: `GET/POST /dokter/rekam-medis` dengan parameter `patient_id` & `queue_id`
- **Form Input** (di controller, sudah ada):
  - Tanggal pemeriksaan
  - Gejala/Symptoms
  - Diagnosis
  - Tindakan/Treatment
  - Resep obat/Prescription
  - Catatan tambahan
- **Auto-Logic**:
  - Queue status otomatis berubah menjadi "in-progress" saat form dibuka
  - Queue status otomatis berubah menjadi "completed" saat form disimpan
  - Medical Record di-link ke queue (`medical_record_id`)
- **View**: `dokter/medical_records/create.blade.php` (sudah ada, menampilkan riwayat pemeriksaan)

### 8. **DOKTER - Lihat Data Pasien & Riwayat Pemeriksaan** ✅
- **Route**: `GET /dokter/pasien` → `dokter.patients.index`
- **Features**:
  - Tabel pasien (nama RM, spesies, ras, pemilik, kontak)
  - Tombol aksi: Lihat detail, Buat rekam medis baru
- **Detail Pasien** (same as admin):
  - Informasi lengkap pasien + riwayat pemeriksaan
- **View**: `dokter/patients/index.blade.php` & shared `admin/patients/show.blade.php`

## Model & Relationship Updates

### Patient Model
```php
protected $fillable = [
    'medical_record_number',  // Nomor RM otomatis
    'name', 'species', 'breed', 'date_of_birth',
    'gender', 'weight', 'medical_history',
    'owner_name', 'owner_phone', 'owner_address', 'owner_email',
];

public function medicalRecords() {}  // hasMany
public function visits() {}          // hasMany
public function transactions() {}    // hasMany
public function queues() {}          // hasMany (NEW)

public static function generateMedicalRecordNumber() // Helper function
```

### Queue Model
```php
protected $fillable = [
    'patient_id', 'queue_number', 'status', 'queue_date', 'complaint', 'medical_record_id'
];

// Status: waiting, in-progress, completed, cancelled
public function scopeNotCancelled() {}   // NEW - untuk exclude antrian dibatalkan
public function scopeCompleted() {}      // NEW
public function scopeCancelled() {}      // NEW
public static function generateQueueNumber($date) {} // Updated - exclude cancelled
```

### MedicalRecord Model
```php
public function scopeByPatient($query, $patientId) {} // NEW - riwayat per pasien
```

## Routes Baru
```php
Route::get('/admin/antrian/{queue}/mulai-pemeriksaan', 
    [QueueController::class, 'startExamination'])->name('admin.queues.start-examination');
```

## Queue Controller Methods
```php
public function startExamination(Queue $queue)  // NEW - mulai pemeriksaan
public function adminIndex()                     // UPDATED - exclude cancelled
public function doctorIndex()                    // UPDATED - exclude cancelled
public function getQueueData()                   // UPDATED - exclude cancelled
```

## Views Baru
1. `admin/queues/start-examination.blade.php` - Dashboard mulai pemeriksaan

## Views Diperbarui
1. `admin/queues/index.blade.php` - Tambah tombol "Mulai Pemeriksaan" + auto-refresh
2. `admin/patients/show.blade.php` - Complete redesign dengan layout kolom + riwayat pemeriksaan lengkap
3. `dokter/patients/index.blade.php` - Sudah optimal

## Database Schema
- **patients table**: Sudah ada medical_record_number (unique, nullable)
- **queues table**: Sudah ada status dengan enum (waiting, in-progress, completed, cancelled)
- **medical_records table**: Sudah ada queue_id (foreign key ke queues)

## Alur Lengkap User Journey

### Admin Journey
```
1. Dashboard Admin
   ↓
2. Menu "Daftar Pasien" → Tambah Pasien Baru
   (Simpan dengan nomor RM otomatis)
   ↓
3. Menu "Data Pasien" → Lihat tabel pasien
   (Klik "Lihat Detail" untuk melihat riwayat pemeriksaan)
   ↓
4. Menu "Tambah Antrian" → Form dengan search pasien
   (Pilih pasien, tgl kunjungan, keluhan) → Simpan
   ↓
5. Menu "Daftar Antrian" → Tabel antrian hari ini
   (Status: waiting, in-progress, completed, cancelled)
   ↓
6. Klik "Mulai Pemeriksaan" (status waiting)
   → Buka dashboard pemeriksaan dengan:
   - Identitas pasien lengkap (kolom kiri)
   - Riwayat pemeriksaan (kolom kanan)
   - Tombol "Tambah Pemeriksaan"
   ↓
7. Klik "Tambah Pemeriksaan" → Redirect ke form dokter
   (atau dokter bisa langsung buat dari antrian)
   ↓
8. Antrian status berubah: waiting → in-progress → completed
```

### Dokter Journey
```
1. Dashboard Dokter
   ↓
2. Menu "Data Pasien" → Lihat tabel pasien + riwayat
   (Klik "Lihat Detail" untuk melihat riwayat lengkap)
   ↓
3. Menu "Daftar Antrian" → Lihat antrian hari ini (bukan yang cancelled)
   ↓
4. Buat Rekam Medis → Form dengan patient selection
   → Input gejala, diagnosis, tindakan, resep
   → Simpan
   ↓
5. Queue status otomatis: waiting → in-progress → completed
```

## Status Antrian
- **waiting**: Pasien belum diperiksa, bisa "Mulai Pemeriksaan" atau "Batalkan"
- **in-progress**: Sedang dalam proses pemeriksaan
- **completed**: Sudah selesai diperiksa
- **cancelled**: Dibatalkan, tidak muncul di dashboard

## Testing Checklist
- [x] Daftar pasien baru dengan nomor RM otomatis
- [x] Edit & hapus data pasien
- [x] Lihat detail pasien + riwayat pemeriksaan
- [x] Tambah antrian dengan search pasien
- [x] Lihat daftar antrian
- [x] Mulai pemeriksaan (status berubah ke in-progress)
- [x] Lihat dashboard pemeriksaan dengan identitas + riwayat
- [x] Batalkan antrian (status jadi cancelled)
- [x] Tambah rekam medis (status berubah ke completed)
- [x] Lihat data pasien dari dashboard dokter
- [x] Auto-refresh antrian setiap 30 detik

## Catatan Penting
1. **Nomor RM**: Format `RM-YYYYMMDD-XXXXX` (otomatis generate saat pasien dibuat)
2. **Search Pasien**: Bisa by nama hewan atau nomor RM
3. **Antrian Dibatalkan**: Tidak masuk ke dashboard dokter
4. **Status Auto-Update**: Queue status otomatis update saat form dibuka/disimpan
5. **Riwayat Pemeriksaan**: Diurutkan dari pemeriksaan terbaru ke lama

## Files Modified
- `app/Models/Patient.php` - Add relationships & helper
- `app/Models/Queue.php` - Add scopes & relationships
- `app/Models/MedicalRecord.php` - Add scope
- `app/Http/Controllers/QueueController.php` - Add startExamination method
- `routes/web.php` - Add new route
- `resources/views/admin/queues/index.blade.php` - Add start-examination button
- `resources/views/admin/queues/start-examination.blade.php` - NEW
- `resources/views/admin/patients/show.blade.php` - Complete redesign

## Next Steps (Optional)
- [ ] Add notification system untuk dokter saat ada antrian baru
- [ ] Add SMS/WhatsApp notification ke pemilik hewan
- [ ] Add export data pasien ke PDF
- [ ] Add riwayat transaksi per pasien
- [ ] Add appointment scheduling (booking system)
- [ ] Add multi-doctor support dengan assignment
- [ ] Add analytics dashboard
