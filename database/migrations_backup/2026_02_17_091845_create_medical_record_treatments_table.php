<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Baris di bawah ini yang kemungkinan besar hilang/terhapus tadi:
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('medical_record_treatments', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel medical_records
            $table->foreignId('medical_record_id')->constrained('medical_records')->onDelete('cascade');
            // Menghubungkan ke tabel treatments
            $table->foreignId('treatment_id')->constrained('treatments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('medical_record_treatments');
    }
};