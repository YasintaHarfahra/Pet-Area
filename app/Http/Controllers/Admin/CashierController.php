<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

/**
 * Controller untuk Admin - Kasir (Pembayaran)
 */
class CashierController extends Controller
{
    /**
     * Hapus tagihan pembayaran (transaction)
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('admin.cashier.index')->with('success', 'Tagihan berhasil dihapus.');
    }

    /**
     * Daftar transaksi
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['medicalRecord.pet.owner', 'admin'])
            ->latest('created_at');

        // Filter status
        if ($status = $request->input('status')) {
            $query->where('status_bayar', $status);
        }

        // Filter tanggal
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        // Hanya belum bayar default
        if (!$request->has('status')) {
            $query->belumBayar();
        }

        $transactions = $query->paginate(15);

        // Stats (1 query gabungan, bukan 3 query terpisah)
        $statsRaw = \Illuminate\Support\Facades\DB::selectOne("
            SELECT
                COUNT(CASE WHEN status_bayar = 'unpaid' THEN 1 END) as belum_bayar,
                COUNT(CASE WHEN status_bayar = 'paid' AND DATE(tgl_bayar) = ? THEN 1 END) as lunas_hari_ini,
                COALESCE(SUM(CASE WHEN status_bayar = 'paid' AND DATE(tgl_bayar) = ? THEN total_biaya ELSE 0 END), 0) as total_hari_ini
            FROM transactions
        ", [today(), today()]);

        $stats = [
            'belum_bayar' => $statsRaw->belum_bayar ?? 0,
            'lunas_hari_ini' => $statsRaw->lunas_hari_ini ?? 0,
            'total_hari_ini' => $statsRaw->total_hari_ini ?? 0,
        ];

        return view('admin.cashier.index', compact('transactions', 'stats'));
    }

    /**
     * Detail transaksi
     */
    public function show(Transaction $transaction)
    {
        $transaction->load([
            'medicalRecord.pet.owner',
            'medicalRecord.prescriptionItems.medicine',
            'medicalRecord.doctor',
            'medicalRecord.treatments',
            'admin'
        ]);

        // Gunakan harga snapshot yang sudah tersimpan, JANGAN hitung ulang dari harga terkini
        $mr = $transaction->medicalRecord;
        if ($mr) {
            // Pemeriksaan
            if (!isset($mr->biaya_pemeriksaan) || $mr->biaya_pemeriksaan === null) {
                $mr->biaya_pemeriksaan = 0;
            }
            // Biaya tindakan & obat sudah tersimpan di medical_records saat pemeriksaan
            // Tidak perlu dihitung ulang agar tidak berubah jika harga obat/tindakan diedit
        }

        return view('admin.cashier.show', compact('transaction'));
    }

    /**
     * Proses pembayaran
     */
    public function processPayment(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:' . $transaction->total_biaya,
            'metode_bayar' => 'required|in:tunai,transfer,qris',
        ]);

        $success = $transaction->processPayment(
            $validated['jumlah_bayar'],
            $validated['metode_bayar'],
            Auth::id()
        );

        if (!$success) {
            return back()->with('error', 'Jumlah bayar kurang dari total tagihan!');
        }

        return redirect()
            ->route('admin.cashier.receipt', $transaction)
            ->with('success', 'Pembayaran berhasil diproses!');
    }

    /**
     * Cetak struk
     */
    public function receipt(Transaction $transaction)
    {
        $transaction->load([
            'medicalRecord.pet.owner',
            'medicalRecord.prescriptionItems.medicine',
            'medicalRecord.doctor',
            'admin'
        ]);

        return view('admin.cashier.receipt', compact('transaction'));
    }

    /**
     * Laporan kasir berdasarkan periode
     */
    public function periodReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', $startDate);

        if ($startDate > $endDate) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $startAt = Carbon::parse($startDate)->startOfDay();
        $endAt = Carbon::parse($endDate)->endOfDay();
        
        $transactions = Transaction::with(['medicalRecord.pet.owner'])
            ->lunas()
            ->whereBetween('tgl_bayar', [$startAt, $endAt])
            ->orderBy('tgl_bayar', 'asc')
            ->get();

        $summary = [
            'total_transaksi' => $transactions->count(),
            'total_pendapatan' => $transactions->sum('total_biaya'),
            'tunai' => $transactions->where('metode_bayar', 'tunai')->sum('total_biaya'),
            'transfer' => $transactions->where('metode_bayar', 'transfer')->sum('total_biaya'),
            'qris' => $transactions->where('metode_bayar', 'qris')->sum('total_biaya'),
        ];

        return view('admin.cashier.period-report', compact('transactions', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Export laporan kasir periode ke PDF
     */
    public function periodReportPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', $startDate);

        if ($startDate > $endDate) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $startAt = Carbon::parse($startDate)->startOfDay();
        $endAt = Carbon::parse($endDate)->endOfDay();
        
        $transactions = Transaction::with(['medicalRecord.pet.owner', 'medicalRecord.doctor', 'medicalRecord.treatments', 'medicalRecord.prescriptionItems.medicine', 'admin'])
            ->lunas()
            ->whereBetween('tgl_bayar', [$startAt, $endAt])
            ->orderBy('tgl_bayar', 'asc')
            ->get();

        $summary = [
            'total_transaksi' => $transactions->count(),
            'total_pendapatan' => $transactions->sum('total_biaya'),
            'tunai' => $transactions->where('metode_bayar', 'tunai')->sum('total_biaya'),
            'transfer' => $transactions->where('metode_bayar', 'transfer')->sum('total_biaya'),
            'qris' => $transactions->where('metode_bayar', 'qris')->sum('total_biaya'),
            'count_tunai' => $transactions->where('metode_bayar', 'tunai')->count(),
            'count_transfer' => $transactions->where('metode_bayar', 'transfer')->count(),
            'count_qris' => $transactions->where('metode_bayar', 'qris')->count(),
        ];

        $pdf = Pdf::loadView('admin.cashier.period-report-pdf', [
            'transactions' => $transactions,
            'summary' => $summary,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generated_at' => now()->format('d/m/Y H:i'),
            'admin_name' => Auth::user()->name ?? '-',
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan_transaksi_' . $startDate . '_sd_' . $endDate . '.pdf');
    }
}
