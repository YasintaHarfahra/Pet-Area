<?php
// app/Http/Controllers/TransactionController.php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Pet;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    // ADMIN: Menampilkan daftar semua transaksi dengan filter status
    public function index(Request $request)
    {
        Gate::authorize('manage-transactions');

        $status = $request->input('status', 'all'); // Filter berdasarkan status
        $search = $request->input('search', '');

        $query = Transaction::with(['pet', 'admin', 'doctor', 'medicalRecord']);

        // Filter berdasarkan status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search berdasarkan patient name, medical record number, atau description
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('pet', function ($petQ) use ($search) {
                      $petQ->where('name', 'like', "%{$search}%")
                               ->orWhere('medical_record_number', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->orderByDesc('transaction_date')->paginate(15);
        $pendingCount = Transaction::where('status', 'pending')->count();
        $paidCount = Transaction::where('status', 'paid')->count();

        return view('admin.transactions.index', compact('transactions', 'status', 'search', 'pendingCount', 'paidCount'));
    }

    // ADMIN: Menampilkan form untuk menambah transaksi baru
    public function create()
    {
        Gate::authorize('manage-transactions');
        $patients = Pet::orderBy('name')->get();
        $medicalRecords = MedicalRecord::with(['patient', 'doctor'])->whereDoesntHave('transaction')->orderByDesc('visit_date')->get();
        return view('admin.transactions.create', compact('patients', 'medicalRecords'));
    }

    // ADMIN: Menyimpan transaksi baru ke database
    public function store(Request $request)
    {
        Gate::authorize('manage-transactions');

        $request->validate([
            'patient_id' => 'nullable|exists:pets,id_pet',
            'medical_record_id' => 'nullable|exists:medical_records,id_medical_record',
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'status' => 'required|in:pending,paid',
        ]);

        Transaction::create([
            'id_pet' => $request->patient_id,
            'id_medical_record' => $request->medical_record_id,
            'id_admin' => Auth::id(),
            'transaction_date' => $request->transaction_date,
            'description' => $request->description,
            'amount' => $request->amount,
            'service_fee' => $request->service_fee ?? 0,
            'payment_method' => $request->payment_method,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    // ADMIN: Menampilkan detail transaksi
    public function show(Transaction $transaction)
    {
        $transaction->load('patient', 'admin', 'doctor', 'medicalRecord');
        return view('admin.transactions.show', compact('transaction'));
    }

    // ADMIN: Menampilkan form untuk mengedit transaksi
    public function edit(Transaction $transaction)
    {
        Gate::authorize('manage-transactions');
        $patients = Pet::orderBy('name')->get();
        $medicalRecords = MedicalRecord::with(['patient', 'doctor'])->orderByDesc('visit_date')->get();
        return view('admin.transactions.edit', compact('transaction', 'patients', 'medicalRecords'));
    }

    // ADMIN: Memperbarui data transaksi di database
    public function update(Request $request, Transaction $transaction)
    {
        Gate::authorize('manage-transactions');

        $request->validate([
            'patient_id' => 'nullable|exists:pets,id_pet',
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'status' => 'required|in:pending,paid',
        ]);

        $transaction->update($request->all());

        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    // ADMIN: Menghapus transaksi dari database
    public function destroy(Transaction $transaction)
    {
        Gate::authorize('manage-transactions');
        $transaction->delete();
        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil dihapus!');
    }

    // ADMIN: Proses pembayaran - update status ke 'paid' dengan metode pembayaran
    public function processPayment(Request $request, Transaction $transaction)
    {
        Gate::authorize('manage-transactions');

        $request->validate([
            'payment_method' => 'required|string|in:cash,transfer,card,check',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $totalAmount = $transaction->total_amount;
        $paidAmount = (float) $request->paid_amount;

        // Validasi jumlah yang dibayar
        if ($paidAmount < $totalAmount) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Jumlah pembayaran kurang dari total ({$totalAmount})"
                ], 422);
            }
            return back()->withErrors(['paid_amount' => "Jumlah pembayaran kurang dari total ({$totalAmount})"]);
        }

        $transaction->update([
            'status' => 'paid',
            'payment_method' => $request->payment_method,
            'transaction_date' => Carbon::now()->toDateString(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses! Status diubah menjadi Lunas.'
            ]);
        }

        return redirect()->route('admin.transactions.index')->with('success', 'Pembayaran berhasil diproses! Status diubah menjadi Lunas.');
    }

    // ADMIN: Get transaction data via AJAX untuk modal
    public function getTransactionData(Transaction $transaction)
    {
        $transaction->load('patient', 'medicalRecord.doctor');
        return response()->json($transaction);
    }

    // ADMIN: Menampilkan tampilan nota untuk dicetak
    public function printNota(Transaction $transaction)
    {
        Gate::authorize('manage-transactions'); // Hanya admin yang bisa mencetak nota

        $transaction->load('patient', 'admin'); // Pastikan relasi diload

        // View ini akan sangat minimalis, tanpa layout/header/sidebar, hanya isi nota
        // Pastikan nama file ini adalah nota_print.blade.php
        return view('admin.transactions.nota_print', compact('transaction'));
    }
}