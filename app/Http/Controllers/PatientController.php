<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class PatientController extends Controller
{
    // ADMIN: Menampilkan daftar semua pasien
    public function indexAdmin()
    {
        $patients = Patient::orderBy('name')->paginate(10);
        return view('admin.patients.index', compact('patients'));
    }

    // DOKTER: Menampilkan daftar semua pasien (Hanya Lihat)
    public function indexDoctor()
    {
        Gate::authorize('view-all-patients');
        $patients = Patient::orderBy('name')->paginate(10);
        return view('dokter.patients.index', compact('patients'));
    }

    // ADMIN/DOKTER: Menampilkan form untuk menambah pasien baru
    public function create()
    {
        Gate::authorize('manage-patients');
        if (auth()->user()->role === 'dokter') {
            return view('dokter.patients.create');
        }
        return view('admin.patients.create');
    }

    // ADMIN/DOKTER: Menyimpan pasien baru ke database dan generate nomor RM
    public function store(Request $request)
    {
        Gate::authorize('manage-patients');

        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age_years' => 'nullable|integer|min:0|max:30',
            'age_months' => 'nullable|integer|min:0|max:11',
            'gender' => 'nullable|string|max:50',
            'weight' => 'nullable|numeric|min:0',
            'medical_history' => 'nullable|string',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'owner_address' => 'nullable|string|max:255',
            'owner_email' => 'nullable|email|max:255',
        ]);

        $data = $request->only([
            'name',
            'species',
            'breed',
            'gender',
            'weight',
            'medical_history',
            'owner_name',
            'owner_phone',
            'owner_address',
            'owner_email',
        ]);

        // Hitung tanggal lahir dari umur jika diisi
        if ($request->filled('age_years') || $request->filled('age_months')) {
            $years = (int) $request->input('age_years', 0);
            $months = (int) $request->input('age_months', 0);
            $dob = Carbon::now()->subYears($years)->subMonths($months);
            $data['date_of_birth'] = $dob->format('Y-m-d');
        } else {
            $data['date_of_birth'] = null;
        }

        $patient = Patient::create($data);

        // Generate Nomor RM setelah pasien dibuat (karena butuh ID pasien)
        $patient->medical_record_number = 'RM-' . Carbon::now()->format('Ymd') . '-' . str_pad($patient->id, 4, '0', STR_PAD_LEFT);
        $patient->save(); // Simpan kembali untuk update nomor RM

        $redirectRoute = auth()->user()->role === 'dokter' ? 'dokter.patients.index' : 'admin.patients.index';
        return redirect()->route($redirectRoute)->with('success', 'Data pasien berhasil ditambahkan! Nomor RM: ' . $patient->medical_record_number);
    }

    // ADMIN/DOKTER: Menampilkan detail pasien
    public function show(Patient $patient)
    {
        $patient->load([
            'medicalRecords' => function ($query) {
                $query->orderByDesc('visit_date')->with('doctor');
            }
        ]);

        return view('shared.patients.show', compact('patient'));
    }

    // ADMIN: Menampilkan form untuk mengedit pasien
    public function edit(Patient $patient)
    {
        Gate::authorize('manage-patients');
        return view('admin.patients.edit', compact('patient'));
    }

    // ADMIN: Memperbarui data pasien di database
    public function update(Request $request, Patient $patient)
    {
        Gate::authorize('manage-patients');

        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age_years' => 'nullable|integer|min:0|max:30',
            'age_months' => 'nullable|integer|min:0|max:11',
            'gender' => 'nullable|string|max:50',
            'weight' => 'nullable|numeric|min:0',
            'medical_history' => 'nullable|string',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'owner_address' => 'nullable|string|max:255',
            'owner_email' => 'nullable|email|max:255',
        ]);

        $data = $request->only([
            'name',
            'species',
            'breed',
            'gender',
            'weight',
            'medical_history',
            'owner_name',
            'owner_phone',
            'owner_address',
            'owner_email',
        ]);

        if ($request->filled('age_years') || $request->filled('age_months')) {
            $years = (int) $request->input('age_years', 0);
            $months = (int) $request->input('age_months', 0);
            $dob = Carbon::now()->subYears($years)->subMonths($months);
            $data['date_of_birth'] = $dob->format('Y-m-d');
        }

        $patient->update($data);

        return redirect()->route('admin.patients.index')->with('success', 'Data pasien berhasil diperbarui!');
    }

    // ADMIN: Menghapus pasien dari database
    public function destroy(Patient $patient)
    {
        Gate::authorize('manage-patients');
        $patient->delete();

        return redirect()->route('admin.patients.index')->with('success', 'Data pasien berhasil dihapus!');
    }
}