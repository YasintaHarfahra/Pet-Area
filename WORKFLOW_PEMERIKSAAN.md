# 📋 Workflow Pemeriksaan Pasien (Opsi 4)

## Overview
Alur pemeriksaan pasien yang baru lebih efisien dengan integrasi antrian dan rekam medis secara seamless.

---

## 🔄 Alur Lengkap

### **TAHAP 1: MENUNGGU (Waiting)**
**Halaman**: `/dokter/antrian` → Tab "Menunggu"

```
Status Queue: waiting
```

**Aksi Tersedia**:
1. **"Buat Rekam Medis"** (Icon: 📝 ungu)
   - Link ke: `/dokter/rekam-medis/create?patient_id={patient_id}&queue_id={queue_id}`
   - Fungsi: Buat rekam medis baru tanpa mengubah status queue
   - Hasil: Tetap di tab "Menunggu"

2. **"Mulai Pemeriksaan"** (Icon: ▶️ hijau)
   - Link ke: `/dokter/rekam-medis/create?patient_id={patient_id}&queue_id={queue_id}&start_examination=true`
   - Fungsi: **Buka form create medical record + AUTO UPDATE queue status ke "in-progress"**
   - Hasil: Pasien otomatis pindah ke tab "Sedang Dilayani"

**Flow Detail** (Ketika dokter klik "Mulai Pemeriksaan"):
```
1. Click tombol "Mulai Pemeriksaan"
   ↓
2. Redirect ke MedicalRecordController@create()
   ↓
3. Parameter ?start_examination=true terdeteksi
   ↓
4. Queue status AUTO diubah: waiting → in-progress
   ↓
5. Form medical record ditampilkan untuk diisi
   ↓
6. Dokter isi: symptoms, diagnosis, treatment, prescription
   ↓
7. Klik "Simpan"
   ↓
8. MedicalRecordController@store() dijalankan
   ↓
9. Medical Record CREATED
   ↓
10. Queue linked dengan medical_record_id
    ↓
11. Redirect ke /dokter/antrian dengan success message
    ↓
12. Pasien sekarang muncul di tab "Sedang Dilayani"
```

---

### **TAHAP 2: SEDANG DILAYANI (In-Progress)**
**Halaman**: `/dokter/antrian` → Tab "Sedang Dilayani"

```
Status Queue: in-progress
Medical Record: sudah ada (linked via medical_record_id)
```

**Aksi Tersedia**:
- **"Lanjut Pemeriksaan"** (Icon: ✏️ biru)
  - **Jika RM sudah ada** → Link ke: `/dokter/rekam-medis/{medicalRecord->id}/edit`
    - Fungsi: Edit form medical record yang sudah dibuat di tahap 1
    - Hasil: Dokter bisa update diagnosis, treatment, prescription lebih lanjut
  
  - **Jika RM belum ada** → Link ke: `/dokter/rekam-medis/create?patient_id={patient_id}&queue_id={queue_id}`
    - Fungsi: Buat RM baru jika dokter langsung skip ke tab ini
    - Hasil: Form create dibuka

**Flow Detail** (Ketika dokter klik "Lanjut Pemeriksaan"):
```
1. Click tombol "Lanjut Pemeriksaan"
   ↓
2. Jika medicalRecord ada:
   - Redirect ke MedicalRecordController@edit(medicalRecord)
   - Form edit ditampilkan dengan data RM lama
   - Dokter update diagnosis/treatment/prescription
   - Klik "Simpan"
   ↓
   Jika medicalRecord tidak ada:
   - Redirect ke MedicalRecordController@create(dengan patient_id & queue_id)
   - Form create ditampilkan kosong
   - Dokter isi data lengkap
   - Klik "Simpan"
   ↓
3. MedicalRecordController@update() atau @store() dijalankan
   ↓
4. Jika update (dari edit):
   - Medical Record UPDATED
   - Queue status AUTO diubah: in-progress → completed
   - Queue linked dengan medical_record_id (jika belum)
   ↓
5. Redirect ke /dokter/antrian dengan success message
   ↓
6. Pasien sekarang muncul di tab "Selesai"
```

**Kolom Tabel**:
- No. Antrian (badge hijau)
- Nama Pasien
- MRN
- Mulai Pemeriksaan (jam berapa queue dipindah ke in-progress)
- Aksi (link edit RM)

---

### **TAHAP 3: SELESAI (Completed)**
**Halaman**: `/dokter/antrian` → Tab "Selesai"

```
Status Queue: completed
Medical Record: sudah ada dan sudah selesai diisi lengkap
```

**Aksi Tersedia**:
- **"Lihat Rekam Medis"** (Icon: 👁️ abu-abu)
  - Link ke: `/dokter/rekam-medis/{medicalRecord->id}`
  - Fungsi: View detail rekam medis (read-only, tidak bisa edit dari sini)
  - Hasil: Halaman detail rekam medis ditampilkan

**Kolom Tabel**:
- No. Antrian (badge abu-abu)
- Nama Pasien
- MRN
- Waktu Selesai (jam berapa queue completed)
- Aksi (link view RM)

---

## 📊 Status Transitions

