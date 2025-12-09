# Quick Reference - Alur Baru Pet-Area

## Routes Penting

### Admin Routes
```
GET    /admin/pasien                           admin.patients.index      - List pasien
GET    /admin/pasien/create                    admin.patients.create     - Form daftar pasien
POST   /admin/pasien                           admin.patients.store      - Simpan pasien
GET    /admin/pasien/{patient}                 patients.show             - Detail pasien
GET    /admin/pasien/{patient}/edit            admin.patients.edit       - Form edit
PUT    /admin/pasien/{patient}                 admin.patients.update     - Update pasien
DELETE /admin/pasien/{patient}                 admin.patients.destroy    - Hapus pasien

GET    /admin/antrian                          admin.queues.index        - Daftar antrian
GET    /admin/antrian/create                   admin.queues.create       - Form tambah antrian
POST   /admin/antrian                          admin.queues.store        - Simpan antrian
GET    /admin/antrian/{queue}                  admin.queues.show         - Detail antrian
GET    /admin/antrian/{queue}/mulai-pemeriksaan admin.queues.start-examination - Dashboard pemeriksaan
DELETE /admin/antrian/{queue}                  admin.queues.cancel       - Batalkan antrian
GET    /admin/antrian/search-patients          admin.queues.search-patients - Search AJAX
GET    /admin/antrian/data                     admin.queues.data         - Get data AJAX
```

### Dokter Routes
```
GET    /dokter/pasien                          dokter.patients.index     - List pasien
GET    /dokter/antrian                         dokter.queues.index       - Daftar antrian
GET    /dokter/rekam-medis/create              dokter.medical_records.create - Form rekam medis
POST   /dokter/rekam-medis                     dokter.medical_records.store - Simpan rekam medis
```

---

## Key Model Methods

### Patient
```php
Patient::generateMedicalRecordNumber()  // Generate nomor RM
$patient->medicalRecords()              // hasMany MedicalRecord
$patient->queues()                      // hasMany Queue
```

### Queue
```php
Queue::generateQueueNumber($date)       // Generate nomor antrian per tanggal
Queue::whereDate('queue_date', today()) // Antrian hari ini
Queue::notCancelled()                   // Exclude cancelled queues
Queue::waiting()                        // Status waiting
Queue::inProgress()                     // Status in-progress
Queue::completed()                      // Status completed
Queue::cancelled()                      // Status cancelled
```

### MedicalRecord
```php
MedicalRecord::byPatient($patientId)    // Riwayat per pasien (desc by visit_date)
$medicalRecord->patient                 // belongsTo Patient
$medicalRecord->doctor                  // belongsTo User
$medicalRecord->queue                   // belongsTo Queue
```

---

## Controller Methods

### PatientController
```php
// Admin only
public function indexAdmin()            - Get all patients
public function create()                - Show create form
public function store()                 - Save patient (auto-generate RM)
public function edit()                  - Show edit form
public function update()                - Update patient
public function destroy()               - Delete patient

// Admin & Dokter
public function show()                  - Detail + riwayat pemeriksaan
public function indexDoctor()           - Get all patients (dokter view)
```

### QueueController
```php
public function adminIndex()            - Get queues for admin (not cancelled)
public function doctorIndex()           - Get queues for dokter (not cancelled)
public function create()                - Show create form
public function store()                 - Save queue (auto-generate number)
public function show()                  - Detail antrian
public function startExamination()      - Dashboard pemeriksaan (NEW)
public function cancel()                - Cancel queue (status = cancelled)
public function searchPatients()        - AJAX search by name/RM
public function getQueueData()          - AJAX get all queues (not cancelled)
public function updateStatus()          - AJAX update queue status
```

### MedicalRecordController
```php
public function create()                - Show form with patient select
                                         - Auto-set queue status to in-progress
public function store()                 - Save record
                                         - Auto-set queue status to completed
                                         - Link queue_id to medical record
```

---

