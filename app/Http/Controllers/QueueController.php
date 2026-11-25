<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Pet;
use App\Models\MedicalRecordDraft;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller
{
    // Tampilkan daftar antrian untuk admin
    public function adminIndex()
    {
        $queues = Queue::with('patient')
            ->whereDate('queue_date', today())
            ->notCancelled()
            ->orderBy('queue_number')
            ->get();

        return view('admin.queues.index', compact('queues'));
    }

    // Tampilkan daftar antrian untuk dokter (dengan draft detection)
    public function doctorIndex()
    {
        $queues = Queue::with([
            'patient', 
            'medicalRecord',
            'medicalRecordDraft' => function ($query) {
                $query->whereIn('status', ['draft', 'in-progress']);
            },
            'doctor'
        ])
            ->whereDate('queue_date', today())
            ->notCancelled()
            ->orderByRaw("CASE 
                WHEN status = 'in-progress' THEN 1 
                WHEN status = 'waiting' THEN 2 
                ELSE 3 
            END")
            ->orderBy('queue_number')
            ->get();

        return view('dokter.queues.index', compact('queues'));
    }

    // Detail antrian untuk dokter (identitas + riwayat)
    public function doctorShow(Queue $queue)
    {
        $queue->load([
            'patient.medicalRecords' => function ($query) {
                $query->orderByDesc('visit_date');
            },
            'patient'
        ]);

        // Ubah status ke in-progress ketika dokter mulai pemeriksaan
        if ($queue->status === 'waiting') {
            $queue->status = 'in-progress';
            $queue->save();
        }

        $medicalRecords = $queue->patient->medicalRecords;

        return view('dokter.queues.show', compact('queue', 'medicalRecords'));
    }

    // Tampilkan form tambah antrian
    public function create()
    {
        $patients = Pet::all();
        return view('admin.queues.create', compact('patients'));
    }

    // AJAX: Cari pasien berdasarkan nama atau nomor rekam medis
    public function searchPatients(Request $request)
    {
        $search = $request->get('q', '');

        $query = Pet::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('medical_record_number', 'like', '%' . $search . '%');
            });
        }

        $patients = $query->orderBy('name')
            ->limit(20)
            ->get(['id_pet', 'name', 'medical_record_number', 'species', 'owner_name']);

        return response()->json($patients);
    }

    // Simpan antrian baru
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:pets,id_pet',
            'complaint' => 'nullable|string',
            'queue_date' => 'required|date'
        ]);

        $queue = new Queue([
            'id_pet' => $request->patient_id,
            'queue_date' => $request->queue_date,
            'complaint' => $request->complaint,
            'queue_number' => Queue::generateQueueNumber($request->queue_date),
            'status' => 'waiting'
        ]);

        $queue->save();

        return redirect()->route('admin.queues.index')
            ->with('success', 'Antrian berhasil ditambahkan.');
    }

    // Update status antrian
    public function updateStatus(Queue $queue, Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|in:waiting,in-progress,completed,cancelled'
            ]);

            $oldStatus = $queue->status;
            $queue->status = $request->status;
            $queue->save();

            return response()->json([
                'message' => 'Status antrian berhasil diupdate',
                'queue' => [
                    'id' => $queue->id_queue,
                    'old_status' => $oldStatus,
                    'new_status' => $queue->status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate status antrian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Batalkan antrian
    public function cancel(Queue $queue)
    {
        $queue->status = 'cancelled';
        $queue->save();

        return redirect()->back()
            ->with('success', 'Antrian dibatalkan.');
    }

    // Tampilkan detail antrian
    public function show(Queue $queue)
    {
        $queue->load('patient');
        return view('admin.queues.show', compact('queue'));
    }

    // Ambil data antrian via API (untuk refresh otomatis)
    public function getQueueData()
    {
        $queues = Queue::with('patient')
            ->whereDate('queue_date', today())
            ->notCancelled()
            ->orderBy('queue_number')
            ->get();

        return response()->json($queues);
    }
}