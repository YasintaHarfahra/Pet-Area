<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AntrianController extends Controller
{
    /**
     * Tampilkan daftar antrian hari ini.
     * Menu Utama Dokter - Antrian Pemeriksaan
     */
    public function index()
    {
        // Satu query untuk semua antrian hari ini, lalu partisi di PHP
        $allQueues = Queue::with(['pet.owner', 'medicalRecord'])
            ->whereDate('queue_date', today())
            ->orderBy('queue_number', 'asc')
            ->get();

        $waitingQueues = $allQueues->where('status', 'waiting');
        $inProgressQueues = $allQueues->where('status', 'in-progress');
        $completedQueues = $allQueues->where('status', 'completed');

        return view('dokter.antrian.index', compact('waitingQueues', 'inProgressQueues', 'completedQueues'));
    }

    /**
     * Mulai pemeriksaan dari antrian.
     * Ubah status antrian menjadi 'in-progress' dan redirect ke form pemeriksaan.
     */
    public function startExamination(Queue $queue)
    {
        // Validasi: hanya antrian dengan status 'waiting' yang bisa dimulai
        if ($queue->status !== 'waiting') {
            return back()->with('error', 'Antrian ini tidak dalam status menunggu.');
        }

        // Update status queue menjadi in-progress
        $queue->update([
            'status' => 'in-progress',
            'id_doctor' => Auth::id(), // Set dokter yang menangani
        ]);

        // Redirect ke halaman form pemeriksaan setelah memulai pemeriksaan
        // Kirim queue_id agar form pemeriksaan tahu pasien yang sedang diperiksa
        return redirect()->route('dokter.examinations.create', ['queue' => $queue->id_queue])->with('success', 'Memulai pemeriksaan untuk ' . $queue->pet->nama_hewan);
    }

    /**
     * Batalkan/lewati antrian.
     */
    public function skip(Queue $queue)
    {
        if ($queue->status !== 'waiting') {
            return back()->with('error', 'Hanya antrian dengan status menunggu yang dapat dilewati.');
        }

        $queue->update([
            'status' => 'cancelled',
            'notes' => ($queue->notes ? $queue->notes . ' | ' : '') . 'Dilewati oleh dokter pada ' . now()->format('d/m/Y H:i'),
        ]);

        return back()->with('success', 'Antrian berhasil dilewati.');
    }

    /**
     * Lihat detail antrian (summary info sebelum mulai periksa).
     */
    public function show(Queue $queue)
    {
        $queue->load(['pet.owner', 'pet.medicalRecords' => function($query) {
            $query->latest()->take(5);
        }]);

        return view('dokter.antrian.show', compact('queue'));
    }

    /**
     * API: Polling data antrian untuk auto-refresh (JSON)
     */
    public function pollData()
    {
        $allQueues = Queue::with(['pet.owner', 'medicalRecord'])
            ->whereDate('queue_date', today())
            ->orderBy('queue_number', 'asc')
            ->get();

        $waitingQueues = $allQueues->where('status', 'waiting')->values();
        $inProgressQueues = $allQueues->where('status', 'in-progress')->values();
        $completedQueues = $allQueues->where('status', 'completed')->values();

        return response()->json([
            'waiting_count' => $waitingQueues->count(),
            'in_progress_count' => $inProgressQueues->count(),
            'completed_count' => $completedQueues->count(),
            'html' => view('dokter.antrian._queue_list', compact('waitingQueues', 'inProgressQueues'))->render(),
        ]);
    }
}
