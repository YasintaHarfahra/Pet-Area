<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan field untuk fitur pengingat kontrol lanjutan (follow-up reminder)
     */
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            // Tanggal kontrol selanjutnya (opsional)
            $table->date('next_control_date')->nullable()->after('notes');
            
            // Catatan untuk kontrol (opsional, bisa untuk instruksi khusus)
            $table->text('follow_up_notes')->nullable()->after('next_control_date');
            
            // Status kontrol: pending, completed, cancelled
            $table->enum('follow_up_status', ['pending', 'completed', 'cancelled'])->default('pending')->after('follow_up_notes');
            
            // Index untuk query performa
            $table->index('next_control_date');
            $table->index('follow_up_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropIndex(['next_control_date']);
            $table->dropIndex(['follow_up_status']);
            $table->dropColumn(['next_control_date', 'follow_up_notes', 'follow_up_status']);
        });
    }
};
