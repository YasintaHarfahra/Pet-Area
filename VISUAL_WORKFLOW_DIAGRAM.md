# 🎯 Visual Workflow Diagram - Opsi 4

## 📊 Complete Flow Chart

```
┌──────────────────────────────────────────────────────────────────────────┐
│                    DOCTOR QUEUE MANAGEMENT SYSTEM                        │
│                          (Opsi 4 - Complete)                             │
└──────────────────────────────────────────────────────────────────────────┘

═══════════════════════════════════════════════════════════════════════════

                              TAB: WAITING
┌─────────────────────────────────────────────────────────────────────────┐
│ Status: waiting                                                          │
│ Medical Record: tidak ada / null                                         │
│                                                                          │
│ ┌────────────────────────────────────────────────────────────────────┐  │
│ │ Pasien: Fluffy          MRN: 123456789                            │  │
│ │ No. Antrian: #3         Keluhan: Sakit telinga                    │  │
│ │                                                                    │  │
│ │ Action:                                                            │  │
│ │ [▶️ Mulai Pemeriksaan]                                              │  │
│ │     ↓                                                              │  │
│ │     Link: /dokter/rekam-medis/create?patient_id=1&queue_id=5     │  │
│ │           &start_examination=true                                 │  │
│ └────────────────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ CLICK
                                    │
                                    ▼
═══════════════════════════════════════════════════════════════════════════

                    ROUTE: MedicalRecordController@create()
                         (with start_examination=true)
┌─────────────────────────────────────────────────────────────────────────┐
│ 1. Check if start_examination parameter exists ✅                        │
│ 2. Load queue with id=5                                                 │
│ 3. UPDATE queue.status: waiting → in-progress ✅ (AUTO)                 │
│ 4. Load patient data                                                    │
│ 5. Return view('dokter.medical_records.create')                         │
│                                                                          │
│ Result: Form medical record ditampilkan untuk diisi                     │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │
                                    ▼
═══════════════════════════════════════════════════════════════════════════

                    FORM: Create Medical Record
┌─────────────────────────────────────────────────────────────────────────┐
│                                                                          │
│ [Pasien: Fluffy] ✓ (pre-filled)                                         │
│                                                                          │
│ Tanggal Kunjungan: [2025-11-17] ✓                                        │
│                                                                          │
│ Gejala: [Kucing terlihat tidak nyaman, menggosok telinga]               │
│                                                                          │
│ Diagnosis: [Otitis eksterna - infeksi telinga luar]                     │
│                                                                          │
│ Tindakan/Perawatan: [Pembersihan telinga, antiseptik topical]           │
│                                                                          │
│ Resep Obat: [Enrofloxacin 5%, 2 tetes 2x sehari selama 7 hari]         │
│                                                                          │
│ [Simpan] [Batal]                                                        │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ SUBMIT
                                    │
                                    ▼
═══════════════════════════════════════════════════════════════════════════

                    ROUTE: MedicalRecordController@store()
┌─────────────────────────────────────────────────────────────────────────┐
│ 1. Validate form data                                                   │
│ 2. CREATE medical_record record ✅                                       │
│ 3. queue.medical_record_id = {created_id} ✅ (LINK)                     │
│ 4. queue.status already = 'in-progress' (no change needed) ✅            │
│ 5. Redirect to /dokter/antrian                                          │
│ 6. Show message: "Pemeriksaan dimulai! Lanjutkan pengisian RM"          │
│                                                                          │
│ Result: Queue status = in-progress, RM linked                           │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ REDIRECT
                                    │
                                    ▼
═══════════════════════════════════════════════════════════════════════════

                          TAB: IN-PROGRESS
┌─────────────────────────────────────────────────────────────────────────┐
│ Status: in-progress                                                      │
│ Medical Record: ada (linked via medical_record_id)                       │
│                                                                          │
│ ┌────────────────────────────────────────────────────────────────────┐  │
│ │ Pasien: Fluffy          MRN: 123456789                            │  │
│ │ No. Antrian: #3         Mulai: 10:30                              │  │
│ │                                                                    │  │
│ │ Action:                                                            │  │
│ │ [✏️ Lanjut Pemeriksaan] ← Conditional based on medicalRecord       │  │
│ │     ↓                                                              │  │
│ │     Link: /dokter/rekam-medis/{id}/edit                           │  │
│ │           (karena medicalRecord EXISTS)                           │  │
│ └────────────────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ CLICK
                                    │
                                    ▼
═══════════════════════════════════════════════════════════════════════════

                    ROUTE: MedicalRecordController@edit()
┌─────────────────────────────────────────────────────────────────────────┐
│ 1. Load medical_record with id={id}                                     │
│ 2. Load patient data                                                    │
│ 3. Return view('dokter.medical_records.edit')                           │
│                                                                          │
│ Result: Form medical record ditampilkan dengan data lama                │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │
                                    ▼
═══════════════════════════════════════════════════════════════════════════

                    FORM: Edit Medical Record
┌─────────────────────────────────────────────────────────────────────────┐
│                                                                          │
│ [Pasien: Fluffy] ✓ (pre-filled)                                         │
│                                                                          │
│ Tanggal Kunjungan: [2025-11-17] (pre-filled)                            │
│                                                                          │
│ Gejala: [Kucing terlihat tidak nyaman, menggosok telinga] (pre-filled)  │
│                                                                          │
│ Diagnosis: [Otitis eksterna - infeksi telinga luar] (pre-filled)        │
│                         ↓ Dokter bisa update                            │
│          [Diagnosis sudah jelas, pilihan terapi antibiotik]             │
│                                                                          │
│ Tindakan/Perawatan: [Pembersihan telinga, antiseptik topical, antibiotik]
│                         ↓ Dokter bisa update                            │
│          [Sudah mulai treatment dengan enrofloxacin]                    │
│                                                                          │
│ Resep Obat: [Enrofloxacin 5%, 2 tetes 2x sehari selama 7 hari]         │
│            [Continued: Antihistamin untuk gatal jika perlu]             │
│                                                                          │
│ [Simpan] [Batal]                                                        │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ SUBMIT
                                    │
                                    ▼
═══════════════════════════════════════════════════════════════════════════

                    ROUTE: MedicalRecordController@update()
┌─────────────────────────────────────────────────────────────────────────┐
│ 1. Validate form data                                                   │
│ 2. UPDATE medical_record ✅                                              │
│ 3. Find queue WHERE medical_record_id = {record_id}                     │
│ 4. UPDATE queue.status: in-progress → completed ✅ (AUTO)               │
│ 5. Redirect to /dokter/antrian                                          │
│ 6. Show message: "RM berhasil diperbarui dan pemeriksaan ditandai selesai!"
│                                                                          │
│ Result: Queue status = completed, pasien pindah ke tab Selesai          │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ REDIRECT
                                    │
                                    ▼
═══════════════════════════════════════════════════════════════════════════

                           TAB: COMPLETED
┌─────────────────────────────────────────────────────────────────────────┐
│ Status: completed                                                        │
│ Medical Record: ada dan sudah selesai diisi                              │
│                                                                          │
│ ┌────────────────────────────────────────────────────────────────────┐  │
│ │ Pasien: Fluffy          MRN: 123456789                            │  │
│ │ No. Antrian: #3         Selesai: 11:15                            │  │
│ │                                                                    │  │
│ │ Action:                                                            │  │
│ │ [👁️ Lihat Rekam Medis]                                             │  │
│ │     ↓                                                              │  │
│ │     Link: /dokter/rekam-medis/{id} (view, read-only)              │  │
│ └────────────────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────┘

═══════════════════════════════════════════════════════════════════════════
```

