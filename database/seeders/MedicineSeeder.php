<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = [
            [
                'nama_obat' => 'Amoxicillin',
                'satuan' => 'tablet',
                'harga_jual' => 5000,
                'stok' => 100,
                'keterangan' => 'Antibiotik untuk infeksi bakteri (250mg)',
                'is_active' => true,
            ],
            [
                'nama_obat' => 'Paracetamol',
                'satuan' => 'tablet',
                'harga_jual' => 3000,
                'stok' => 80,
                'keterangan' => 'Obat penurun panas dan pereda nyeri (500mg)',
                'is_active' => true,
            ],
            [
                'nama_obat' => 'Metronidazole',
                'satuan' => 'tablet',
                'harga_jual' => 4000,
                'stok' => 60,
                'keterangan' => 'Antibiotik untuk infeksi protozoa (250mg)',
                'is_active' => true,
            ],
            [
                'nama_obat' => 'Doxycycline',
                'satuan' => 'tablet',
                'harga_jual' => 8000,
                'stok' => 50,
                'keterangan' => 'Antibiotik spectrum luas (100mg)',
                'is_active' => true,
            ],
            [
                'nama_obat' => 'Vitamin B Complex',
                'satuan' => 'tablet',
                'harga_jual' => 15000,
                'stok' => 40,
                'keterangan' => 'Vitamin untuk meningkatkan imunitas',
                'is_active' => true,
            ],
            [
                'nama_obat' => 'Probiotik',
                'satuan' => 'botol',
                'harga_jual' => 25000,
                'stok' => 30,
                'keterangan' => 'Suplemen probiotik untuk kesehatan pencernaan (5ml)',
                'is_active' => true,
            ],
            [
                'nama_obat' => 'Cephalexin',
                'satuan' => 'tablet',
                'harga_jual' => 6000,
                'stok' => 75,
                'keterangan' => 'Antibiotik untuk infeksi kulit (250mg)',
                'is_active' => true,
            ],
            [
                'nama_obat' => 'Ibuprofen',
                'satuan' => 'tablet',
                'harga_jual' => 4000,
                'stok' => 90,
                'keterangan' => 'NSAID untuk peradangan dan nyeri (200mg)',
                'is_active' => true,
            ],
            [
                'nama_obat' => 'Domperidone',
                'satuan' => 'tablet',
                'harga_jual' => 3500,
                'stok' => 70,
                'keterangan' => 'Obat anti mual (10mg)',
                'is_active' => true,
            ],
            [
                'nama_obat' => 'Salep Antibiotik',
                'satuan' => 'tube',
                'harga_jual' => 12000,
                'stok' => 25,
                'keterangan' => 'Salep untuk luka dan lesi kulit (10g)',
                'is_active' => true,
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
