<?php
// app/Http/Controllers/PatientController.php

namespace App\Http\Controllers;

use App\Models\Patient; // Pastikan ini diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon; // Pastikan ini diimpor untuk generate Nomor RM

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

    // ADMIN: Menampilkan form untuk menambah pasien baru
    public function create()
    {
        Gate::authorize('manage-patients');
        return view('admin.patients.create');
    }

    // ADMIN: Menyimpan pasien baru ke database dan generate nomor RM
    public function store(Request $request)
    {
        Gate::authorize('manage-patients');

        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:50',
            'weight' => 'nullable|numeric|min:0',
            'medical_history' => 'nullable|string',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'owner_address' => 'nullable|string|max:255',
            'owner_email' => 'nullable|email|max:255',
        ]);

        $patient = Patient::create($request->all());

        // Generate Nomor RM setelah pasien dibuat (karena butuh ID pasien)
        $patient->medical_record_number = 'RM-' . Carbon::now()->format('Ymd') . '-' . str_pad($patient->id, 4, '0', STR_PAD_LEFT);
        $patient->save(); // Simpan kembali untuk update nomor RM

        return redirect()->route('admin.patients.index')->with('success', 'Data pasien berhasil ditambahkan! Nomor RM: ' . $patient->medical_record_number);
    }

    // ADMIN/DOKTER: Menampilkan detail pasien
    public function show(Patient $patient)
    {
        // Rute sudah diproteksi untuk admin/dokter
        return view('shared.patients.show', compact('patient'));
    }

    // ADMIN: Menampilkan form untuk mengedit pasien
    public function edit(Patient $patient) // <--- PASTIKAN METODE INI ADA DAN KODENYA BENAR
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
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:50',
            'weight' => 'nullable|numeric|min:0',
            'medical_history' => 'nullable|string',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'owner_address' => 'nullable|string|max:255',
            'owner_email' => 'nullable|email|max:255',
        ]);

        $patient->update($request->all());

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