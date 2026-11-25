<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\Pet;
use App\Models\MedicalRecord;
use App\Models\Transaction;
use App\Models\Medicine;
use App\Models\Referral;
use App\Models\WhatsAppLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Ambil log Fonnte dari API (di-cache 5 menit agar tidak lambat tiap load)
        $fonnteLogs = [];
        $fonnteApiDebug = null;
        try {
            $cachedResponse = cache()->remember('fonnte_logs', 300, function () {
                $response = \Illuminate\Support\Facades\Http::withToken(config('services.fonnte.token', env('FONNTE_TOKEN')))
                    ->withOptions(['verify' => false])
                    ->timeout(5)
                    ->get('https://api.fonnte.com/log');
                return [
                    'body' => $response->body(),
                    'success' => $response->successful(),
                    'data' => $response->successful() ? $response->json() : [],
                ];
            });
            $fonnteApiDebug = $cachedResponse['body'];
            $fonnteLogs = $cachedResponse['data'];
        } catch (\Exception $e) {
            $fonnteLogs = [];
            $fonnteApiDebug = $e->getMessage();
        }

        // Statistik Utama (TTL dapat diatur dari .env melalui DASHBOARD_CACHE_SECONDS)
        $statsResolver = function () use ($today) {
            $statsRaw = DB::selectOne("
                SELECT
                    (SELECT COUNT(*) FROM owners) as total_pemilik,
                    (SELECT COUNT(*) FROM pets) as total_hewan,
                    (SELECT COUNT(*) FROM transactions WHERE status_bayar = 'unpaid') as transaksi_belum_bayar,
                    (SELECT COALESCE(SUM(total_biaya), 0) FROM transactions WHERE status_bayar = 'paid' AND DATE(tgl_bayar) = ?) as pendapatan_hari_ini,
                    (SELECT COUNT(*) FROM medicines WHERE stok <= stok_minimum AND deleted_at IS NULL) as obat_stok_rendah,
                    (SELECT COUNT(*) FROM referrals WHERE status_cetak = 'pending') as rujukan_pending
            ", [$today]);

            return [
                'total_pemilik' => $statsRaw->total_pemilik ?? 0,
                'total_hewan' => $statsRaw->total_hewan ?? 0,
                'transaksi_belum_bayar' => $statsRaw->transaksi_belum_bayar ?? 0,
                'pendapatan_hari_ini' => $statsRaw->pendapatan_hari_ini ?? 0,
                'obat_stok_rendah' => $statsRaw->obat_stok_rendah ?? 0,
                'rujukan_pending' => $statsRaw->rujukan_pending ?? 0,
            ];
        };

        $dashboardTtl = (int) config('cache.dashboard_ttl', 60);
        $stats = $dashboardTtl > 0
            ? cache()->remember('admin_dashboard_stats_' . $today->toDateString(), $dashboardTtl, $statsResolver)
            : $statsResolver();

        // Transaksi Belum Bayar
        $pendingTransactions = Transaction::with(['medicalRecord.pet.owner'])
            ->where('status_bayar', 'unpaid')
            ->latest()
            ->limit(5)
            ->get();

        // Registrasi Pemilik Terbaru
        $recentOwners = Owner::withCount('pets')
            ->latest()
            ->limit(5)
            ->get();

        // Stok Obat Rendah
        $lowStockMedicines = Medicine::whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')
            ->get();

        // Cek Log Pengiriman Hari Ini
        $sudahKirimHariIni = WhatsAppLog::whereDate('created_at', $today)->exists();

        // Riwayat Log WA (10 terbaru, tanpa filter tanggal)
        $waLogs = WhatsAppLog::orderByDesc('created_at')->limit(10)->get();

        return view('admin.dashboard', compact(
            'stats',
            'pendingTransactions',
            'recentOwners',
            'lowStockMedicines',
            'sudahKirimHariIni',
            'waLogs',
            'fonnteLogs',
            'fonnteApiDebug'
        ));
    }

    /**
     * Mengirim reminder H-1 sebelum tgl kontrol (next_control_date)
     */
    public function kirimReminder()
    {
        // Mendefinisikan tanggal besok (H-1 dari jadwal kontrol)
        $besok = Carbon::tomorrow()->toDateString();
        
        // Ambil data kontrol besok lengkap dengan relasi pet dan owner
        $jadwalReminder = MedicalRecord::with(['pet.owner'])
            ->whereDate('next_control_date', $besok)
            ->whereHas('pet', function ($query) {
                $query->alive();
            })
            ->get();

        if ($jadwalReminder->isEmpty()) {
            return redirect()->back()->with('error', "Gagal: Tidak ada pasien yang memiliki jadwal kontrol untuk tanggal besok ($besok).");
        }

        $count = 0;
        $logsToInsert = [];
        foreach ($jadwalReminder as $record) {
            // Pastikan relasi pet dan owner ada
            if ($record->pet && $record->pet->owner && !$record->pet->is_deceased) {
                $owner = $record->pet->owner;
                $pet   = $record->pet;

                $logsToInsert[] = [
                    'nama_pemilik' => $owner->nama ?? 'Tanpa Nama',
                    'nama_hewan'   => $pet->nama_hewan ?? 'Tanpa Nama',
                    'nomor_wa'     => $owner->no_hp ?? '-',
                    'status'       => 'Terkirim (H-1)',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
                $count++;
            }
        }

        if (!empty($logsToInsert)) {
            WhatsAppLog::insert($logsToInsert);
        }

        if ($count > 0) {
            return redirect()->back()->with('success', "Berhasil mengirim $count reminder & mencatat log riwayat ke database.");
        }

        return redirect()->back()->with('error', "Data ditemukan, tapi gagal memproses log karena masalah relasi.");
    }
}