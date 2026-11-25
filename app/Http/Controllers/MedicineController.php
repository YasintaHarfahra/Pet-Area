<?php
/**
 * =====================================================
 * CONTROLLER: MedicineController (STOK OBAT)
 * =====================================================
 * 
 * Controller untuk Admin mengelola stok obat.
 * Stok akan berkurang otomatis saat dokter meresepkan.
 * =====================================================
 */

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display listing of medicines
     */
    public function index(Request $request)
    {
        $query = Medicine::orderBy('nama_obat', 'asc');

        // Filter active/inactive
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by stock
        if ($request->has('stock')) {
            if ($request->stock === 'low') {
                $query->where('stok', '<=', 10)->where('stok', '>', 0);
            } elseif ($request->stock === 'out') {
                $query->where('stok', '<=', 0);
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $medicines = $query->paginate(15);

        return view('admin.medicines.index', compact('medicines'));
    }

    /**
     * Show form for creating new medicine
     */
    public function create()
    {
        return view('admin.medicines.create');
    }

    /**
     * Store new medicine
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'dosis' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Medicine::create($validated);

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Obat berhasil ditambahkan');
    }

    /**
     * Show medicine details
     */
    public function show(Medicine $medicine)
    {
        // Load prescription history
        $prescriptions = $medicine->prescriptions()
            ->with(['animalMedicalRecord.animal.owner'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.medicines.show', compact('medicine', 'prescriptions'));
    }

    /**
     * Show form for editing medicine
     */
    public function edit(Medicine $medicine)
    {
        return view('admin.medicines.edit', compact('medicine'));
    }

    /**
     * Update medicine
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'dosis' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $medicine->update($validated);

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Obat berhasil diperbarui');
    }

    /**
     * Delete medicine (soft delete agar riwayat resep/transaksi tidak rusak)
     */
    public function destroy(Medicine $medicine)
    {
        // Soft delete - data prescription_items tetap utuh
        $medicine->delete();

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Obat berhasil dihapus');
    }

    /**
     * Add stock (for admin to restock)
     */
    public function addStock(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'tambah_stok' => 'required|integer|min:1',
        ]);

        $medicine->increment('stok', $validated['tambah_stok']);

        return redirect()
            ->back()
            ->with('success', "Stok berhasil ditambah {$validated['tambah_stok']} {$medicine->satuan}");
    }

    /**
     * API: Get medicines for prescription form (AJAX)
     */
    public function apiSearch(Request $request)
    {
        $search = $request->get('q', '');
        
        $medicines = Medicine::active()
            ->inStock()
            ->where('nama_obat', 'like', "%{$search}%")
            ->select('id', 'nama_obat', 'satuan', 'harga_jual', 'stok', 'dosis')
            ->take(10)
            ->get();

        return response()->json($medicines);
    }

    /**
     * Get low stock alerts (for dashboard)
     */
    public function getLowStockCount()
    {
        $count = Medicine::active()
            ->where('stok', '<=', 10)
            ->count();

        return response()->json(['count' => $count]);
    }
}
