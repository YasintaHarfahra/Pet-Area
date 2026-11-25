<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('whats_app_logs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemilik');
            $table->string('nama_hewan')->nullable(); // Tambahkan ini
            $table->string('nomor_wa');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('whats_app_logs');
    }
};