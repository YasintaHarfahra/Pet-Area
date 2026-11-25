<?php
/**
 * =====================================================
 * CONTROLLER: OWNER CONTROLLER
 * =====================================================
 * 
 * Controller ini menangani semua operasi terkait PEMILIK HEWAN:
 * - Daftar pemilik dengan pencarian
 * - Detail pemilik (beserta daftar hewan)
 * - CRUD pemilik
 * 
 * Fitur utama:
 * - Pencarian pemilik berdasarkan nama/no_hp
 * - Menampilkan hierarki: Pemilik → Hewan → Rekam Medis
 * =====================================================
 */

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Animal;
use App\Models\AnimalMedicalRecord;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    /**
     * =========================================
     * INDEX: Daftar Pemilik + Pencarian
     * =========================================
     * 
     * Menampilkan daftar semua pemilik dengan fitur:
     * - Pencarian berdasarkan nama atau no_hp
     * - Pagination
     * - Jumlah hewan per pemilik
     * 
     * URL: GET /owners
     * Route: owners.index
     */
    public function index(Request $request)
    {
        // Query dasar dengan eager loading untuk menghitung jumlah hewan
        $query = Owner::withCount('animals');

        // Jika ada keyword pencarian
        if ($request->filled('search')) {
            $keyword = $request->search;
            
            // Gunakan scope search yang sudah dibuat di model
            $query->search($keyword);
        }

        // Urutkan: terbaru dulu
        $query->orderBy('created_at', 'desc');

        // Pagination: 15 data per halaman
        $owners = $query->paginate(15)->withQueryString();

        // Kirim ke view
        return view('owners.index', compact('owners'));
    }

    /**
     * =========================================
     * CREATE: Form Tambah Pemilik Baru
     * =========================================
     * 
     * URL: GET /owners/create
     * Route: owners.create
     */
    public function create()
    {
        return view('owners.create');
    }

    /**
     * =========================================
     * STORE: Simpan Pemilik Baru
     * =========================================
     * 
     * Menyimpan data pemilik baru ke database.
     * owner_code akan di-generate otomatis oleh model.
     * 
     * URL: POST /owners
     * Route: owners.store
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'no_hp'   => 'required|string|max:20',
            'alamat'  => 'nullable|string',
            'email'   => 'nullable|email|max:255',
            'no_ktp'  => 'nullable|string|max:20',
            'catatan' => 'nullable|string',
        ]);

        // Simpan ke database (owner_code auto-generate)
        $owner = Owner::create($validated);

        // Redirect ke halaman detail dengan pesan sukses
        return redirect()
            ->route('owners.show', $owner->id_owner)
            ->with('success', 'Pemilik berhasil ditambahkan dengan kode: ' . $owner->owner_code);
    }

    /**
     * =========================================
     * SHOW: Detail Pemilik + Daftar Hewan
     * =========================================
     * 
     * Menampilkan detail lengkap pemilik beserta:
     * - Semua hewan miliknya
     * - Statistik rekam medis per hewan
     * 
     * URL: GET /owners/{owner}
     * Route: owners.show
     */
    public function show(Owner $owner)
    {
        // Eager load hewan dengan jumlah rekam medis
        $owner->load(['animals' => function ($query) {
            $query->withCount('medicalRecords')
                  ->with('latestMedicalRecord')
                  ->orderBy('nama_hewan');
        }]);

        // Ambil statistik
        $stats = [
            'total_hewan'       => $owner->animals->count(),
            'hewan_aktif'       => $owner->animals->where('is_active', true)->count(),
            'total_rekam_medis' => $owner->medicalRecords()->count(),
            'perlu_kontrol'     => $owner->medicalRecords()
                                         ->whereNotNull('tanggal_kontrol')
                                         ->where('tanggal_kontrol', '<=', now())
                                         ->count(),
        ];

        return view('owners.show', compact('owner', 'stats'));
    }

    /**
     * =========================================
     * EDIT: Form Edit Pemilik
     * =========================================
     * 
     * URL: GET /owners/{owner}/edit
     * Route: owners.edit
     */
    public function edit(Owner $owner)
    {
        return view('owners.edit', compact('owner'));
    }

    /**
     * =========================================
     * UPDATE: Update Data Pemilik
     * =========================================
     * 
     * URL: PUT /owners/{owner}
     * Route: owners.update
     */
    public function update(Request $request, Owner $owner)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'no_hp'   => 'required|string|max:20',
            'alamat'  => 'nullable|string',
            'email'   => 'nullable|email|max:255',
            'no_ktp'  => 'nullable|string|max:20',
            'catatan' => 'nullable|string',
        ]);

        $owner->update($validated);

        return redirect()
            ->route('owners.show', $owner->id_owner)
            ->with('success', 'Data pemilik berhasil diperbarui!');
    }

    /**
     * =========================================
     * DESTROY: Hapus Pemilik
     * =========================================
     * 
     * PERHATIAN: Menggunakan soft delete - data tetap ada di database.
     * Rekam medis dan data hewan tidak terpengaruh.
     * 
     * URL: DELETE /owners/{owner}
     * Route: owners.destroy
     */
    public function destroy(Owner $owner)
    {
        $ownerName = $owner->nama;
        $owner->delete();

        return redirect()
            ->route('owners.index')
            ->with('success', "Pemilik '{$ownerName}' berhasil dihapus.");
    }

    /**
     * =========================================
     * SEARCH API: Pencarian AJAX
     * =========================================
     * 
     * Endpoint untuk pencarian real-time (autocomplete)
     * Mengembalikan JSON untuk digunakan di frontend
     * 
     * URL: GET /api/owners/search
     */
    public function searchApi(Request $request)
    {
        $keyword = $request->get('q', '');

        if (strlen($keyword) < 2) {
            return response()->json([]);
        }

        $owners = Owner::search($keyword)
            ->withCount('animals')
            ->limit(10)
            ->get()
            ->map(function ($owner) {
                return [
                    'id'            => $owner->id_owner,
                    'owner_code'    => $owner->owner_code,
                    'nama'          => $owner->nama,
                    'no_hp'         => $owner->no_hp,
                    'alamat'        => $owner->alamat,
                    'animals_count' => $owner->animals_count,
                ];
            });

        return response()->json($owners);
    }
}
