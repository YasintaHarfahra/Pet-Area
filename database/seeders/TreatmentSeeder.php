<?php

namespace Database\Seeders;

use App\Models\Treatment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $treatments = [
            // Kategori: Vaksin
            [
                'nama_tindakan' => 'Vaksin Rabies',
                'deskripsi' => 'Vaksinasi pencegahan penyakit rabies',
                'harga' => 150000,
                'kategori' => 'vaksin',
                'is_active' => true,
            ],
            [
                'nama_tindakan' => 'Vaksin DHPP',
                'deskripsi' => 'Vaksin kombinasi untuk distemper, hepatitis, parvovirus, dan parainfluenza',
                'harga' => 200000,
                'kategori' => 'vaksin',
                'is_active' => true,
            ],
            [
                'nama_tindakan' => 'Vaksin Feline',
                'deskripsi' => 'Vaksin untuk kucing (FVRCP)',
                'harga' => 180000,
                'kategori' => 'vaksin',
                'is_active' => true,
            ],

            // Kategori: Konsultasi
            [
                'nama_tindakan' => 'Pemeriksaan Umum',
                'deskripsi' => 'Pemeriksaan medis umum dan palpasi',
                'harga' => 75000,
                'kategori' => 'konsultasi',
                'is_active' => true,
            ],
            [
                'nama_tindakan' => 'Konsultasi Nutrisi',
                'deskripsi' => 'Konsultasi nutrisi dan diet khusus',
                'harga' => 100000,
                'kategori' => 'konsultasi',
                'is_active' => true,
            ],

            // Kategori: Perawatan
            [
                'nama_tindakan' => 'Pembersihan Gigi (Scaling)',
                'deskripsi' => 'Pembersihan plak dan tartar pada gigi',
                'harga' => 300000,
                'kategori' => 'perawatan',
                'is_active' => true,
            ],
            [
                'nama_tindakan' => 'Cukur Rambut',
                'deskripsi' => 'Grooming dan cukur rambut',
                'harga' => 150000,
                'kategori' => 'perawatan',
                'is_active' => true,
            ],
            [
                'nama_tindakan' => 'Perawatan Luka',
                'deskripsi' => 'Pembersihan, disinfeksi, dan perawatan luka',
                'harga' => 125000,
                'kategori' => 'perawatan',
                'is_active' => true,
            ],

            // Kategori: Operasi
            [
                'nama_tindakan' => 'Operasi Kastasi',
                'deskripsi' => 'Operasi sterilisasi untuk anjing/kucing jantan',
                'harga' => 800000,
                'kategori' => 'operasi',
                'is_active' => true,
            ],
            [
                'nama_tindakan' => 'Operasi Ovariohysterektomi',
                'deskripsi' => 'Operasi sterilisasi untuk anjing/kucing betina',
                'harga' => 1200000,
                'kategori' => 'operasi',
                'is_active' => true,
            ],
            [
                'nama_tindakan' => 'Operasi Tumor/Kista',
                'deskripsi' => 'Operasi pengangkatan tumor atau kista',
                'harga' => 1500000,
                'kategori' => 'operasi',
                'is_active' => true,
            ],

            // Kategori: Pemeriksaan Spesial
            [
                'nama_tindakan' => 'Rontgen (X-Ray)',
                'deskripsi' => 'Pemeriksaan radiologi X-ray',
                'harga' => 250000,
                'kategori' => 'pemeriksaan_khusus',
                'is_active' => true,
            ],
            [
                'nama_tindakan' => 'Ultrasound (USG)',
                'deskripsi' => 'Pemeriksaan ultrasound diagnostik',
                'harga' => 350000,
                'kategori' => 'pemeriksaan_khusus',
                'is_active' => true,
            ],
            [
                'nama_tindakan' => 'Pemeriksaan Darah Lengkap',
                'deskripsi' => 'Lab test darah lengkap (CBC)',
                'harga' => 200000,
                'kategori' => 'pemeriksaan_khusus',
                'is_active' => true,
            ],

            // Kategori: Obat/Injeksi
            [
                'nama_tindakan' => 'Injeksi Antibiotik',
                'deskripsi' => 'Suntikan antibiotik',
                'harga' => 80000,
                'kategori' => 'obat_injeksi',
                'is_active' => true,
            ],
            [
                'nama_tindakan' => 'Infus Cairan',
                'deskripsi' => 'Pemasangan infus cairan IV',
                'harga' => 150000,
                'kategori' => 'obat_injeksi',
                'is_active' => true,
            ],
        ];

        foreach ($treatments as $treatment) {
            Treatment::firstOrCreate(
                ['nama_tindakan' => $treatment['nama_tindakan']],
                $treatment
            );
        }
    }
}