## Status Flow

```
           ┌─────────────────────┐
           │   Pasien Terdaftar  │
           └──────────┬──────────┘
                      │
                      ▼
           ┌─────────────────────┐
       ┌──▶│  Antrian Status:    │◀───┐
       │   │  WAITING (Kuning)   │    │
       │   └──────────┬──────────┘    │
       │              │               │
       │     (Batalkan Antrian)       │
       │              │               │
       │              ▼               │
       │   ┌─────────────────────┐    │
       │   │  CANCELLED (Merah)  │    │
       │   │  [Tidak ke Dokter]  │    │
       │   └─────────────────────┘    │
       │                              │
       │   (Mulai Pemeriksaan)        │
       │              │               │
       └──┬───────────▼──────────┬────┘
          │   ┌─────────────────────┐
          │   │  COMPLETED (Hijau)  │
          │   │  (Sudah In-Progress)│
          │   └──────────┬──────────┘
          │              │
          │   (Tambah Pemeriksaan)
          │              │
          │              ▼
          │   ┌─────────────────────┐
          │   │  COMPLETED (Abu-abu)│
          │   │  [Medical Record OK]│
          │   └─────────────────────┘
          │
          └─ Back to WAITING (untuk antrian baru)
```

---

## View Structure

### admin/queues/index.blade.php
- Status cards (total, waiting, in-progress, completed)
- Table dengan sort by queue number
- Auto-refresh setiap 30s via AJAX

### admin/queues/start-examination.blade.php (NEW)
- Header: Info antrian
- Kolom Kiri (1/3): Identitas pasien (sticky)
  - Data hewan (nomor RM, nama, jenis, ras, umur, jenis kelamin, berat)
  - Data pemilik (nama, HP, alamat, email)
  - Riwayat medis umum
  - Tombol: Edit, Hapus
- Kolom Kanan (2/3): Riwayat pemeriksaan
  - Header dengan tombol "Tambah Pemeriksaan"
  - List riwayat (per tanggal)
    - Tanggal pemeriksaan & dokter
    - Gejala (yellow bg)
    - Diagnosis (blue bg)
    - Tindakan (green bg)
    - Resep (purple bg)
    - Catatan (gray bg)
- Footer: Tombol Kembali, Batalkan

### admin/patients/show.blade.php (UPDATED)
- Same structure as start-examination
- Additional: Edit & Hapus buttons

---

## Database Relations

```
patients (1) ──────┐
                   ├──→ (∞) queues
                   │
                   └──→ (∞) medical_records

queues (1) ────────┐
                   ├──→ (∞) medical_records
                   │
                   └──→ (1) patients

medical_records ──→ (1) patients
                 ──→ (1) users (doctor)
                 ──→ (1) queues

users (doctors) ───→ (∞) medical_records
```

---

## Key Fields

### patients table
```
id                       INTEGER PRIMARY KEY
medical_record_number    STRING UNIQUE (RM-YYYYMMDD-XXXXX)
name                     STRING
species                  STRING
breed                    STRING NULLABLE
date_of_birth            DATE NULLABLE
gender                   STRING NULLABLE
weight                   DECIMAL NULLABLE
medical_history          TEXT NULLABLE
owner_name               STRING
owner_phone              STRING NULLABLE
owner_address            STRING NULLABLE
owner_email              STRING NULLABLE
created_at, updated_at   TIMESTAMPS
```

### queues table
```
id                       INTEGER PRIMARY KEY
patient_id               FOREIGN KEY → patients.id
queue_number             INTEGER (per tanggal)
status                   ENUM (waiting, in-progress, completed, cancelled)
queue_date               DATE
complaint                TEXT NULLABLE
medical_record_id        FOREIGN KEY → medical_records.id NULLABLE
created_at, updated_at   TIMESTAMPS
```

