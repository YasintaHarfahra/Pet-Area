<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Tambah kolom untuk link ke medical record
            $table->foreignId('medical_record_id')->nullable()->after('patient_id')->constrained('medical_records')->onDelete('set null');
            
            // Ubah status menjadi enum
            $table->dropColumn('status');
            $table->enum('status', ['pending', 'paid'])->default('pending')->after('payment_method');
            
            // Tambah kolom service fee
            $table->decimal('service_fee', 10, 2)->nullable()->after('amount')->comment('Biaya layanan/tindakan');
            
            // Tambah kolom doctor_id untuk tracking dokter yang melayani
            $table->foreignId('doctor_id')->nullable()->after('patient_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['medical_record_id']);
            $table->dropForeignKeyIfExists(['doctor_id']);
            $table->dropColumn(['medical_record_id', 'doctor_id', 'service_fee', 'status']);
            $table->string('status')->default('completed');
        });
    }
};
