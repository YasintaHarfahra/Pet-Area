<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    /**
     * Tampilkan daftar admin (dikelola oleh dokter)
     */
    public function index()
    {
        // Ambil data admin dari tabel users dimana role = 'admin'
        $staff = User::where('role', 'admin')
            ->orderBy('name')
            ->paginate(10);

        return view('dokter.staff.index', compact('staff'));
    }

    /**
     * Form tambah admin baru
     */
    public function create()
    {
        return view('dokter.staff.create');
    }

    /**
     * Simpan admin baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:100', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('dokter.staff.index')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Form edit admin
     */
    public function edit($id)
    {
        $staff = User::where('role', 'admin')->findOrFail($id);
        return view('dokter.staff.edit', compact('staff'));
    }

    /**
     * Update data admin (termasuk reset password)
     */
    public function update(Request $request, $id)
    {
        $staff = User::where('role', 'admin')->findOrFail($id);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $staff->id_user],
            'username' => ['required', 'string', 'max:100', 'unique:users,username,' . $staff->id_user],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        return redirect()->route('dokter.staff.index')
            ->with('success', 'Data admin berhasil diupdate.');
    }

    /**
     * Hapus admin
     */
    public function destroy($id)
    {
        $staff = User::where('role', 'admin')->findOrFail($id);
        $staff->delete();

        return redirect()->route('dokter.staff.index')
            ->with('success', 'Admin berhasil dihapus.');
    }
}
