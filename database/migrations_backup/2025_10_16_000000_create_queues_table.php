<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id'); // Tabel patients belum ada di migrasi
            $table->integer('queue_number');
            $table->enum('status', ['waiting', 'in-progress', 'completed', 'cancelled'])->default('waiting');
            $table->date('queue_date');
            $table->text('complaint')->nullable();
            $table->timestamps();
            
            // Index untuk mempercepat pencarian
            $table->index('queue_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};