### medical_records table
```
id                       INTEGER PRIMARY KEY
patient_id               FOREIGN KEY → patients.id
doctor_id                FOREIGN KEY → users.id
queue_id                 FOREIGN KEY → queues.id NULLABLE
visit_date               DATE
symptoms                 TEXT NULLABLE
diagnosis                TEXT NULLABLE
treatment                TEXT NULLABLE
prescription             TEXT NULLABLE
notes                    TEXT NULLABLE
created_at, updated_at   TIMESTAMPS
```

---

## Common Tasks

### Add New Patient
```php
// Controller
$data = [
    'name' => 'Fluffy',
    'species' => 'Kucing',
    // ...
];
$patient = Patient::create($data);
$patient->medical_record_number = Patient::generateMedicalRecordNumber();
$patient->save();
```

### Create Queue for Patient
```php
$queue = Queue::create([
    'patient_id' => $patientId,
    'queue_date' => $date,
    'complaint' => $complaint,
    'queue_number' => Queue::generateQueueNumber($date),
    'status' => 'waiting'
]);
```

### Update Queue Status
```php
$queue->status = 'in-progress';
$queue->save();
```

### Add Medical Record
```php
MedicalRecord::create([
    'patient_id' => $patientId,
    'doctor_id' => Auth::id(),
    'queue_id' => $queueId,
    'visit_date' => $date,
    'diagnosis' => 'Gastroenteritis',
    // ...
]);
// Queue akan auto-update ke completed
```

### Get Patient History
```php
$patient->medicalRecords()->orderBy('visit_date', 'desc')->get();
// atau
MedicalRecord::byPatient($patientId)->get();
```

---

## Debug Tips

### Check Queue Status
```php
Queue::whereDate('queue_date', today())->get()
  ->each(function($q) { echo "$q->queue_number: $q->status\n"; });
```

### Get Non-Cancelled Queues
```php
Queue::notCancelled()->whereDate('queue_date', today())->get();
```

### Check Patient Medical Record Number
```php
Patient::find($id)->medical_record_number;
```

### Count Today's Queues by Status
```php
Queue::whereDate('queue_date', today())->get()
  ->countBy('status');
```

---

## Performance Considerations

1. **Search Pasien**: AJAX call dengan limit 20 results
2. **Auto-Refresh Antrian**: 30 detik interval (adjustable)
3. **Load Relations**: Use `with()` untuk eager loading
4. **Pagination**: Default 10 items per page

---

## Troubleshooting

### Queue not showing up for dokter
- Check status is not "cancelled"
- Check queue_date is today
- Check relationship is correct

### Medical record number not generated
- Check patient.id exists
- Check date formatting
- Check unique constraint not violated

### Search not working
- Check AJAX route is accessible
- Check JS enabled in browser
- Check patient search API endpoint

### Status not auto-updating
- Check request method is POST/PUT
- Check middleware permissions
- Check model update triggers

---

## File Locations

```
Controllers:
  app/Http/Controllers/PatientController.php
  app/Http/Controllers/QueueController.php
  app/Http/Controllers/MedicalRecordController.php

Models:
  app/Models/Patient.php
  app/Models/Queue.php
  app/Models/MedicalRecord.php

Views:
  resources/views/admin/patients/
    ├── index.blade.php (UPDATED)
    ├── create.blade.php
    ├── edit.blade.php
    └── show.blade.php (UPDATED)
  
  resources/views/admin/queues/
    ├── index.blade.php (UPDATED)
    ├── create.blade.php
    ├── show.blade.php
    └── start-examination.blade.php (NEW)
  
  resources/views/dokter/patients/
    └── index.blade.php
  
  resources/views/dokter/medical_records/
    ├── create.blade.php
    ├── edit.blade.php
    └── index.blade.php

Routes:
  routes/web.php (UPDATED)

Database:
  database/migrations/
    ├── 2025_06_17_083041_create_patients_table.php
    ├── 2025_10_16_000000_create_queues_table.php
    └── 2025_06_17_083104_create_medical_records_table.php
```
