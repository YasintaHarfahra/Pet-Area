<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // Pastikan ini ada
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Treatment;
use Illuminate\Support\Facades\Log;
use App\Models\MedicalRecordDraft;
use App\Models\Referral;


class ExaminationController extends Controller
{
    /**
     * SIMPAN DATA PEMERIKSAAN BARU
     */
    public function store(Request $request)
    {
        $doctorId = Auth::guard('dokter')->id() ?? Auth::id();
        if (!$doctorId) {
            return back()
                ->withInput()
                ->with('error', 'Sesi dokter tidak valid. Silakan login ulang.');
        }

        // Validasi data — field names harus cocok dengan form blade
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id_pet',
            'queue' => 'nullable|exists:queues,id_queue',
            'tgl_periksa' => 'required|date_format:Y-m-d H:i:s',
            'keluhan' => 'nullable|string',
            'diagnosa' => 'nullable|string',
            'treatments' => 'nullable|array',
            'treatments.*' => 'exists:treatments,id_treatment',
            'prescriptions' => 'nullable|array',
            'prescriptions.*.id_medicine' => 'nullable|exists:medicines,id_medicine',
            'prescriptions.*.jumlah' => 'nullable|numeric|min:1',
            'prescriptions.*.instruksi' => 'nullable|string',
            'berat_badan' => 'nullable|numeric',
            'suhu_tubuh' => 'nullable|numeric',
            'biaya_pemeriksaan' => 'nullable|numeric|min:0',
            'tgl_kontrol_berikutnya' => 'nullable|date',
            'catatan_kontrol' => 'nullable|string',
            'buat_rujukan' => 'nullable',
            'tujuan_rujukan' => 'nullable|string',
            'alasan_rujukan' => 'nullable|string',
        ]);

        $prescriptions = $validated['prescriptions'] ?? [];
        if (!empty($prescriptions)) {
            $requestedMedicines = collect($prescriptions)
                ->filter(fn ($item) => !empty($item['id_medicine']) && !empty($item['jumlah']))
                ->groupBy('id_medicine')
                ->map(fn ($items) => (int) $items->sum('jumlah'));

            $medicineIds = $requestedMedicines->keys()->all();
            $currentStocks = Medicine::whereIn('id_medicine', $medicineIds)
                ->get()
                ->keyBy('id_medicine');

            foreach ($requestedMedicines as $medicineId => $requestedQty) {
                $medicine = $currentStocks->get($medicineId);
                if (!$medicine) {
                    return back()
                        ->withInput()
                        ->with('error', 'Obat yang dipilih tidak ditemukan.');
                }

                if ($requestedQty > (int) $medicine->stok) {
                    return back()
                        ->withInput()
                        ->with('error', "Stok {$medicine->nama_obat} tidak cukup. Tersedia {$medicine->stok}, diminta {$requestedQty}.");
                }
            }
        }

        DB::transaction(function () use ($validated, $request, &$medicalRecord, &$biayaTindakan, &$biayaObat, &$biayaPemeriksaan, &$totalBiaya) {
            // Hapus draft jika ada
            if (!empty($validated['queue'])) {
                MedicalRecordDraft::where('id_queue', $validated['queue'])
                    ->where('id_doctor', Auth::guard('dokter')->id() ?? Auth::id())
                    ->delete();
            }

            // Simpan MedicalRecord
            $medicalRecord = MedicalRecord::create([
                'id_pet' => $validated['pet_id'],
                'id_doctor' => Auth::guard('dokter')->id() ?? Auth::id(),
                'id_queue' => $validated['queue'] ?? null,
                'visit_date' => $validated['tgl_periksa'],
                'symptoms' => $validated['keluhan'] ?? null,
                'diagnosis' => $validated['diagnosa'] ?? null,
                'next_control_date' => $validated['tgl_kontrol_berikutnya'] ?? null,
                'follow_up_notes' => $validated['catatan_kontrol'] ?? null,
                'berat_badan' => $validated['berat_badan'] ?? null,
                'suhu_tubuh' => $validated['suhu_tubuh'] ?? null,
            ]);

            // Simpan tindakan (pivot) & hitung total biaya tindakan
            $biayaTindakan = 0;
            if (!empty($validated['treatments'])) {
                $tindakanList = \App\Models\Treatment::whereIn('id_treatment', $validated['treatments'])->get();
                $syncData = [];
                foreach ($tindakanList as $tindakan) {
                    $syncData[$tindakan->id_treatment] = ['harga' => $tindakan->harga];
                    $biayaTindakan += $tindakan->harga;
                }
                $medicalRecord->treatments()->sync($syncData);
            }

            // Simpan resep obat (prescription items) & kurangi stok, hitung total biaya obat
            $biayaObat = 0;
            if (!empty($validated['prescriptions'])) {
                $medicineIds = collect($validated['prescriptions'])->pluck('id_medicine')->filter()->unique()->values()->all();
                $medicinesMap = Medicine::whereIn('id_medicine', $medicineIds)->get()->keyBy('id_medicine');

                foreach ($validated['prescriptions'] as $item) {
                    if (!empty($item['id_medicine']) && !empty($item['jumlah'])) {
                        $medicine = $medicinesMap[$item['id_medicine']] ?? null;
                        $hargaSatuan = $medicine ? $medicine->harga : 0;
                        $jumlah = (int) $item['jumlah'];
                        $subtotal = $hargaSatuan * $jumlah;
                        $medicalRecord->prescriptionItems()->create([
                            'id_medicine' => $item['id_medicine'],
                            'jumlah' => $jumlah,
                            'harga_satuan' => $hargaSatuan,
                            'subtotal' => $subtotal,
                            'instruksi' => $item['instruksi'] ?? null,
                        ]);
                        $biayaObat += $subtotal;
                    }
                }
            }

            // Update status antrian ke completed jika ada
            if (!empty($validated['queue'])) {
                $queue = \App\Models\Queue::find($validated['queue']);
                if ($queue) {
                    $queue->status = 'completed';
                    $queue->save();
                }
            }

            $biayaPemeriksaan = $validated['biaya_pemeriksaan'] ?? 0;
            $totalBiaya = $biayaPemeriksaan + $biayaTindakan + $biayaObat;

            $medicalRecord->biaya_pemeriksaan = $biayaPemeriksaan;
            $medicalRecord->biaya_tindakan = $biayaTindakan;
            $medicalRecord->biaya_obat = $biayaObat;
            $medicalRecord->total_biaya = $totalBiaya;
            $medicalRecord->save();

            \App\Models\Transaction::create([
                'id_medical_record' => $medicalRecord->id_medical_record,
                'id_pet' => $medicalRecord->id_pet,
                'id_doctor' => $medicalRecord->id_doctor,
                'total_biaya' => $totalBiaya,
                'biaya_pemeriksaan' => $biayaPemeriksaan,
                'biaya_tindakan' => $biayaTindakan,
                'biaya_obat' => $biayaObat,
                'status_bayar' => 'unpaid',
            ]);

            if ($request->filled('buat_rujukan') && $request->input('tujuan_rujukan')) {
                $doctorId = $medicalRecord->id_doctor ?? (Auth::guard('dokter')->id() ?? Auth::id());
                Referral::create([
                    'id_medical_record' => $medicalRecord->id_medical_record,
                    'id_doctor' => $doctorId,
                    'tujuan_rujukan' => $request->input('tujuan_rujukan'),
                    'alasan_rujukan' => $request->input('alasan_rujukan'),
                    'tanggal_rujukan' => now(),
                    'status_cetak' => 'pending',
                ]);
            }
        });

        // Redirect ke halaman antrian pasien setelah simpan
        return redirect()->route('dokter.antrian.index')->with('success', 'Pemeriksaan berhasil disimpan.');
    }

    /**
     * TAMPILAN FORM BUAT PEMERIKSAAN BARU
     */
    public function create(Request $request)
    {
        $doctorId = Auth::guard('dokter')->id() ?? Auth::id();

        $petId = $request->input('pet_id');
        $queueId = $request->input('queue');
        $petData = null;
        $queueData = null;
        $draftData = null;
        $historyRecords = collect();
        if ($petId) {
            $petData = \App\Models\Pet::with('owner')->find($petId);
            // Ambil riwayat pemeriksaan berdasarkan pet_id saja (kolom patient_id sudah tidak ada)
            $historyRecords = \App\Models\MedicalRecord::with(['prescriptionItems.medicine', 'treatments'])
                ->where('id_pet', $petId)
                ->orderByDesc('visit_date')
                ->orderByDesc('visit_date')
                ->limit(5)
                ->get();
        }
        if ($queueId) {
            $queueData = \App\Models\Queue::with('pet.owner')->find($queueId);
            // Cek draft pemeriksaan
            $draftData = \App\Models\MedicalRecordDraft::where('id_queue', $queueId)
                ->where('id_doctor', $doctorId)
                ->where('status', 'draft')
                ->first();
            // Jika belum ada $petId, ambil dari queue
            if (!$petId && $queueData && $queueData->id_pet) {
                $petData = $queueData->pet;
                $historyRecords = \App\Models\MedicalRecord::with(['prescriptionItems.medicine', 'treatments'])
                    ->where('id_pet', $queueData->id_pet)
                    ->orderByDesc('visit_date')
                    ->limit(5)
                    ->get();
            }
        }
        $medicines = \App\Models\Medicine::orderBy('nama_obat')->get();
        $treatments = cache()->remember('active_treatments', 300, fn() => \App\Models\Treatment::active()->orderBy('kategori')->orderBy('nama_tindakan')->get());
        return view('dokter.examinations.create', compact('petData', 'queueData', 'medicines', 'treatments', 'draftData', 'historyRecords'));
    }
    /**
     * TAMPILAN DAFTAR PEMERIKSAAN (Riwayat)
     */
    public function index(Request $request) 
    {
        $medicalRecords = MedicalRecord::with(['pet.owner', 'doctor'])
            ->orderByDesc('visit_date')
            ->paginate(15);

        // JIKA REQUEST AJAX: Kirim hanya potongan tabel
        if ($request->ajax()) {
            return view('dokter.examinations.table', compact('medicalRecords'))->render();
        }

        // JIKA REQUEST BIASA: Kirim halaman penuh
        return view('dokter.examinations.index', compact('medicalRecords'));
    }

    /**
     * TAMPILAN FORM EDIT PEMERIKSAAN
     */
    public function edit($examinationId)
    {
        $examination = \App\Models\MedicalRecord::with(['pet.owner', 'prescriptionItems.medicine'])
            ->findOrFail($examinationId);

        // Riwayat rekam medis pasien (kecuali yang sedang diedit)
        $historyRecords = \App\Models\MedicalRecord::with(['prescriptionItems.medicine'])
            ->where('id_pet', $examination->id_pet)
            ->where('id_medical_record', '!=', $examination->id_medical_record)
            ->orderByDesc('visit_date')
            ->limit(5)
            ->get();

        $medicines = \App\Models\Medicine::orderBy('nama_obat')->get();

        return view('dokter.examinations.edit', compact('examination', 'historyRecords', 'medicines'));
    }
}