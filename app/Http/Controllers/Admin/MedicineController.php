<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

/**
 * Controller untuk Admin - Stok Obat
 */
class MedicineController extends Controller
{
    /**
     * Daftar obat
     */
    public function index(Request $request)
    {
        $query = Medicine::query();

        // Filter pencarian
        if ($search = $request->input('search')) {
            $query->search($search);
        }

        // Filter stok
        if ($request->input('stock') === 'low') {
            $query->where('stok', '>', 0)->whereColumn('stok', '<=', 'stok_minimum');
        } elseif ($request->input('stock') === 'out') {
            $query->where('stok', '<=', 0);
        }

        // Filter status aktif/nonaktif
        if ($request->input('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->input('status') === 'inactive') {
            $query->where('is_active', false);
        }

        // Sorting
        $sortField = $request->input('sort', 'nama_obat');
        $sortOrder = $request->input('order', 'asc');
        
        if (in_array($sortField, ['stok', 'nama_obat', 'harga'])) {
            $query->orderBy($sortField, $sortOrder === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('nama_obat', 'asc');
        }

        $medicines = $query->paginate(15)->withQueryString();

        // Stats (1 query gabungan, hanya obat yang belum di-soft-delete)
        $statsRaw = \Illuminate\Support\Facades\DB::selectOne("
            SELECT
                COUNT(*) as total,
                COUNT(CASE WHEN stok > 0 AND stok <= stok_minimum THEN 1 END) as low_stock,
                COUNT(CASE WHEN stok <= 0 THEN 1 END) as out_of_stock
            FROM medicines
            WHERE deleted_at IS NULL
        ");

        $stats = [
            'total' => $statsRaw->total ?? 0,
            'low_stock' => $statsRaw->low_stock ?? 0,
            'out_of_stock' => $statsRaw->out_of_stock ?? 0,
        ];

        return view('admin.medicines.index', compact('medicines', 'stats'));
    }

    /**
     * Form tambah obat
     */
    public function create()
    {
        return view('admin.medicines.create');
    }

    /**
     * Simpan obat baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:150',
            'dosis' => 'nullable|string|max:10',
            'satuan' => 'required|string|max:30',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['stok_minimum'] = $validated['stok_minimum'] ?? 10;

        Medicine::create($validated);

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Obat berhasil ditambahkan!');
    }

    /**
     * Form edit obat
     */
    public function edit(Medicine $medicine)
    {
        return view('admin.medicines.edit', compact('medicine'));
    }

    /**
     * Update obat
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:150',
            'dosis' => 'nullable|string|max:10',
            'satuan' => 'required|string|max:30',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['stok_minimum'] = $validated['stok_minimum'] ?? $medicine->stok_minimum;

        $medicine->update($validated);

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Data obat berhasil diperbarui!');
    }

    /**
     * Tambah stok
     */
    public function addStock(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $medicine->increaseStock($validated['jumlah']);

        return back()->with('success', "Stok {$medicine->nama_obat} berhasil ditambah {$validated['jumlah']} {$medicine->satuan}!");
    }

    /**
     * Hapus obat (soft delete agar riwayat resep/transaksi tidak rusak)
     */
    public function destroy(Medicine $medicine)
    {
        // Soft delete - data prescription_items tetap utuh
        $medicine->delete();

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Obat berhasil dihapus!');
    }

    /**
     * API: Search medicines
     */
    public function apiSearch(Request $request)
    {
        $search = $request->input('q');
        
        $medicines = Medicine::active()
            ->inStock()
            ->search($search)
            ->limit(10)
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id_medicine,
                    'nama' => $m->nama_obat,
                    'satuan' => $m->satuan,
                    'harga' => $m->harga,
                    'stok' => $m->stok,
                ];
            });

        return response()->json($medicines);
    }
}