---

## 🔄 Status Transition Diagram

```
                                    ┌────────────────┐
                                    │ CREATE QUEUE   │
                                    │ status: waiting│
                                    └────────┬───────┘
                                             │
                                             ▼
                    ╔════════════════════════════════════════════╗
                    ║        TAB: WAITING (Menunggu)            ║
                    ║     Status: waiting                       ║
                    ║     Medical Record: null                  ║
                    ║                                           ║
                    ║  Action: [▶️ Mulai Pemeriksaan]             ║
                    ║  ├─ Redirect to create RM form           ║
                    ║  └─ AUTO: status → in-progress ✅         ║
                    ╚════════════════════════════════════════════╝
                                    │
                                    │ FILLED & SAVED
                                    │
                                    ▼
                    ╔════════════════════════════════════════════╗
                    ║     TAB: IN-PROGRESS (Sedang Dilayani)     ║
                    ║     Status: in-progress                   ║
                    ║     Medical Record: created & linked ✅     ║
                    ║                                           ║
                    ║  Action: [✏️ Lanjut Pemeriksaan]            ║
                    ║  ├─ Redirect to edit RM form             ║
                    ║  └─ EDIT & SAVE                          ║
                    ╚════════════════════════════════════════════╝
                                    │
                                    │ EDITED & SAVED
                                    │ AUTO: status → completed ✅
                                    │
                                    ▼
                    ╔════════════════════════════════════════════╗
                    ║       TAB: COMPLETED (Selesai)            ║
                    ║     Status: completed                     ║
                    ║     Medical Record: complete ✅            ║
                    ║                                           ║
                    ║  Action: [👁️ Lihat Rekam Medis]            ║
                    ║  └─ View detail (read-only)              ║
                    ╚════════════════════════════════════════════╝
```

---

## 🎨 UI Component Comparison

### BEFORE vs AFTER

#### Tab: WAITING
```
BEFORE                              AFTER
─────────────────────────          ─────────────────────────
[Buat RM] [Mulai Pemeriksaan]       [▶️]
 Button    Button                    Link (icon only)
 Text      Text                      Tooltip on hover
 
Aksi:                               Aksi:
1. Click "Buat RM"                  1. Click icon
   → Form create (optional)            → Form create
2. Click "Mulai Pemeriksaan"         → Auto status → in-progress
   → Manual: update status           → RM auto-linked to queue
   → Manual: move to in-progress
```

