<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Pet;
use App\Models\Treatment;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RiwayatController extends Controller
{
    /**
     * Hapus rekam medis
     */
    public function destroy(MedicalRecord $record)
    {
        $record->delete();
        return redirect()->route('dokter.riwayat.index')->with('success', 'Rekam medis berhasil dihapus.');
    }

    /**
     * TAMPILAN DAFTAR RIWAYAT REKAM MEDIS
     */
    public function index(Request $request)
    {
        // === MODE PASIEN: Cari hewan berdasarkan nama/pemilik/telepon ===
        if ($request->get('mode') === 'pasien') {
            $search = $request->input('search');
            $pets = collect(); // default kosong

            if ($search) {
                // withTrashed agar hewan yang sudah dihapus tetap muncul selama punya rekam medis
                $pets = Pet::withTrashed()
                    ->with(['owner' => fn($q) => $q->withTrashed()])
                    ->withCount('medicalRecords')
                    ->has('medicalRecords') // hanya tampilkan pet yang punya riwayat
                    ->where(function ($q) use ($search) {
                        $q->where('nama_hewan', 'LIKE', "%{$search}%")
                          ->orWhere('jenis', 'LIKE', "%{$search}%")
                          ->orWhereHas('owner', function ($o) use ($search) {
                              $o->withTrashed()
                                ->where('nama', 'LIKE', "%{$search}%")
                                ->orWhere('no_hp', 'LIKE', "%{$search}%");
                          });
                    })
                    ->orderBy('nama_hewan')
                    ->get();
            }

            return view('dokter.riwayat.index', compact('pets', 'search'));
        }

        // === MODE DEFAULT: Daftar rekam medis ===
        $query = MedicalRecord::with(['pet' => fn($q) => $q->withTrashed(), 'pet.owner' => fn($q) => $q->withTrashed(), 'doctor']);

        // Filter by nama hewan (termasuk yang sudah soft-deleted)
        if ($request->filled('nama_hewan')) {
            $query->whereHas('pet', function ($q) use ($request) {
                $q->withTrashed()->where('nama_hewan', 'LIKE', '%' . $request->nama_hewan . '%');
            });
        }
        // Filter by nama pemilik (termasuk yang sudah soft-deleted)
        if ($request->filled('nama_pemilik')) {
            $query->whereHas('pet', function ($q) use ($request) {
                $q->withTrashed()->whereHas('owner', function ($o) use ($request) {
                    $o->withTrashed()->where('nama', 'LIKE', '%' . $request->nama_pemilik . '%');
                });
            });
        }
        // Filter by tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('visit_date', $request->tanggal);
        }
        // Sort
        $sort = $request->input('sort', 'desc');
        $sortField = $request->input('sort_field', 'visit_date');
        $query->orderBy($sortField, $sort);

        $medicalRecords = $query->paginate(15)->appends($request->all());
        return view('dokter.riwayat.index', compact('medicalRecords'));
    }

    /**
     * Edit rekam medis
     */
    public function edit(MedicalRecord $record)
    {
        $record->load(['pet' => fn($q) => $q->withTrashed(), 'pet.owner' => fn($q) => $q->withTrashed(), 'prescriptionItems.medicine', 'referral', 'treatments']);

        $tindakans = cache()->remember('active_treatments', 300, fn() => Treatment::active()->orderBy('kategori')->orderBy('nama_tindakan')->get());
        // Stok obat harus real-time agar sinkron dengan halaman admin dan form pemeriksaan.
        $medicines = Medicine::active()->orderBy('nama_obat')->get();

        return view('dokter.riwayat.edit', compact('record', 'tindakans', 'medicines'));
    }

    /**
     * Update rekam medis
     */
    public function update(Request $request, MedicalRecord $record)
    {
        $request->validate([
            'symptoms' => 'nullable|string',
            'diagnosis' => 'required|string',
            'tindakan_id' => 'nullable|array',
            'prescriptions' => 'nullable|array',
            'prescriptions.*.id_medicine' => 'nullable|exists:medicines,id_medicine',
            'prescriptions.*.jumlah' => 'nullable|integer|min:1',
            'prescriptions.*.instruksi' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $record) {
            $requestedPrescriptions = collect($request->input('prescriptions', []))
                ->filter(fn ($item) => !empty($item['id_medicine']) && !empty($item['jumlah']))
                ->map(function ($item) {
                    return [
                        'id_medicine' => (int) $item['id_medicine'],
                        'jumlah' => (int) $item['jumlah'],
                        'instruksi' => $item['instruksi'] ?? null,
                    ];
                })
                ->values();

            $requestedQtyByMedicine = $requestedPrescriptions
                ->groupBy('id_medicine')
                ->map(fn ($items) => (int) $items->sum('jumlah'));

            $allMedicineIds = $requestedQtyByMedicine->keys()->values();

            $lockedMedicines = collect();
            if ($allMedicineIds->isNotEmpty()) {
                $lockedMedicines = Medicine::whereIn('id_medicine', $allMedicineIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id_medicine');
            }

            foreach ($requestedQtyByMedicine as $medicineId => $requestedQty) {
                $medicine = $lockedMedicines->get($medicineId);
                if (!$medicine) {
                    throw ValidationException::withMessages([
                        'prescriptions' => 'Obat yang dipilih tidak ditemukan.',
                    ]);
                }

                // Validasi ketat: jumlah resep tidak boleh melebihi stok yang tersedia saat ini.
                $availableForEdit = (int) $medicine->stok;
                if ($requestedQty > $availableForEdit) {
                    throw ValidationException::withMessages([
                        'prescriptions' => "Stok {$medicine->nama_obat} tidak cukup. Tersedia {$availableForEdit}, diminta {$requestedQty}.",
                    ]);
                }
            }

            // Update ke kedua kolom agar riwayat pasti tersimpan di sistem lama & baru
            $record->update([
                'berat_badan' => $request->berat_badan,
                'suhu_tubuh'  => $request->suhu_tubuh,
                'keluhan'     => $request->symptoms ?? $record->keluhan,
                'symptoms'    => $request->symptoms ?? $record->symptoms,
                'diagnosa'    => $request->diagnosis ?? $record->diagnosa,
                'diagnosis'   => $request->diagnosis ?? $record->diagnosis,
                'next_control_date' => $request->next_control_date ?? $record->next_control_date,
                'follow_up_notes' => $request->follow_up_notes ?? $record->follow_up_notes,
            ]);

            // Sinkronisasi Tabel Pivot Tindakan (dengan snapshot harga)
            $biayaTindakan = 0;
            $tindakanIds = $request->tindakan_id ?? [];
            if (!empty($tindakanIds)) {
                $tindakanList = Treatment::whereIn('id_treatment', $tindakanIds)->get();
                $syncData = [];
                foreach ($tindakanList as $tindakan) {
                    $syncData[$tindakan->id_treatment] = ['harga' => $tindakan->harga];
                    $biayaTindakan += $tindakan->harga;
                }
                $record->treatments()->sync($syncData);
            } else {
                $record->treatments()->sync([]);
            }

            // Update rujukan
            if ($request->filled('tujuan_rujukan')) {
                $record->referral()->updateOrCreate(
                    ['id_medical_record' => $record->id_medical_record],
                    ['tujuan_rujukan' => $request->tujuan_rujukan, 'alasan_rujukan' => $request->alasan_rujukan]
                );
            } else {
                if ($record->referral) {
                    $record->referral()->delete();
                }
            }

            // Hapus item lama TANPA restore stok supaya stok tidak bertambah saat edit.
            $record->prescriptionItems()->toBase()->delete();

            $biayaObat = 0;
            $prescriptionRows = [];
            foreach ($requestedPrescriptions as $item) {
                $medicine = $lockedMedicines->get($item['id_medicine']);
                $hargaSatuan = $medicine ? $medicine->harga : 0;
                $subtotal = $hargaSatuan * $item['jumlah'];

                $prescriptionRows[] = [
                    'id_medical_record' => $record->id_medical_record,
                    'id_medicine' => $item['id_medicine'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                    'instruksi' => $item['instruksi'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $biayaObat += $subtotal;
            }

            if (!empty($prescriptionRows)) {
                DB::table('prescription_items')->insert($prescriptionRows);
            }

            // Kurangi stok dari stok saat ini sesuai jumlah resep yang baru disimpan.
            foreach ($requestedQtyByMedicine as $medicineId => $requestedQty) {
                $medicine = $lockedMedicines->get($medicineId);
                if ($medicine) {
                    if (!$medicine->decreaseStock((int) $requestedQty)) {
                        throw ValidationException::withMessages([
                            'prescriptions' => "Stok {$medicine->nama_obat} tidak cukup untuk disimpan.",
                        ]);
                    }
                }
            }

            // Update biaya
            $record->biaya_tindakan = $biayaTindakan;
            $record->biaya_obat = $biayaObat;
            $record->total_biaya = ($record->biaya_pemeriksaan ?? 0) + $biayaTindakan + $biayaObat;
            $record->save();

            // Update transaksi terkait agar biaya selalu sinkron
            $transaction = \App\Models\Transaction::where('id_medical_record', $record->id_medical_record)->first();
            if ($transaction) {
                $transaction->update([
                    'biaya_tindakan' => $biayaTindakan,
                    'biaya_obat' => $biayaObat,
                    'total_biaya' => $record->total_biaya,
                ]);
            }
        });

        // Redirect ke daftar rekam medis setelah update
        return redirect()->route('dokter.riwayat.index')->with('success', 'Data diperbarui.');
    }

    /**
     * Tampilkan detail rekam medis
     */
    public function show(MedicalRecord $record)
    {
        $record->load(['pet' => fn($q) => $q->withTrashed(), 'pet.owner' => fn($q) => $q->withTrashed(), 'doctor', 'prescriptionItems.medicine', 'treatments', 'referral']);
        return view('dokter.riwayat.record', compact('record'));
    }

    /**
     * Tampilkan riwayat per pasien (by pet id)
     */
    public function showByPet($petId)
    {
        $pet = Pet::withTrashed()->with(['owner' => fn($q) => $q->withTrashed()])->findOrFail($petId);
        $medicalRecords = MedicalRecord::with(['doctor', 'treatments', 'prescriptionItems.medicine'])
            ->where('id_pet', $pet->id_pet)
            ->orderByDesc('visit_date')
            ->get();

        return view('dokter.riwayat.pet_history', compact('pet', 'medicalRecords'));
    }
}