```
┌─────────┐
│ WAITING │ ──[Mulai Pemeriksaan]──> ┌─────────────┐
└─────────┘                           │ IN-PROGRESS │
                                      └─────────────┘
                                            │
                                    [Lanjut Pemeriksaan]
                                    [Edit + Save RM]
                                            │
                                            ▼
                                      ┌───────────┐
                                      │ COMPLETED │
                                      └───────────┘
```

---

## 🔗 Routes & Methods Involved

### Mulai Pemeriksaan (Waiting → In-Progress)
```
GET /dokter/rekam-medis/create?patient_id={id}&queue_id={id}&start_examination=true
→ MedicalRecordController@create()
   - Auto update queue status: waiting → in-progress
   - Display form create medical record

POST /dokter/rekam-medis
→ MedicalRecordController@store()
   - Create medical record
   - Update queue.medical_record_id
   - Queue status sudah in-progress (dari tahap sebelumnya)
   - Redirect to /dokter/antrian
```

### Lanjut Pemeriksaan (In-Progress → Completed)
```
GET /dokter/rekam-medis/{medicalRecord}/edit
→ MedicalRecordController@edit()
   - Display form edit medical record

PUT /dokter/rekam-medis/{medicalRecord}
→ MedicalRecordController@update()
   - Update medical record
   - Auto update queue status: in-progress → completed
   - Redirect to /dokter/antrian
```

### Lihat Rekam Medis (Completed - View Only)
```
GET /dokter/rekam-medis/{medicalRecord}
→ MedicalRecordController@show()
   - Display detail medical record (read-only)
```

---

## 💾 Database Changes

### Queue Model
```php
Queue {
  id
  patient_id
  queue_number
  status: 'waiting' | 'in-progress' | 'completed' | 'cancelled'
  queue_date
  complaint
  medical_record_id ← Link ke medical record yang aktif
  created_at
  updated_at
}
```

### Queue Relationship
```php
public function medicalRecord()
{
    return $this->hasOne(MedicalRecord::class);
}
```

### Controller Updates

**QueueController::doctorIndex()**
```php
$queues = Queue::with('patient', 'medicalRecord') // Added medicalRecord eager load
    ->whereDate('queue_date', today())
    ->orderBy('queue_number')
    ->get();
```

**MedicalRecordController::create()**
```php
// Jika ada ?start_examination=true
if ($request->has('start_examination') && $queue_id) {
    $queue = Queue::find($queue_id);
    if ($queue && $queue->status === 'waiting') {
        $queue->status = 'in-progress'; // Auto update
        $queue->save();
    }
}
```

**MedicalRecordController::store()**
```php
// Update queue dari waiting → in-progress (jika belum)
// Dan link medical_record_id
if ($request->has('queue_id')) {
    $queue = Queue::find($request->queue_id);
    if ($queue) {
        if ($queue->status === 'waiting') {
            $queue->status = 'in-progress';
        }
        $queue->medical_record_id = $medicalRecord->id;
        $queue->save();
    }
}
```

**MedicalRecordController::update()**
```php
// Auto update queue status ke completed
$queue = Queue::where('medical_record_id', $medicalRecord->id)->first();
if ($queue && $queue->status !== 'completed') {
    $queue->status = 'completed'; // Auto update
    $queue->save();
}
```

---

## 🎯 Keuntungan Opsi 4

✅ **Workflow Jelas**: Waiting → In-Progress → Completed  
✅ **One-Click Action**: "Mulai Pemeriksaan" langsung ke form + auto status  
✅ **Auto Status Update**: Tidak perlu manual ubah status  
✅ **Data Integration**: Medical record langsung linked ke queue  
✅ **Efficient UX**: Minimal clicks, maksimal automation  
✅ **Flexible**: Dokter bisa edit RM sebelum mark completed  
✅ **Audit Trail**: Setiap tahap terekam dengan jelas  

---

## 📱 UI Changes Summary

### Waiting Tab
- ❌ Removed: Dua tombol (Buat RM + Mulai Pemeriksaan)
- ✅ Added: Tombol "Mulai Pemeriksaan" (direct ke RM form + auto status)

### In-Progress Tab
- ❌ Removed: Button "Selesaikan Pemeriksaan" (simple status update)
- ✅ Added: Link "Lanjut Pemeriksaan" (conditional: edit RM if exists, create RM if not)

### Completed Tab
- ✅ Unchanged: Link "Lihat Rekam Medis" (view detail)

---

## 🧪 Testing Checklist

- [ ] Klik "Mulai Pemeriksaan" di tab Waiting → redirect ke form + status auto → in-progress
- [ ] Isi form dan save → medical record created + queue linked + redirect antrian
- [ ] Pasien muncul di tab "Sedang Dilayani"
- [ ] Klik "Lanjut Pemeriksaan" di tab In-Progress → open edit form
- [ ] Edit dan save → queue status auto → completed + redirect antrian
- [ ] Pasien muncul di tab "Selesai"
- [ ] Klik "Lihat Rekam Medis" di tab Selesai → view detail RM
- [ ] Search filter tetap berfungsi di semua tab
- [ ] Tab switching tetap smooth

---

**Created**: 2025-11-17  
**Version**: 1.0 - Opsi 4 Implementation  
**Status**: ✅ Ready for Testing
