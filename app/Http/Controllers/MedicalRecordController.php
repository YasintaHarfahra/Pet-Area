<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient; // Perlu untuk form pilih pasien
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan dokter yang sedang login

class MedicalRecordController extends Controller
{
    // ADMIN: Menampilkan daftar semua rekam medis (Hanya Lihat)
    public function indexAdmin()
    {
        Gate::authorize('view-medical-records'); // Gate untuk admin melihat rekam medis
        $medicalRecords = MedicalRecord::with(['patient', 'doctor'])
                                       ->orderByDesc('visit_date')
                                       ->paginate(10);
        return view('admin.medical_records.index', compact('medicalRecords'));
    }

    // DOKTER: Menampilkan daftar rekam medis dengan tombol CRUD
    public function indexDoctor()
    {
        Gate::authorize('manage-medical-records'); // Gate untuk dokter mengelola rekam medis
        $medicalRecords = MedicalRecord::with(['patient', 'doctor'])
                                       ->orderByDesc('visit_date')
                                       ->paginate(10);
        return view('dokter.medical_records.index', compact('medicalRecords'));
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
            $patient = Patient::findOrFail($request->patient_id);
            $queue_id = $request->queue_id;
            
            // Auto update queue status to in-progress ketika membuka form dari queue waiting
            // Jika ada queue_id dan status masih waiting, berarti ini dari tab "Menunggu"
            if ($queue_id) {
                $queue = \App\Models\Queue::find($queue_id);
                if ($queue && $queue->status === 'waiting') {
                    $queue->status = 'in-progress';
                    $queue->save();
                }
            }
            
            return view('dokter.medical_records.create', compact('patient', 'queue_id'));
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