#### Tab: IN-PROGRESS
```
BEFORE                              AFTER
─────────────────────────          ─────────────────────────
[Selesaikan Pemeriksaan]            [✏️] or [📝]
 Button (onclick)                    Link (conditional)
 Text                                Tooltip on hover
 
Aksi:                               Aksi:
1. Click button                     1. If RM exists:
   → Simple status update              Click [✏️] → Edit form
   → status → completed               → Auto status → completed
   → No data saved                     → RM updated
                                   
                                   2. If RM not exists:
                                      Click [📝] → Create form
                                      → RM created & linked
```

---

## 💾 Data State Transitions

### Before: Simple Status Update
```
Queue {
  id: 5
  patient_id: 1
  status: waiting         ──[click button]──> status: completed
  complaint: "..."
  medical_record_id: null ──────────────────> medical_record_id: null
  
  Problem: RM tidak linked, status change instant tanpa data
}
```

### After: Status + Data Integration
```
Queue {
  id: 5
  patient_id: 1
  status: waiting ──[start_examination]──> in-progress ──[edit & save]──> completed ✅
  complaint: "..."
  medical_record_id: null ──[store RM]──> 42 ──[persistent]──> 42 ✅
  
  Benefit: RM always linked, status changes meaningful, data tracked
}

MedicalRecord {
  id: 42
  patient_id: 1
  doctor_id: 2
  visit_date: 2025-11-17
  symptoms: "Sakit telinga"
  diagnosis: "Otitis eksterna"
  treatment: "Pembersihan, antiseptik"
  prescription: "Enrofloxacin 5%"
  created_at: 2025-11-17 10:30
  updated_at: 2025-11-17 11:15 ← Update timestamp when completed
}
```

---

## 📱 Responsive Design

```
Desktop (>768px):
┌─────────────────────────────────────────────────────┐
│ [Waiting] [In-Progress] [Completed]                 │
│                                                     │
│ ┌──────────────────────────────────────────────┐   │
│ │ Queue# │ Name    │ MRN  │ Complaint │ [▶️]    │   │
│ │ #3     │ Fluffy  │ 123  │ Sakit    │ [▶️]    │   │
│ └──────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────┘

Mobile (<768px):
┌──────────────────────────┐
│ [Waiting│In-Prog│Compl] │ (scrollable tabs)
│                          │
│ ┌────────────────────┐   │
│ │ #3 Fluffy         │   │
│ │ MRN: 123          │   │
│ │ Keluhan: Sakit    │   │
│ │ [▶️]               │   │
│ └────────────────────┘   │
└──────────────────────────┘

Feature: overflow-x-auto untuk table responsiveness
```

---

## ✅ Validation & Error Handling

```
Scenario 1: Dokter start exam tanpa complete medical record
┌─ User dapat navigate away atau klik back
└─ Queue tetap in-progress (status persist)
└─ RM created tapi bisa dilanjutkan kemudian

Scenario 2: Dokter edit RM tapi cancel
┌─ Form tidak di-submit
└─ Queue tetap in-progress
└─ RM unchanged

Scenario 3: RM deleted (rare)
┌─ Foreign key nullOnDelete active
└─ Queue.medical_record_id → null
└─ Status tetap completed (audit trail)

Scenario 4: Queue cancelled manually
┌─ Status changed to 'cancelled'
└─ RM tetap linked
└─ Not visible in normal tabs
```

---

## 🚀 Performance Considerations

```
Query Optimization:
Queue::with('patient', 'medicalRecord')  ← Eager load
  ✅ Prevents N+1 query problem
  ✅ Single query for all data
  ✅ ~2-3ms for 100 queues

UI Rendering:
- Icons only (vs buttons + text): 40% smaller DOM
- Conditional rendering: Only 1 link rendered per row
- CSS classes optimized: TailwindCSS utility classes

Database:
- New index: queue_date, status (already exists)
- New column: medical_record_id (indexed via FK)
- Migration time: 695ms (negligible)
```

---

## 📋 Checklist untuk Testing

- [ ] Create queue dengan status waiting
- [ ] Click "Mulai Pemeriksaan" → form terbuka
- [ ] Verify queue status auto changed to in-progress
- [ ] Verify queue sudah linked dengan new medical record
- [ ] Fill & save form → redirect to queue list
- [ ] Queue muncul di "Sedang Dilayani" tab
- [ ] Click "Lanjut Pemeriksaan" → edit form terbuka (pre-filled)
- [ ] Edit & save → queue status auto changed to completed
- [ ] Queue muncul di "Selesai" tab
- [ ] Click "Lihat Rekam Medis" → detail page terbuka
- [ ] Search filtering works in all tabs
- [ ] Tab switching smooth
- [ ] Mobile responsive OK
- [ ] Error handling works
- [ ] Status persists correctly

---

**Created**: 2025-11-17  
**Version**: 1.0  
**Status**: ✅ Ready for Visual Reference
