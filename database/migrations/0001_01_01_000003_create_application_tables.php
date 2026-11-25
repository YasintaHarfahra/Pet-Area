<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * =============================================================
 * CONSOLIDATED MIGRATION — Semua tabel aplikasi Pet-Area
 * =============================================================
 * 
 * Migrasi ini menggabungkan 39 file migrasi inkremental menjadi
 * satu file dengan schema final. Migrasi lama ada di _backup/.
 * 
 * Urutan: owners → pets → medical_records → queues → transactions
 *         → treatments → medicines → prescription_items → referrals
 *         → medical_record_drafts → medical_record_treatments
 *         → whats_app_logs
 * =============================================================
 */
return new class extends Migration
{
    public function up(): void
    {
        // =============================================
        // 1. OWNERS
        // =============================================
        Schema::create('owners', function (Blueprint $table) {
            $table->id('id_owner');
            $table->string('owner_code')->unique()->nullable();
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20);
            $table->string('email')->nullable();
            $table->string('no_ktp', 20)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('nama');
            $table->index('no_hp');
        });

        // =============================================
        // 2. PETS
        // =============================================
        Schema::create('pets', function (Blueprint $table) {
            $table->id('id_pet');
            $table->unsignedBigInteger('id_owner');
            $table->string('no_rm', 10)->nullable()->unique()->comment('Nomor Rekam Medis format: RMK0001');
            $table->string('nama_hewan', 100);
            $table->string('jenis', 50)->comment('Kucing, Anjing, dll');
            $table->string('species_code', 2)->nullable()->comment('Kode jenis hewan (K=Kucing, A=Anjing, dll)');
            $table->string('ras', 100)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('jenis_kelamin', ['jantan', 'betina'])->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_owner')->references('id_owner')->on('owners')->onDelete('cascade');
            $table->index('nama_hewan');
            $table->index('jenis');
            $table->index('id_owner');
        });

        // =============================================
        // 3. MEDICAL RECORDS
        // =============================================
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id('id_medical_record');
            $table->unsignedBigInteger('id_pet')->nullable();
            $table->unsignedBigInteger('id_doctor');
            $table->unsignedBigInteger('id_queue')->nullable();
            $table->string('nomor_rekam_medis', 30)->nullable();
            $table->timestamp('visit_date')->nullable();
            $table->date('tgl_periksa')->nullable();

            // Pemeriksaan fisik
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->decimal('suhu_tubuh', 4, 1)->nullable();

            // Hasil pemeriksaan (kolom utama)
            $table->text('symptoms')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->text('prescription')->nullable();
            $table->text('notes')->nullable();

            // Kolom bahasa Indonesia (duplikat untuk transisi)
            $table->text('keluhan')->nullable();
            $table->text('diagnosa')->nullable();
            $table->text('tindakan')->nullable();

            // Kontrol & follow-up
            $table->date('next_control_date')->nullable();
            $table->text('follow_up_notes')->nullable();
            $table->date('tgl_kontrol_berikutnya')->nullable();
            $table->text('catatan_kontrol')->nullable();

            // Biaya
            $table->decimal('biaya_pemeriksaan', 12, 2)->default(0);
            $table->decimal('biaya_tindakan', 12, 2)->default(0);
            $table->decimal('biaya_obat', 12, 2)->default(0);
            $table->decimal('total_biaya', 12, 2)->default(0);

            // Data obat (legacy JSON)
            $table->json('medicines_data')->nullable();

            $table->enum('status', ['draft', 'completed'])->default('completed');
            $table->timestamps();

            $table->foreign('id_pet')->references('id_pet')->on('pets')->onDelete('cascade');
            $table->foreign('id_doctor')->references('id_user')->on('users');
            $table->index('id_pet');
            $table->index('id_doctor');
            $table->index('visit_date');
            $table->index('next_control_date');
        });

        // =============================================
        // 4. QUEUES (Antrian)
        // =============================================
        Schema::create('queues', function (Blueprint $table) {
            $table->id('id_queue');
            $table->unsignedBigInteger('id_pet')->nullable();
            $table->unsignedBigInteger('id_doctor')->nullable();
            $table->integer('queue_number');
            $table->enum('status', ['waiting', 'in-progress', 'completed', 'cancelled'])->default('waiting');
            $table->date('queue_date');
            $table->text('complaint')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('last_worked_at')->nullable();
            $table->timestamps();

            $table->foreign('id_pet')->references('id_pet')->on('pets')->onDelete('cascade');
            $table->foreign('id_doctor')->references('id_user')->on('users')->onDelete('set null');
            $table->index('queue_date');
            $table->index('status');
            $table->index('id_pet');
            $table->index('id_doctor');
        });

        // Tambah FK id_queue di medical_records (setelah queues dibuat)
        Schema::table('medical_records', function (Blueprint $table) {
            $table->foreign('id_queue')->references('id_queue')->on('queues')->onDelete('set null');
            $table->index('id_queue');
        });

        // =============================================
        // 5. TREATMENTS (Tindakan)
        // =============================================
        Schema::create('treatments', function (Blueprint $table) {
            $table->id('id_treatment');
            $table->string('nama_tindakan');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2)->default(0);
            $table->string('kategori')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // =============================================
        // 6. MEDICINES (Obat)
        // =============================================
        Schema::create('medicines', function (Blueprint $table) {
            $table->id('id_medicine');
            $table->string('nama_obat');
            $table->string('satuan', 50)->nullable();
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(10);
            $table->decimal('harga_jual', 12, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('stok');
        });

        // =============================================
        // 7. TRANSACTIONS (Transaksi / Kasir)
        // =============================================
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('id_transaction');
            $table->string('nomor_transaksi', 30)->nullable();
            $table->unsignedBigInteger('id_medical_record')->nullable();
            $table->unsignedBigInteger('id_admin')->nullable();
            $table->unsignedBigInteger('id_doctor')->nullable();
            $table->unsignedBigInteger('id_pet')->nullable();

            // Biaya
            $table->decimal('total_biaya', 12, 2)->default(0);
            $table->decimal('biaya_pemeriksaan', 12, 2)->default(0);
            $table->decimal('biaya_tindakan', 12, 2)->default(0);
            $table->decimal('biaya_obat', 12, 2)->default(0);
            $table->decimal('jumlah_bayar', 12, 2)->default(0);
            $table->decimal('kembalian', 12, 2)->default(0);

            // Status & metode
            $table->enum('status_bayar', ['unpaid', 'paid', 'partial'])->default('unpaid');
            $table->enum('metode_bayar', ['tunai', 'transfer', 'qris'])->nullable();
            $table->timestamp('tgl_bayar')->nullable();

            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_medical_record')->references('id_medical_record')->on('medical_records')->onDelete('set null');
            $table->foreign('id_admin')->references('id_user')->on('users')->onDelete('set null');
            $table->foreign('id_doctor')->references('id_user')->on('users')->onDelete('set null');
            $table->index('id_medical_record');
            $table->index('id_admin');
            $table->index('id_doctor');
            $table->index('status_bayar');
            $table->index('tgl_bayar');
        });

        // =============================================
        // 8. PRESCRIPTION ITEMS (Resep Obat)
        // =============================================
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id('id_prescription_item');
            $table->unsignedBigInteger('id_medical_record');
            $table->unsignedBigInteger('id_medicine');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->string('instruksi', 255)->nullable()->comment('Aturan pakai obat');
            $table->timestamps();

            $table->foreign('id_medical_record')->references('id_medical_record')->on('medical_records')->onDelete('cascade');
            $table->foreign('id_medicine')->references('id_medicine')->on('medicines')->onDelete('restrict');
            $table->index('id_medical_record');
            $table->index('id_medicine');
        });

        // =============================================
        // 9. REFERRALS (Surat Rujukan)
        // =============================================
        Schema::create('referrals', function (Blueprint $table) {
            $table->id('id_referral');
            $table->string('nomor_rujukan')->unique()->nullable();
            $table->unsignedBigInteger('id_medical_record')->nullable();
            $table->unsignedBigInteger('id_doctor');
            $table->string('tujuan_rujukan');
            $table->text('alasan_rujukan')->nullable();
            $table->text('diagnosa_sementara')->nullable();
            $table->text('tindakan_dilakukan')->nullable();
            $table->date('tanggal_rujukan');
            $table->enum('status_cetak', ['pending', 'printed'])->default('pending');
            $table->timestamp('printed_at')->nullable();
            $table->unsignedBigInteger('id_printed_by')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_medical_record')->references('id_medical_record')->on('medical_records')->onDelete('cascade');
            $table->foreign('id_doctor')->references('id_user')->on('users')->onDelete('restrict');
            $table->foreign('id_printed_by')->references('id_user')->on('users')->onDelete('set null');
            $table->index('id_medical_record');
            $table->index('id_doctor');
            $table->index('status_cetak');
            $table->index('tanggal_rujukan');
        });

        // =============================================
        // 10. MEDICAL RECORD DRAFTS
        // =============================================
        Schema::create('medical_record_drafts', function (Blueprint $table) {
            $table->id('id_medical_record_draft');
            $table->unsignedBigInteger('id_queue');
            $table->unsignedBigInteger('id_doctor');
            $table->unsignedBigInteger('id_pet')->nullable();
            $table->json('form_data')->nullable()->comment('Form data yang sedang diisi');
            $table->enum('status', ['draft', 'in-progress', 'completed'])->default('draft');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();

            $table->foreign('id_queue')->references('id_queue')->on('queues')->onDelete('cascade');
            $table->foreign('id_doctor')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_pet')->references('id_pet')->on('pets')->onDelete('cascade');
            $table->index(['id_queue', 'id_doctor']);
            $table->index('status');
            $table->index('id_doctor');
            $table->index('id_queue');
        });

        // =============================================
        // 11. MEDICAL RECORD TREATMENTS (Pivot)
        // =============================================
        Schema::create('medical_record_treatments', function (Blueprint $table) {
            $table->id('id_medical_record_treatment');
            $table->unsignedBigInteger('id_medical_record');
            $table->unsignedBigInteger('id_treatment');
            $table->timestamps();

            $table->foreign('id_medical_record')->references('id_medical_record')->on('medical_records')->onDelete('cascade');
            $table->foreign('id_treatment')->references('id_treatment')->on('treatments')->onDelete('cascade');
            $table->index('id_medical_record');
            $table->index('id_treatment');
        });

        // =============================================
        // 12. WHATSAPP LOGS
        // =============================================
        Schema::create('whats_app_logs', function (Blueprint $table) {
            $table->id('id_whats_app_log');
            $table->string('nama_pemilik');
            $table->string('nama_hewan')->nullable();
            $table->string('nomor_wa');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('whats_app_logs');
        Schema::dropIfExists('medical_record_treatments');
        Schema::dropIfExists('medical_record_drafts');
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('prescription_items');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('medicines');
        Schema::dropIfExists('treatments');
        Schema::dropIfExists('queues');
        Schema::dropIfExists('medical_records');
        Schema::dropIfExists('pets');
        Schema::dropIfExists('owners');

        Schema::enableForeignKeyConstraints();
    }
};
