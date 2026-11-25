<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\MedicalRecord;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Global search across patients, medical records, and transactions
     */
    public function globalSearch(Request $request)
    {
        $query = $request->query('q', '');

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // Search Pets
        $pets = Pet::with('owner')
                   ->where('nama_hewan', 'LIKE', "%{$query}%")
                   ->orWhere('species_code', 'LIKE', "%{$query}%")
                   ->orWhereHas('owner', function ($q) use ($query) {
                       $q->where('nama_pemilik', 'LIKE', "%{$query}%");
                   })
                   ->limit(5)
                   ->get();

        foreach ($pets as $pet) {
            $results[] = [
                'title' => $pet->nama_hewan . ' (' . ($pet->owner->nama_pemilik ?? '-') . ')',
                'type' => 'Pasien - ' . $pet->jenis,
                'url' => route('admin.queues.index'),
            ];
        }

        // Search Medical Records
        $medicalRecords = MedicalRecord::with('pet', 'doctor')
                                      ->where('diagnosis', 'LIKE', "%{$query}%")
                                      ->orWhereHas('pet', function ($q) use ($query) {
                                          $q->where('nama_hewan', 'LIKE', "%{$query}%");
                                      })
                                      ->limit(5)
                                      ->get();

        foreach ($medicalRecords as $record) {
            $results[] = [
                'title' => ($record->pet->nama_hewan ?? '-') . ' - ' . $record->diagnosis,
                'type' => 'Rekam Medis (' . $record->visit_date->format('d M Y') . ')',
                'url' => route('medical_records.show', $record->id_medical_record),
            ];
        }

        // Search Transactions
        $transactions = Transaction::with('medicalRecord.pet')
                                  ->where('notes', 'LIKE', "%{$query}%")
                                  ->orWhereHas('medicalRecord', function ($q) use ($query) {
                                      $q->whereHas('pet', function ($q2) use ($query) {
                                          $q2->where('nama_hewan', 'LIKE', "%{$query}%");
                                      });
                                  })
                                  ->limit(5)
                                  ->get();

        foreach ($transactions as $transaction) {
            $petName = $transaction->medicalRecord->pet->nama_hewan ?? '-';
            $results[] = [
                'title' => $petName . ' - Rp ' . number_format($transaction->total_amount),
                'type' => 'Transaksi (' . $transaction->created_at->format('d M Y') . ')',
                'url' => route('admin.transactions.index'),
            ];
        }

        return response()->json(['results' => array_slice($results, 0, 10)]);
    }
}
