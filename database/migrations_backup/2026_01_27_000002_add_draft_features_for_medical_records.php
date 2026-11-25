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
        // Add columns to queues table untuk track pemeriksaan yang sedang berjalan
        Schema::table('queues', function (Blueprint $table) {
            $table->foreignId('doctor_id')->nullable()->constrained('users')->onDelete('set null')->after('status');
            $table->timestamp('started_at')->nullable()->after('doctor_id')->comment('Kapan pemeriksaan dimulai');
            $table->timestamp('last_worked_at')->nullable()->after('started_at')->comment('Terakhir diupdate');
        });

        // Create table untuk menyimpan draft medical records (in-progress data)
        Schema::create('medical_record_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_id')->constrained('queues')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            
            // Data rekam medis yang sudah diinput (JSON untuk fleksibilitas)
            $table->json('form_data')->nullable()->comment('Form data yang sedang diisi');
            
            // Status draft: draft, in-progress, completed
            $table->enum('status', ['draft', 'in-progress', 'completed'])->default('draft');
            
            // Tracking
            $table->timestamp('started_at')->comment('Kapan dokter mulai mengisi');
            $table->timestamp('last_updated_at')->comment('Terakhir disimpan');
            $table->timestamps();
            
            // Indexes untuk performa
            $table->index(['queue_id', 'doctor_id']);
            $table->index('status');
            $table->index('doctor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['doctor_id']);
            $table->dropColumn(['doctor_id', 'started_at', 'last_worked_at']);
        });
        
        Schema::dropIfExists('medical_record_drafts');
    }
};
