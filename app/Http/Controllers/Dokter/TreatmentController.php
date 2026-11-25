<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TreatmentController extends Controller
{
    /**
     * Hapus tindakan via AJAX (TomSelect + SweetAlert)
     * Menggunakan soft delete agar data riwayat pemeriksaan tidak rusak
     */
    public function destroyAjax(Request $request)
    {
        try {
            $id = $request->input('id');
            if (!$id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID tindakan tidak ditemukan.'
                ], 400);
            }
            $treatment = Treatment::find($id);
            if (!$treatment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tindakan tidak ditemukan.'
                ], 404);
            }
            // Soft delete - data pivot medical_record_treatments tetap utuh
            $treatment->delete();
            return response()->json([
                'success' => true,
                'message' => 'Tindakan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            Log::error('Treatment Delete Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tindakan: ' . $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Simpan tindakan baru via AJAX (TomSelect + SweetAlert)
     */
    public function storeAjax(Request $request)
    {
        try {
            Log::info('Treatment Store Request', [
                'request_data' => $request->all(),
            ]);

            $validated = $request->validate([
                'nama_tindakan' => 'required|string|max:255',
                'harga' => 'required|numeric|min:0',
            ]);

            $treatment = Treatment::create([
                'nama_tindakan' => $validated['nama_tindakan'],
                'harga' => $validated['harga'],
                'deskripsi' => $request->input('deskripsi'),
                'kategori' => $request->input('kategori'),
                'is_active' => true,
            ]);

            Log::info('Treatment Created Successfully', [
                'treatment_id' => $treatment->id_treatment,
                'nama_tindakan' => $treatment->nama_tindakan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tindakan berhasil ditambahkan.',
                'treatment' => [
                    'id' => $treatment->id_treatment,
                    'nama_tindakan' => $treatment->nama_tindakan,
                    'harga' => (int) $treatment->harga,
                    'kategori' => $treatment->kategori,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Treatment Validation Error', [
                'errors' => $e->errors(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_map(function($errors) {
                    return implode(', ', $errors);
                }, $e->errors())),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Treatment Store Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan tindakan: ' . $e->getMessage(),
            ], 500);
        }
    }
}

