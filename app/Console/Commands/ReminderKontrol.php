<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MedicalRecord; 
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ReminderKontrol extends Command
{
    protected $signature = 'send:reminder-kontrol';
    protected $description = 'Kirim reminder WA otomatis H-1 jadwal kontrol';

    public function handle()
    {
        // 1. Cari jadwal kontrol untuk BESOK
        $besok = Carbon::tomorrow()->toDateString();
        
        // Ambil data pasien yang besok kontrol
        $pasien = MedicalRecord::with(['pet.owner']) // Eager loading supaya cepat
                               ->whereDate('next_control_date', $besok)
                               ->whereNotNull('next_control_date')
                               ->whereHas('pet', function ($query) {
                                   $query->alive();
                               })
                               ->get();

        if ($pasien->isEmpty()) {
            $this->info("Tidak ada jadwal kontrol untuk besok ($besok).");
            return;
        }

        // Ambil token dengan config sebagai cadangan jika env() gagal
        $token = "TJCL5T1LJEJvqNzp426h";

        foreach ($pasien as $item) {
            // Pastikan data owner dan no_hp ada
            if (!$item->pet || !$item->pet->owner || !$item->pet->owner->no_hp || $item->pet->is_deceased) {
                continue;
            }

            $noHp = $item->pet->owner->no_hp;
            $namaOwner = $item->pet->owner->nama;
            $namaAnabul = $item->pet->nama_hewan;

            // MEMBERSIHKAN NOMOR HP (Penting!)
            $noHp = preg_replace('/[^0-9]/', '', $noHp);

            $pesan = "Halo Kak *$namaOwner*,\n\n" .
                     "Mengingatkan jadwal kontrol untuk anabul tercinta *" . $namaAnabul . "* di *Pet Area Clinic* pada besok hari, tanggal " . Carbon::parse($besok)->translatedFormat('d F Y') . ".\n\n" .
                     "Sampai jumpa di klinik!";

            // Kirim ke Fonnte
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $noHp,
                'message' => $pesan,
                'delay' => '2', 
            ]);

            // Simpan log ke database lokal
            \App\Models\WhatsAppLog::create([
                'nama_pemilik' => $namaOwner ?? 'Tanpa Nama',
                'nama_hewan' => $namaAnabul ?? '-',
                'nomor_wa' => $noHp,
                'status' => $response->successful() ? 'Terkirim' : 'Gagal',
            ]);

            // Munculkan log di terminal jika gagal untuk debug
            if ($response->failed()) {
                $this->error("Gagal mengirim ke $noHp. Error: " . $response->body());
            } else {
                $this->line("Berhasil mengirim ke: $noHp ($namaAnabul)");
            }
        }

        $this->info('Selesai! Berhasil memproses ' . $pasien->count() . ' data.');
    }
}