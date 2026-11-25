<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\Pet;
use App\Models\Owner;
// use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Controller untuk Admin - Manajemen Antrian
 */
class QueueController extends Controller
{
    /**
     * Tampilkan form tambah antrian baru
     */
    public function create(Request $request)
    {
        $date = $request->input('date', today()->format('Y-m-d'));

        return redirect()
            ->route('admin.registration.owners.index', ['tab' => 'owners', 'queue_date' => $date])
            ->with('success', 'Silakan pilih pemilik dan hewan untuk didaftarkan ke antrian.');
    }
    
    /**
     * Tampilkan daftar antrian hari ini
     */
    public function index(Request $request)
    {
        $date = $request->input('date', today()->format('Y-m-d'));
        $today = today()->format('Y-m-d');
        if ($date < $today) {
            return redirect()->route('admin.queues.index', ['date' => $today]);
        }
        $queues = Queue::with(['pet.owner', 'doctor'])
            ->whereDate('queue_date', $date)
            ->orderByRaw("CASE 
                WHEN status = 'waiting' THEN 1 
                WHEN status = 'in-progress' THEN 2 
                WHEN status = 'completed' THEN 3 
                WHEN status = 'cancelled' THEN 4 
                ELSE 5 
            END")
            ->orderBy('queue_number')
            ->get();
        return view('admin.queues.index', compact('queues', 'date'));
    }

    /**
     * Simpan antrian baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id_pet',
            'queue_date' => 'required|date|after_or_equal:today',
            'complaint' => 'nullable|string|max:500',
        ], [
            'pet_id.required' => 'Pilih hewan yang akan didaftarkan',
            'pet_id.exists' => 'Hewan tidak ditemukan',
            'queue_date.required' => 'Tanggal kunjungan wajib diisi',
            'queue_date.after_or_equal' => 'Tanggal kunjungan tidak boleh sebelum hari ini'
        ]);

        // Cek apakah hewan sudah ada di antrian hari yang sama
        $existingQueue = Queue::where('id_pet', $validated['pet_id'])
            ->whereDate('queue_date', $validated['queue_date'])
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->first();

        if ($existingQueue) {
            return back()
                ->withInput()
                ->withErrors(['pet_id' => 'Hewan ini sudah terdaftar di antrian pada tanggal tersebut.']);
        }

        // Ambil data Pet dan Owner
        $pet = Pet::with('owner')->findOrFail($validated['pet_id']);
        if ($pet->status_hidup === Pet::STATUS_MATI) {
            return back()
                ->withInput()
                ->withErrors(['pet_id' => 'Hewan dengan status meninggal tidak dapat didaftarkan ke antrian.']);
        }

        // LOGIKA NOMOR ANTRIAN OTOMATIS
        $lastQueue = Queue::whereDate('queue_date', $validated['queue_date'])
            ->orderByDesc('queue_number')
            ->first();

        $queueNumber = $lastQueue ? $lastQueue->queue_number + 1 : 1;

        $queue = Queue::create([
            'id_pet' => $validated['pet_id'],
            'queue_date' => $validated['queue_date'],
            'complaint' => $validated['complaint'],
            'queue_number' => $queueNumber,
            'status' => 'waiting',
        ]);

        // Format nomor antrian untuk tampilan (opsional)
        $queueNumberDisplay = 'Q-' . date('Ymd', strtotime($validated['queue_date'])) . '-' . str_pad($queueNumber, 3, '0', STR_PAD_LEFT);

        return redirect()
            ->route('admin.queues.index', ['date' => $validated['queue_date']])
            ->with('success', "Berhasil mendaftarkan {$pet->nama_hewan} ke antrian nomor {$queueNumberDisplay}");
    }

    /**
     * Detail antrian
     */
    public function show(Queue $queue)
    {
        $queue->load(['pet.owner', 'pet.medicalRecords' => function($q) {
              $q->latest('visit_date')->limit(5);
        }, 'doctor', 'medicalRecord']);

        return view('admin.queues.show', compact('queue'));
    }

    /**
     * Batalkan antrian
     */
    public function cancel(Queue $queue)
    {
        if ($queue->status === 'completed') {
            return back()->withErrors(['error' => 'Antrian yang sudah selesai tidak dapat dibatalkan.']);
        }

        if ($queue->status === 'in-progress') {
            return back()->withErrors(['error' => 'Antrian yang sedang diperiksa tidak dapat dibatalkan.']);
        }

        $queue->update(['status' => 'cancelled']);

        return back()->with('success', 'Antrian berhasil dibatalkan.');
    }

    /**
     * API: Cari hewan untuk antrian
     */
    public function searchPets(Request $request)
    {
        $search = $request->get('q', '');

        $pets = Pet::with('owner')
            ->alive()
            ->where(function($query) use ($search) {
                $query->where('nama_hewan', 'like', "%{$search}%")
                    ->orWhere('jenis', 'like', "%{$search}%")
                    ->orWhereHas('owner', function($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                          ->orWhere('no_hp', 'like', "%{$search}%");
                    });
            })
            ->limit(10)
            ->get()
            ->map(function($pet) {
                return [
                    'id' => $pet->id_pet,
                    'nama_hewan' => $pet->nama_hewan,
                    'jenis' => $pet->jenis,
                    'ras' => $pet->ras,
                    'foto_hewan_url' => $pet->foto_hewan_url,
                    'owner_nama' => $pet->owner->nama ?? '-',
                    'owner_hp' => $pet->owner->no_hp ?? '-',
                    'text' => "{$pet->nama_hewan} ({$pet->jenis}) - {$pet->owner->nama}",
                ];
            });

        return response()->json($pets);
    }

    /**
     * API: Polling data antrian untuk auto-refresh (JSON)
     */
    public function pollData(Request $request)
    {
        $date = $request->input('date', today()->format('Y-m-d'));
        $queues = Queue::with(['pet.owner', 'doctor'])
            ->whereDate('queue_date', $date)
            ->orderByRaw("CASE 
                WHEN status = 'waiting' THEN 1 
                WHEN status = 'in-progress' THEN 2 
                WHEN status = 'completed' THEN 3 
                WHEN status = 'cancelled' THEN 4 
                ELSE 5 
            END")
            ->orderBy('queue_number')
            ->get();

        return response()->json([
            'total' => $queues->count(),
            'waiting' => $queues->where('status', 'waiting')->count(),
            'in_progress' => $queues->where('status', 'in-progress')->count(),
            'completed' => $queues->where('status', 'completed')->count(),
            'html' => view('admin.queues._queue_table', compact('queues'))->render(),
        ]);
    }
}
