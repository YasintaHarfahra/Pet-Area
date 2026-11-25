<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller untuk Admin - Pendaftaran Owner & Pet
 */
class RegistrationController extends Controller
{
    /**
     * Hapus pemilik (soft delete - rekam medis tetap aman)
     */
    public function destroyOwner(Owner $owner)
    {
        $owner->delete();
        return redirect()->route('admin.registration.owners.index')->with('success', 'Pemilik berhasil dihapus!');
    }
    /**
     * Daftar pemilik + hewan (Tab View)
     */
    public function indexOwners(Request $request)
    {
        $search = $request->input('search');
        $tab = $request->input('tab', 'owners'); // Default: owners
        
        // Total counts for both tabs (lightweight queries for badge numbers)
        $totalOwners = Owner::count();
        $totalPets = Pet::count();

        $owners = collect();
        $pets = collect();

        if ($tab === 'owners') {
            $ownersQuery = Owner::withCount('pets')->latest();
            if ($search) {
                $ownersQuery->search($search);
            }
            $owners = $ownersQuery->paginate(15)->withQueryString();
        } else {
            $petsQuery = Pet::with(['owner' => fn($q) => $q->withTrashed(), 'latestMedicalRecord'])->latest();
            
            if ($search) {
                $petsQuery->where(function($q) use ($search) {
                    $q->where('nama_hewan', 'LIKE', "%{$search}%")
                      ->orWhere('jenis', 'LIKE', "%{$search}%")
                      ->orWhere('ras', 'LIKE', "%{$search}%")
                      ->orWhereHas('owner', function($ownerQuery) use ($search) {
                          $ownerQuery->where('nama', 'LIKE', "%{$search}%");
                      });
                });
            }
            $pets = $petsQuery->paginate(15)->withQueryString();
        }

        return view('admin.registration.owners.index', compact('owners', 'pets', 'tab', 'search', 'totalOwners', 'totalPets'));
    }

    /**
     * Form tambah pemilik baru
     */
    public function createOwner()
    {
        return view('admin.registration.owners.create');
    }

    /**
     * Simpan pemilik baru
     */
    public function storeOwner(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        // Format nomor HP ke +62
        $validated['no_hp'] = $this->formatPhoneNumber($validated['no_hp']);

        $owner = Owner::create($validated);

        return redirect()
            ->route('admin.registration.owners.show', $owner)
            ->with('success', 'Pemilik berhasil ditambahkan!');
    }

    /**
     * Detail pemilik + daftar hewan
     */
    public function showOwner(Owner $owner)
    {
        $owner->load('pets');
        
        return view('admin.registration.owners.show', compact('owner'));
    }

    /**
     * Form edit pemilik
     */
    public function editOwner(Owner $owner)
    {
        return view('admin.registration.owners.edit', compact('owner'));
    }

    /**
     * Update pemilik
     */
    public function updateOwner(Request $request, Owner $owner)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        $validated['no_hp'] = $this->formatPhoneNumber($validated['no_hp']);

        $owner->update($validated);

        return redirect()
            ->route('admin.registration.owners.show', $owner)
            ->with('success', 'Data pemilik berhasil diperbarui!');
    }

    // ==========================================
    // PET MANAGEMENT
    // ==========================================

    /**
     * Form tambah hewan untuk owner tertentu
     */
    public function createPet(Owner $owner)
    {
        return view('admin.registration.pets.create', compact('owner'));
    }

    /**
     * Simpan hewan baru
     */
    public function storePet(Request $request, Owner $owner)
    {
        $validated = $request->validate([
            'nama_hewan' => 'required|string|max:100',
            'jenis' => 'required|string|max:50',
            'ras' => 'nullable|string|max:100',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:jantan,betina',
            'catatan' => 'nullable|string',
            'status_hidup' => 'required|in:hidup,mati',
            'tanggal_kematian' => 'nullable|date|required_if:status_hidup,mati',
            'penyebab_kematian' => 'nullable|string|max:1000',
            'foto_hewan' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if (($validated['status_hidup'] ?? 'hidup') !== 'mati') {
            $validated['tanggal_kematian'] = null;
            $validated['penyebab_kematian'] = null;
        }

        if ($request->hasFile('foto_hewan')) {
            $validated['foto_hewan'] = $request->file('foto_hewan')->store('pets', 'public');
        }

        $validated['id_owner'] = $owner->id_owner;

        $pet = Pet::create($validated);

        return redirect()
            ->route('admin.registration.owners.show', $owner)
            ->with('success', "Hewan {$pet->nama_hewan} berhasil ditambahkan!");
    }

    /**
     * Form edit hewan
     */
    public function editPet(Pet $pet)
    {
        $pet->load('owner');
        
        return view('admin.registration.pets.edit', compact('pet'));
    }

    /**
     * Update hewan
     */
    public function updatePet(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'nama_hewan' => 'required|string|max:100',
            'jenis' => 'required|string|max:50',
            'ras' => 'nullable|string|max:100',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:jantan,betina',
            'catatan' => 'nullable|string',
            'status_hidup' => 'required|in:hidup,mati',
            'tanggal_kematian' => 'nullable|date|required_if:status_hidup,mati',
            'penyebab_kematian' => 'nullable|string|max:1000',
            'foto_hewan' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if (($validated['status_hidup'] ?? 'hidup') !== 'mati') {
            $validated['tanggal_kematian'] = null;
            $validated['penyebab_kematian'] = null;
        }

        if ($request->hasFile('foto_hewan')) {
            if (!empty($pet->foto_hewan)) {
                Storage::disk('public')->delete($pet->foto_hewan);
            }
            $validated['foto_hewan'] = $request->file('foto_hewan')->store('pets', 'public');
        }

        $pet->update($validated);

        return redirect()
            ->route('admin.registration.owners.show', $pet->owner)
            ->with('success', "Data {$pet->nama_hewan} berhasil diperbarui!");
    }

    /**
     * Hapus hewan (soft delete - rekam medis tetap aman)
     */
    public function destroyPet(Pet $pet)
    {
        $owner = $pet->owner;
        $namaHewan = $pet->nama_hewan;
        
        $pet->delete();

        return redirect()
            ->route('admin.registration.owners.show', $owner)
            ->with('success', "Hewan {$namaHewan} berhasil dihapus!");
    }

    // ==========================================
    // API ENDPOINTS
    // ==========================================

    /**
     * API: Cari owner
     */
    public function searchOwners(Request $request)
    {
        $search = $request->input('q');
        
        $owners = Owner::with('pets')
            ->search($search)
            ->limit(10)
            ->get();

        return response()->json($owners->map(function ($owner) {
            return [
                'id' => $owner->id_owner,
                'nama' => $owner->nama,
                'no_hp' => $owner->no_hp,
                'pets' => $owner->pets->map(function ($pet) {
                    return [
                        'id' => $pet->id_pet,
                        'nama_hewan' => $pet->nama_hewan,
                        'jenis' => $pet->jenis,
                    ];
                }),
            ];
        }));
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Format phone number ke format +62
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Hapus karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Konversi 08xx ke 628xx
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        
        // Pastikan dimulai dengan 62
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
