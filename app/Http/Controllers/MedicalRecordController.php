<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient; // Perlu untuk form pilih pasien
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan dokter yang sedang login
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

        $recordsQuery = MedicalRecord::with(['patient', 'doctor']);

        if ($filters['search']) {
            $search = $filters['search'];
            $recordsQuery->where(function ($query) use ($search) {
                $query->whereHas('patient', function ($patientQuery) use ($search) {
                    $patientQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('medical_record_number', 'like', "%{$search}%")
                        ->orWhere('owner_name', 'like', "%{$search}%");
                })->orWhereHas('doctor', function ($doctorQuery) use ($search) {
                    $doctorQuery->where('name', 'like', "%{$search}%");
                })->orWhere('diagnosis', 'like', "%{$search}%")
                    ->orWhere('symptoms', 'like', "%{$search}%");
            });
        }

        if ($filters['doctor_id']) {
            $recordsQuery->where('doctor_id', $filters['doctor_id']);
        }

        if ($filters['species']) {
            $recordsQuery->whereHas('patient', function ($query) use ($filters) {
                $query->where('species', $filters['species']);
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
        $speciesOptions = Patient::select('species')->whereNotNull('species')->distinct()->orderBy('species')->pluck('species');

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
        $query = MedicalRecord::with(['patient', 'doctor'])
                                ->orderByDesc('visit_date');

        // Apply search filter
        if ($filters['search']) {
            $search = $filters['search'];
            $query->whereHas('patient', function ($q) use ($search) {
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
            $query->where('doctor_id', $filters['doctor_id']);
        }

        // Apply species filter
        if ($filters['species']) {
            $query->whereHas('patient', function ($q) use ($filters) {
                $q->where('species', $filters['species']);
            });
        }

        $medicalRecords = $query->paginate(10)->withQueryString();
        $filteredCount = $query->count();

        $doctors = User::where('role', 'dokter')->orderBy('name')->get();
        $speciesOptions = Patient::select('species')->whereNotNull('species')->distinct()->orderBy('species')->pluck('species');

        return view('dokter.medical_records.index', compact('medicalRecords', 'filters', 'doctors', 'speciesOptions', 'filteredCount'));
    }

    // ADMIN & DOKTER: Menampilkan detail rekam medis tertentu (Shared View)
    public function show(MedicalRecord $medicalRecord)
    {
        // Gate sudah diatur di routes/web.php untuk melihat, jadi tidak perlu Gate::authorize di sini
        $medicalRecord->load('patient', 'doctor');
        return view('shared.medical_records.show', compact('medicalRecord'));
    }

    // DOKTER: Menampilkan form untuk menambah rekam medis baru
    public function create(Request $request)
    {
        Gate::authorize('manage-medical-records'); // Hanya dokter yang bisa membuat
        
        // Jika ada patient_id dari antrian, langsung gunakan pasien tersebut
        if ($request->has('patient_id')) {
            $patient = Patient::with(['medicalRecords' => function ($q) {
                $q->orderByDesc('visit_date');
            }])->findOrFail($request->patient_id);
            $queue_id = $request->queue_id;
            $queue = null;
            
            // Auto update queue status to in-progress ketika membuka form dari queue waiting
            // Jika ada queue_id dan status masih waiting, berarti ini dari tab "Menunggu"
            if ($queue_id) {
                $queue = \App\Models\Queue::find($queue_id);
                if ($queue) {
                    if ($queue->status === 'waiting') {
                        $queue->status = 'in-progress';
                        $queue->save();
                    }
                }
            }
            
            return view('dokter.medical_records.create', compact('patient', 'queue_id', 'queue'));
        }

        // Jika tidak ada patient_id, tampilkan form pilih pasien
        $patients = Patient::orderBy('name')->get();
        return view('dokter.medical_records.create', compact('patients'));
    }

    // DOKTER: Menyimpan rekam medis baru ke database
    public function store(Request $request)
    {
        Gate::authorize('manage-medical-records');

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'visit_date' => 'required|date',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'prescription' => 'nullable|string', // Untuk resep obat
            'notes' => 'nullable|string',
            'queue_id' => 'nullable|exists:queues,id',
        ]);

        $medicalRecord = MedicalRecord::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => Auth::id(), // Dokter yang sedang login otomatis menjadi pencatat
            'visit_date' => $request->visit_date,
            'symptoms' => $request->symptoms,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
        ]);

        // Update queue status to completed jika ada queue_id
        if ($request->has('queue_id')) {
            $queue = \App\Models\Queue::find($request->queue_id);
            if ($queue) {
                $queue->status = 'completed';  // Auto set to completed
                $queue->medical_record_id = $medicalRecord->id;
                $queue->save();
            }
        }

        return redirect()->route('dokter.queues.index')->with('success', 'Rekam medis berhasil disimpan dan pemeriksaan ditandai selesai!');
    }

    // DOKTER: Menampilkan form untuk mengedit rekam medis
    public function edit(MedicalRecord $medicalRecord)
    {
        Gate::authorize('manage-medical-records');
        if (! $this->canEditRecord($medicalRecord)) {
            return redirect()->route('dokter.medical_records.index')
                ->withErrors('Rekam medis ini sudah terkunci karena melewati batas waktu 2x24 jam.');
        }
        // Opsional: Pastikan hanya dokter yang membuat RM ini yang bisa mengedit
        // if ($medicalRecord->doctor_id !== Auth::id()) {
        //     abort(403, 'Anda tidak diizinkan mengedit rekam medis ini.');
        // }
        $patients = Patient::orderBy('name')->get(); // Diperlukan untuk menampilkan nama pasien
        return view('dokter.medical_records.edit', compact('medicalRecord', 'patients'));
    }

    // DOKTER: Memperbarui data rekam medis di database
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        Gate::authorize('manage-medical-records');
        if (! $this->canEditRecord($medicalRecord)) {
            return redirect()->route('dokter.medical_records.index')
                ->withErrors('Rekam medis ini sudah terkunci karena melewati batas waktu 2x24 jam.');
        }
        // Opsional: Pastikan hanya dokter yang membuat RM ini yang bisa mengupdate
        // if ($medicalRecord->doctor_id !== Auth::id()) {
        //     abort(403, 'Anda tidak diizinkan memperbarui rekam medis ini.');
        // }

        $request->validate([
            'patient_id' => 'required|exists:patients,id', // Tidak diubah, tapi tetap divalidasi
            'visit_date' => 'required|date',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $medicalRecord->update($request->all());

        // Update queue status to completed jika ada queue yang terkait dengan rekam medis ini
        $queue = \App\Models\Queue::where('medical_record_id', $medicalRecord->id)->first();
        if ($queue && $queue->status !== 'completed') {
            $queue->status = 'completed';
            $queue->save();
        }

        return redirect()->route('dokter.queues.index')->with('success', 'Rekam medis berhasil diperbarui dan pemeriksaan ditandai selesai!');
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
}