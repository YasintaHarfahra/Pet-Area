<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\Queue;
use App\Models\MedicalRecord;

class MedicalRecordController extends Controller
{
    // Tampilkan form pemeriksaan baru
    // WARNING: Controller ini sudah tidak digunakan, migrasi ke ExaminationController
    public function create(Request $request)
    {
        // Controller ini deprecated, gunakan ExaminationController
        abort(404, 'Controller tidak digunakan, gunakan workflow baru.');
    }

    // Simpan data pemeriksaan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'queue_id' => 'nullable|exists:queues,id',
            'visit_date' => 'required|date',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
        ]);
        $medicalRecord = MedicalRecord::create($validated);
        return redirect()->route('dokter.antrian.index')->with('success', 'Pemeriksaan berhasil disimpan.');
    }
}
