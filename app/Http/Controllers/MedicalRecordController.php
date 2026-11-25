<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Pet; // Perlu untuk form pilih pasien
use App\Models\User;
use App\Models\Queue;
use App\Models\MedicalRecordDraft;
use App\Models\Treatment;
use App\Models\Medicine;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan dokter yang sedang login
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MedicalRecordController extends Controller
{
    // ADMIN: Menampilkan daftar semua rekam medis (Hanya Lihat)
    public function indexAdmin(Request $request)
    {
        Gate::authorize('view-medical-records'); // Gate untuk admin melihat rekam medis
        $filters = [
            'search' => $request->input('search'),
            'doctor_id' => $request->input('doctor_id'),
            'species' => $request->input('species'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];

        $recordsQuery = MedicalRecord::with(['pet' => fn($q) => $q->withTrashed(), 'doctor']);

        if ($filters['search']) {
            $search = $filters['search'];
            $recordsQuery->where(function ($query) use ($search) {
                $query->whereHas('pet', function ($petQuery) use ($search) {
                    $petQuery->withTrashed()
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('medical_record_number', 'like', "%{$search}%")
                        ->orWhere('owner_name', 'like', "%{$search}%");
                })->orWhereHas('doctor', function ($doctorQuery) use ($search) {
                    $doctorQuery->where('name', 'like', "%{$search}%");
                })->orWhere('diagnosis', 'like', "%{$search}%")
                    ->orWhere('symptoms', 'like', "%{$search}%");
            });
        }

        if ($filters['doctor_id']) {
            $recordsQuery->where('id_doctor', $filters['doctor_id']);
        }

        if ($filters['species']) {
            $recordsQuery->whereHas('pet', function ($query) use ($filters) {
                $query->withTrashed()->where('species', $filters['species']);
            });
        }

        if ($filters['date_from']) {
            $recordsQuery->whereDate('visit_date', '>=', $filters['date_from']);
        }

        if ($filters['date_to']) {
            $recordsQuery->whereDate('visit_date', '<=', $filters['date_to']);
        }

        $filteredCount = (clone $recordsQuery)->count();

        $medicalRecords = $recordsQuery->orderByDesc('visit_date')
            ->paginate(12)
            ->withQueryString();

        $doctors = User::where('role', 'dokter')->orderBy('name')->get();
        $speciesOptions = Pet::select('species')->whereNotNull('species')->distinct()->orderBy('species')->pluck('species');

        return view('admin.medical_records.index', compact('medicalRecords', 'filters', 'doctors', 'speciesOptions', 'filteredCount'));
    }

    // DOKTER: Menampilkan daftar rekam medis dengan tombol CRUD
    public function indexDoctor()
    {
        Gate::authorize('manage-medical-records'); // Gate untuk dokter mengelola rekam medis
        
        // Initialize filters
        $filters = [
            'search' => request('search', ''),
            'date_from' => request('date_from', ''),
            'date_to' => request('date_to', ''),
            'doctor_id' => request('doctor_id', ''),
            'species' => request('species', ''),
        ];

        // Build query dengan filter
        $query = MedicalRecord::with(['pet', 'doctor', 'transaction'])
                    ->orderByDesc('visit_date');

        // Apply search filter
        if ($filters['search']) {
            $search = $filters['search'];
            $query->whereHas('pet', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('medical_record_number', 'like', "%{$search}%");
            })->orWhereHas('doctor', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('diagnosis', 'like', "%{$search}%");
        }

        // Apply date range filter
        if ($filters['date_from']) {
            $query->whereDate('visit_date', '>=', $filters['date_from']);
        }
        if ($filters['date_to']) {
            $query->whereDate('visit_date', '<=', $filters['date_to']);
        }

        // Apply doctor filter
        if ($filters['doctor_id']) {
            $query->where('id_doctor', $filters['doctor_id']);
        }

        // Apply species filter
        if ($filters['species']) {
            $query->whereHas('pet', function ($q) use ($filters) {
                $q->where('species', $filters['species']);
            });
        }

        $medicalRecords = $query->paginate(10)->withQueryString();
        $filteredCount = $query->count();

        $doctors = User::where('role', 'dokter')->orderBy('name')->get();
        $speciesOptions = Pet::select('species')->whereNotNull('species')->distinct()->orderBy('species')->pluck('species');

        return view('dokter.medical_records.index', compact('medicalRecords', 'filters', 'doctors', 'speciesOptions', 'filteredCount'));
    }

    // ADMIN & DOKTER: Menampilkan detail rekam medis tertentu (Shared View)
    public function show(MedicalRecord $medicalRecord)
    {
        // Gate sudah diatur di routes/web.php untuk melihat, jadi tidak perlu Gate::authorize di sini
        $medicalRecord->load('pet', 'doctor', 'transaction', 'treatments', 'prescriptionItems');
        return view('shared.medical_records.show', compact('medicalRecord'));
    }

    // DOKTER: Menampilkan form untuk menambah rekam medis baru
    public function create(Request $request)
    {
        Gate::authorize('manage-medical-records'); // Hanya dokter yang bisa membuat
        
        // Load treatments dan medicines untuk dropdown
        $treatments = Treatment::active()->orderBy('kategori')->orderBy('nama_tindakan')->get();
        $medicines = Medicine::active()->orderBy('nama_obat')->get();
        
        // Jika ada pet_id dari antrian, langsung gunakan hewan tersebut
        if ($request->has('pet_id')) {
            $pet = Pet::with(['medicalRecords' => function ($q) {
                $q->orderByDesc('visit_date');
            }])->findOrFail($request->pet_id);
            $queue_id = $request->queue_id;
            $queue = null;
            $draft = null;
            $resume = $request->has('resume');
            
            // Get draft data jika sedang lanjutkan pemeriksaan
            if ($queue_id) {
                $queue = Queue::find($queue_id);
                
                if (!$queue) {
                    return abort(404, 'Antrian tidak ditemukan');
                }
                
                // Cek jika queue sudah selesai (completed)
                if ($queue->status === 'completed') {
                    return abort(403, 'Pemeriksaan ini sudah selesai. Anda tidak dapat mengubah data yang sudah disimpan.');
                }
                
                // Validasi: queue harus milik dokter yang login
                // PENTING: Jika queue in-progress tapi doctor_id NULL, set ke current user (auto-recovery)
                if ($queue->status === 'in-progress') {
                    if ($queue->id_doctor === null) {
                        // Auto-assign jika belum ada doctor_id (fallback untuk error recovery)
                        $queue->update([
                            'id_doctor' => Auth::id(),
                            'started_at' => now(),
                        ]);
                    } elseif ($queue->id_doctor !== Auth::id()) {
                        // Blok akses jika doctor_id set dan bukan milik current user
                        return abort(403, 'Anda tidak diizinkan melanjutkan pemeriksaan ini. Pemeriksaan sedang dikerjakan oleh dokter lain.');
                    }
                }
                
                // Cek draft data
                if ($resume) {
                    $draft = MedicalRecordDraft::where('id_queue', $queue_id)
                        ->where('id_doctor', Auth::id())
                        ->whereIn('status', ['draft', 'in-progress'])
                        ->first();
                    
                    if (!$draft) {
                        return redirect()->back()->with('error', 'Data draft tidak ditemukan.');
                    }
                }
                
                // Auto update queue status to in-progress ketika membuka form dari queue waiting
                if ($queue && $queue->status === 'waiting') {
                    $queue->update([
                        'status' => 'in-progress',
                        'id_doctor' => Auth::id(),
                        'started_at' => now(),
                    ]);
                    
                    // Create draft untuk menyimpan data sementara
                    $draft = MedicalRecordDraft::create([
                        'id_queue' => $queue_id,
                        'id_doctor' => Auth::id(),
                        'id_pet' => $request->pet_id,
                        'form_data' => [],
                        'status' => 'draft',
                        'started_at' => now(),
                        'last_updated_at' => now(),
                    ]);
                }
            }
            
            return view('dokter.medical_records.create', compact('patient', 'queue_id', 'queue', 'draft', 'resume', 'treatments', 'medicines'));
        }

        // Jika tidak ada patient_id, tampilkan form pilih pasien
        $patients = Pet::orderBy('name')->get();
        return view('dokter.medical_records.create', compact('patients', 'treatments', 'medicines'));
    }

    // DOKTER: Menyimpan rekam medis baru ke database
    public function store(Request $request)
    {
        // Log request untuk debugging
        Log::info('Medical Record Store Request', [
            'request_data' => $request->except(['_token']),
            'user_id' => Auth::id(),
            'has_pet_id' => $request->has('pet_id'),
            'pet_id' => $request->pet_id,
        ]);
        
        try {
            Gate::authorize('manage-medical-records');

            // Update validation untuk treatment dan medicines
            $validated = $request->validate([
                'pet_id' => 'required|exists:pets,id_pet',
                'visit_date' => 'required|date',
                'symptoms' => 'nullable|string',
                'diagnosis' => 'nullable|string',
                'treatment' => 'nullable|array', // Array of treatment IDs
                'treatment.*' => 'nullable|exists:treatments,id_treatment', // Validasi setiap ID
                'medicines_data' => 'nullable|json', // JSON array medicines
                'prescription' => 'nullable|string',
                'notes' => 'nullable|string',
                'queue_id' => 'nullable|exists:queues,id_queue',
                'next_control_date' => 'nullable|date|after_or_equal:today', // Tanggal kontrol lanjutan
                'follow_up_notes' => 'nullable|string|max:500', // Catatan kontrol
            ]);

            // ===== KALKULASI HARGA DARI TINDAKAN =====
            $treatmentIds = $request->treatment ?? [];
            $totalTreatmentPrice = 0;
            $treatmentNames = [];
            
            if (!empty($treatmentIds)) {
                $selectedTreatments = Treatment::active()
                    ->whereIn('id_treatment', $treatmentIds)
                    ->get();
                
                $totalTreatmentPrice = $selectedTreatments->sum('harga');
                $treatmentNames = $selectedTreatments->pluck('nama_tindakan')->toArray();
                
                Log::info('Treatment cost calculation', [
                    'pet_id' => $request->pet_id,
                    'treatment_names' => $treatmentNames,
                    'total_price' => $totalTreatmentPrice,
                ]);
            }

            // ===== KALKULASI HARGA DAN STOK DARI OBAT =====
            $medicinesArray = [];
            $totalMedicinePrice = 0;
            $medicineNames = [];
            
            if ($request->has('medicines_data') && !empty($request->medicines_data)) {
                $medicinesInput = json_decode($request->medicines_data, true) ?? [];
                
                // Pre-fetch semua obat sekaligus (1 query)
                $allMedicineIds = collect($medicinesInput)->pluck('medicine_id')->filter()->unique()->values()->all();
                $medicinesMap = Medicine::whereIn('id_medicine', $allMedicineIds)->get()->keyBy('id_medicine');

                foreach ($medicinesInput as $medicine) {
                    $medicineId = $medicine['medicine_id'] ?? null;
                    $qty = (int)($medicine['qty'] ?? 1);
                    $instructions = $medicine['instructions'] ?? '';
                    
                    if ($medicineId) {
                        // Ambil data obat dari map (tanpa query tambahan)
                        $medicineRecord = $medicinesMap[$medicineId] ?? null;
                        
                        if ($medicineRecord) {
                            $subtotal = $medicineRecord->harga_jual * $qty;
                            $totalMedicinePrice += $subtotal;
                            
                            // Simpan ke array dengan nama obat
                            $medicinesArray[] = [
                                'medicine_id' => $medicineId,
                                'qty' => $qty,
                                'instructions' => $instructions,
                            ];
                            
                            // Format untuk deskripsi
                            $medicineNames[] = "{$medicineRecord->nama_obat} ({$qty}x)";
                            
                            // Kurangi stok secara otomatis (BONUS)
                            $medicineRecord->decreaseStock($qty);
                            
                            Log::info('Medicine stock decreased', [
                                'medicine_id' => $medicineId,
                                'medicine_name' => $medicineRecord->nama_obat,
                                'qty_decreased' => $qty,
                                'remaining_stock' => $medicineRecord->fresh()->stok,
                            ]);
                        }
                    }
                }
            }

            // ===== TOTAL HARGA: TINDAKAN + OBAT =====
            $totalAmount = $totalTreatmentPrice + $totalMedicinePrice;
            
            $treatmentString = !empty($treatmentNames) ? implode(', ', $treatmentNames) : '';

            // Simpan Medical Record dengan medicines_data dan follow-up fields
            $medicalRecord = MedicalRecord::create([
                'id_pet' => $request->pet_id,
                'id_doctor' => Auth::id(),
                'visit_date' => $request->visit_date,
                'symptoms' => $request->symptoms,
                'diagnosis' => $request->diagnosis,
                'treatment' => $treatmentString,
                'prescription' => $request->prescription,
                'medicines_data' => !empty($medicinesArray) ? json_encode($medicinesArray) : null,
                'notes' => $request->notes,
                'next_control_date' => $request->next_control_date,
                'follow_up_notes' => $request->follow_up_notes,
                'follow_up_status' => $request->next_control_date ? 'pending' : null,
            ]);

            // Mark draft sebagai completed
            if ($request->has('queue_id')) {
                $draft = MedicalRecordDraft::where('id_queue', $request->queue_id)
                    ->where('id_doctor', Auth::id())
                    ->whereIn('status', ['draft', 'in-progress'])
                    ->first();
                
                if ($draft) {
                    $draft->update([
                        'status' => 'completed',
                        'last_updated_at' => now(),
                    ]);
                }
            }

            // AUTO-CREATE TRANSACTION dengan total harga (tindakan + obat)
            try {
                // Buat deskripsi ringkas
                $descriptionParts = [];
                
                if ($request->diagnosis) {
                    $descriptionParts[] = 'Diagnosis: ' . $request->diagnosis;
                }
                
                if (!empty($treatmentNames)) {
                    $descriptionParts[] = 'Tindakan: ' . implode(', ', $treatmentNames);
                }
                
                if (!empty($medicineNames)) {
                    $descriptionParts[] = 'Obat: ' . implode(', ', $medicineNames);
                }
                
                $description = !empty($descriptionParts) 
                    ? implode(' | ', $descriptionParts) 
                    : 'Layanan Medis';
                
                // Buat transaction dengan TOTAL HARGA (tindakan + obat)
                Transaction::create([
                    'id_pet' => $request->pet_id,
                    'id_doctor' => Auth::id(),
                    'id_medical_record' => $medicalRecord->id_medical_record,
                    'id_admin' => Auth::id(),
                    'transaction_date' => $request->visit_date,
                    'description' => $description,
                    'amount' => $totalAmount, // TOTAL: tindakan + obat
                    'service_fee' => 0,
                    'payment_method' => null,
                    'status' => 'pending',
                ]);

                Log::info('Transaction created with medicines', [
                    'medical_record_id' => $medicalRecord->id_medical_record,
                    'treatment_price' => $totalTreatmentPrice,
                    'medicine_price' => $totalMedicinePrice,
                    'total_amount' => $totalAmount,
                    'description' => $description,
                ]);
            } catch (\Exception $e) {
                // Log error tapi jangan sampai gagal menyimpan medical record
                Log::error('Failed to create transaction for medical record: ' . $e->getMessage(), [
                    'medical_record_id' => $medicalRecord->id_medical_record ?? null,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            // Update queue status to completed jika ada queue_id
            if ($request->has('queue_id')) {
                $queue = Queue::find($request->queue_id);
                if ($queue) {
                    $queue->update([
                        'status' => 'completed',
                        'id_medical_record' => $medicalRecord->id_medical_record,
                        'last_worked_at' => now(),
                    ]);
                }
            }

            return redirect()->route('dokter.antrian.index')->with('success', 'Rekam medis berhasil disimpan! Transaksi otomatis dibuat dengan total Rp' . number_format($totalAmount, 0, ',', '.'));
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangkap error validasi dan redirect back dengan pesan error
            Log::warning('Validation error in medical record store', [
                'errors' => $e->errors(),
                'request_data' => $request->except(['_token']),
            ]);
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Tangkap error lain (DB, logic, dsb)
            Log::error('Failed to save medical record: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token']),
            ]);
            return back()->with('error', 'Gagal menyimpan rekam medis: ' . $e->getMessage())->withInput();
        }
    }

    // DOKTER: Menampilkan form untuk mengedit rekam medis
    public function edit(MedicalRecord $medicalRecord)
    {
        Gate::authorize('manage-medical-records');
        if (! $this->canEditRecord($medicalRecord)) {
            return redirect()->route('dokter.medical_records.index')
                ->withErrors('Rekam medis ini sudah terkunci karena melewati batas waktu 2x24 jam.');
        }
        // Load treatments dan medicines untuk dropdown
        $treatments = Treatment::active()->orderBy('kategori')->orderBy('nama_tindakan')->get();
        $medicines = Medicine::active()->orderBy('nama_obat')->get();
        $patients = Pet::orderBy('name')->get();
        return view('dokter.medical_records.edit', compact('medicalRecord', 'patients', 'treatments', 'medicines'));
    }

    // DOKTER: Memperbarui data rekam medis di database
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        Gate::authorize('manage-medical-records');
        if (! $this->canEditRecord($medicalRecord)) {
            return redirect()->route('dokter.medical_records.index')
                ->withErrors('Rekam medis ini sudah terkunci karena melewati batas waktu 2x24 jam.');
        }

        // Update validation untuk support array treatment IDs dan medicines_data
        $request->validate([
            'pet_id' => 'required|exists:pets,id_pet',
            'visit_date' => 'required|date',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|array', // Array of treatment IDs
            'treatment.*' => 'nullable|exists:treatments,id_treatment',
            'medicines_data' => 'nullable|json', // JSON array medicines
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'next_control_date' => 'nullable|date', // Tanggal kontrol lanjutan
            'follow_up_notes' => 'nullable|string|max:500', // Catatan kontrol
            'follow_up_status' => 'nullable|in:pending,completed,cancelled', // Status kontrol
        ]);

        // ===== KALKULASI ULANG HARGA DARI TINDAKAN =====
        $treatmentIds = $request->treatment ?? [];
        $totalTreatmentPrice = 0;
        $treatmentNames = [];
        
        if (!empty($treatmentIds)) {
            $selectedTreatments = Treatment::active()
                ->whereIn('id_treatment', $treatmentIds)
                ->get();
            
            $totalTreatmentPrice = $selectedTreatments->sum('harga');
            $treatmentNames = $selectedTreatments->pluck('nama_tindakan')->toArray();
        }

        // ===== HANDLE MEDICINES UPDATE =====
        // Restore previous medicines stock (pre-fetch all at once)
        $oldMedicines = $medicalRecord->medicines_data ?? [];
        if ($oldMedicines && is_array($oldMedicines)) {
            $oldMedicineIds = collect($oldMedicines)->pluck('medicine_id')->filter()->unique()->values()->all();
            $oldMedicinesMap = Medicine::whereIn('id_medicine', $oldMedicineIds)->get()->keyBy('id_medicine');

            foreach ($oldMedicines as $medicine) {
                $medicineRecord = $oldMedicinesMap[$medicine['medicine_id'] ?? null] ?? null;
                if ($medicineRecord) {
                    $medicineRecord->increaseStock($medicine['qty'] ?? 1);
                    Log::info('Medicine stock restored on update', [
                        'medicine_id' => $medicineRecord->id_medicine,
                        'qty_restored' => $medicine['qty'] ?? 1,
                    ]);
                }
            }
        }

        // ===== KALKULASI HARGA DAN STOK DARI OBAT BARU =====
        $medicinesArray = [];
        $totalMedicinePrice = 0;
        $medicineNames = [];
        
        if ($request->has('medicines_data') && !empty($request->medicines_data)) {
            $medicinesInput = json_decode($request->medicines_data, true) ?? [];
            
            // Pre-fetch semua obat sekaligus (1 query)
            $newMedicineIds = collect($medicinesInput)->pluck('medicine_id')->filter()->unique()->values()->all();
            $newMedicinesMap = Medicine::whereIn('id_medicine', $newMedicineIds)->get()->keyBy('id_medicine');

            foreach ($medicinesInput as $medicine) {
                $medicineId = $medicine['medicine_id'] ?? null;
                $qty = (int)($medicine['qty'] ?? 1);
                $instructions = $medicine['instructions'] ?? '';
                
                if ($medicineId) {
                    $medicineRecord = $newMedicinesMap[$medicineId] ?? null;
                    
                    if ($medicineRecord) {
                        $subtotal = $medicineRecord->harga_jual * $qty;
                        $totalMedicinePrice += $subtotal;
                        
                        $medicinesArray[] = [
                            'medicine_id' => $medicineId,
                            'qty' => $qty,
                            'instructions' => $instructions,
                        ];
                        
                        $medicineNames[] = "{$medicineRecord->nama_obat} ({$qty}x)";
                        
                        // Kurangi stok untuk medicines baru
                        $medicineRecord->decreaseStock($qty);
                        
                        Log::info('Medicine stock decreased on update', [
                            'medicine_id' => $medicineId,
                            'qty_decreased' => $qty,
                        ]);
                    }
                }
            }
        }

        // ===== TOTAL HARGA BARU =====
        $totalAmount = $totalTreatmentPrice + $totalMedicinePrice;
        $treatmentString = !empty($treatmentNames) ? implode(', ', $treatmentNames) : '';

        // Update medical record dengan medicines_data dan follow-up fields
        $medicalRecord->update([
            'id_pet' => $request->pet_id,
            'visit_date' => $request->visit_date,
            'symptoms' => $request->symptoms,
            'diagnosis' => $request->diagnosis,
            'treatment' => $treatmentString,
            'prescription' => $request->prescription,
            'medicines_data' => !empty($medicinesArray) ? json_encode($medicinesArray) : null,
            'notes' => $request->notes,
            'next_control_date' => $request->next_control_date,
            'follow_up_notes' => $request->follow_up_notes,
            'follow_up_status' => $request->follow_up_status ?? $medicalRecord->follow_up_status,
        ]);

        // Update transaksi yang terkait jika ada
        try {
            $transaction = Transaction::where('id_medical_record', $medicalRecord->id_medical_record)->first();
            if ($transaction) {
                // Buat deskripsi ringkas baru
                $descriptionParts = [];
                
                if ($request->diagnosis) {
                    $descriptionParts[] = 'Diagnosis: ' . $request->diagnosis;
                }
                
                if (!empty($treatmentNames)) {
                    $descriptionParts[] = 'Tindakan: ' . implode(', ', $treatmentNames);
                }
                
                if (!empty($medicineNames)) {
                    $descriptionParts[] = 'Obat: ' . implode(', ', $medicineNames);
                }
                
                $description = !empty($descriptionParts) 
                    ? implode(' | ', $descriptionParts) 
                    : 'Layanan Medis';
                
                $transaction->update([
                    'amount' => $totalAmount, // TOTAL: tindakan + obat
                    'description' => $description,
                ]);
                
                Log::info('Transaction updated with medicines', [
                    'transaction_id' => $transaction->id_transaction,
                    'treatment_price' => $totalTreatmentPrice,
                    'medicine_price' => $totalMedicinePrice,
                    'total_amount' => $totalAmount,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update transaction: ' . $e->getMessage());
        }

        // Update queue status to completed jika ada queue yang terkait
        $queue = \App\Models\Queue::where('id_medical_record', $medicalRecord->id_medical_record)->first();
        if ($queue && $queue->status !== 'completed') {
            $queue->status = 'completed';
            $queue->save();
        }

        return redirect()->route('dokter.antrian.index')->with('success', 'Rekam medis berhasil diperbarui dengan total tagihan Rp' . number_format($totalAmount, 0, ',', '.'));
    }

    protected function canEditRecord(MedicalRecord $medicalRecord): bool
    {
        if (! $medicalRecord->visit_date) {
            return false;
        }

        $visitDate = Carbon::parse($medicalRecord->visit_date);

        return now()->lessThan($visitDate->copy()->addHours(48));
    }

    // DOKTER: Menghapus rekam medis dari database
    public function destroy(MedicalRecord $medicalRecord)
    {
        Gate::authorize('manage-medical-records');
        // Opsional: Pastikan hanya dokter yang membuat RM ini yang bisa menghapus
        // if ($medicalRecord->doctor_id !== Auth::id()) {
        //     abort(403, 'Anda tidak diizinkan menghapus rekam medis ini.');
        // }
        $medicalRecord->delete();

        return redirect()->route('dokter.medical_records.index')->with('success', 'Rekam medis berhasil dihapus!');
    }

    /**
     * Auto-save draft medical record
     * AJAX endpoint untuk menyimpan form data ke draft secara otomatis
     */
    public function autosaveDraft(Request $request)
    {
        Gate::authorize('manage-medical-records');

        $request->validate([
            'queue_id' => 'required|exists:queues,id_queue',
            'pet_id' => 'required|exists:pets,id_pet',
            'form_data' => 'required|array',
        ]);

        try {
            $queue = Queue::findOrFail($request->queue_id);
            
            // Validasi: hanya dokter pemilik queue yang bisa save draft
            if ($queue->id_doctor !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak diizinkan menyimpan draft untuk queue ini.'
                ], 403);
            }
            
            // Validasi: queue tidak boleh completed atau cancelled
            if (in_array($queue->status, ['completed', 'cancelled'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemeriksaan ini sudah selesai atau dibatalkan.'
                ], 403);
            }

            // Update atau create draft
            $draft = MedicalRecordDraft::updateOrCreate(
                [
                    'id_queue' => $request->queue_id,
                    'id_doctor' => Auth::id(),
                ],
                [
                    'id_pet' => $request->pet_id,
                    'form_data' => $request->form_data,
                    'status' => 'in-progress',
                    'last_updated_at' => now(),
                ]
            );

            // Update queue last_worked_at
            $queue->update(['last_worked_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Draft berhasil disimpan',
                'draft_id' => $draft->id_medical_record_draft,
                'last_updated' => $draft->last_updated_at->format('H:i:s')
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to autosave draft: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan draft: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve draft data untuk form pre-fill
     */
    public function retrieveDraft(Request $request)
    {
        Gate::authorize('manage-medical-records');

        $request->validate([
            'queue_id' => 'required|exists:queues,id_queue',
        ]);

        try {
            $draft = MedicalRecordDraft::where('id_queue', $request->queue_id)
                ->where('id_doctor', Auth::id())
                ->whereIn('status', ['draft', 'in-progress'])
                ->first();

            if (!$draft) {
                return response()->json([
                    'success' => false,
                    'message' => 'Draft tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'form_data' => $draft->form_data,
                'started_at' => $draft->started_at,
                'last_updated_at' => $draft->last_updated_at,
                'duration_minutes' => $draft->getDurationMinutes()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve draft: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data draft'
            ], 500);
        }
    }

    /**
     * Calculate total price dari selected treatments
     * AJAX endpoint untuk preview harga real-time di form
     */
    public function calculateTreatmentPrice(Request $request)
    {
        Gate::authorize('manage-medical-records');

        $request->validate([
            'treatments' => 'required|array',
            'treatments.*' => 'string',
        ]);

        try {
            $treatments = array_filter(array_map('trim', $request->treatments));
            $totalPrice = 0;

            if (!empty($treatments)) {
                $totalPrice = Treatment::active()
                    ->whereIn('nama_tindakan', $treatments)
                    ->sum('harga');
            }

            return response()->json([
                'success' => true,
                'treatments' => $treatments,
                'total_price' => $totalPrice,
                'formatted_price' => 'Rp' . number_format($totalPrice, 0, ',', '.'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to calculate treatment price: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung harga'
            ], 500);
        }
    }
}
