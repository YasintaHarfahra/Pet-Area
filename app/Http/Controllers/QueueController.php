<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QueueController extends Controller
{
    // Tampilkan daftar antrian untuk admin
    public function adminIndex()
    {
        $queues = Queue::with('patient')
            ->whereDate('queue_date', today())
            ->orderBy('queue_number')
            ->get();

        return view('admin.queues.index', compact('queues'));
    }

    // Tampilkan daftar antrian untuk dokter
    public function doctorIndex()
    {
        $queues = Queue::with('patient', 'medicalRecord')
            ->whereDate('queue_date', today())
            ->orderBy('queue_number')
            ->get();

        return view('dokter.queues.index', compact('queues'));
    }

    // Tampilkan form tambah antrian
    public function create()
    {
        $patients = Patient::all();
        return view('admin.queues.create', compact('patients'));
    }

    // Simpan antrian baru
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'complaint' => 'nullable|string',
            'queue_date' => 'required|date'
        ]);

        $queue = new Queue([
            'patient_id' => $request->patient_id,
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
                    'id' => $queue->id,
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
        return view('admin.queues.show', compact('queue'));
    }

    // Ambil data antrian via API (untuk refresh otomatis)
    public function getQueueData()
    {
        $queues = Queue::with('patient')
            ->whereDate('queue_date', today())
            ->orderBy('queue_number')
            ->get();

        return response()->json($queues);
    }
}