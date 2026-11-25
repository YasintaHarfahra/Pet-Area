<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Update transactions table untuk skema kasir baru
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'nomor_transaksi')) {
                $table->string('nomor_transaksi', 30)->nullable()->after('id');
            }
            
            if (!Schema::hasColumn('transactions', 'total_biaya')) {
                $table->decimal('total_biaya', 12, 2)->default(0);
            }
            
            if (!Schema::hasColumn('transactions', 'jumlah_bayar')) {
                $table->decimal('jumlah_bayar', 12, 2)->default(0);
            }
            
            if (!Schema::hasColumn('transactions', 'kembalian')) {
                $table->decimal('kembalian', 12, 2)->default(0);
            }
            
            if (!Schema::hasColumn('transactions', 'status_bayar')) {
                $table->enum('status_bayar', ['belum_bayar', 'lunas'])->default('belum_bayar');
            }
            
            if (!Schema::hasColumn('transactions', 'metode_bayar')) {
                $table->enum('metode_bayar', ['tunai', 'transfer', 'qris'])->nullable();
            }
            
            if (!Schema::hasColumn('transactions', 'cashier_id')) {
                $table->foreignId('cashier_id')->nullable();
            }
            
            if (!Schema::hasColumn('transactions', 'tgl_bayar')) {
                $table->timestamp('tgl_bayar')->nullable();
            }
            
            if (!Schema::hasColumn('transactions', 'catatan')) {
                $table->text('catatan')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $columns = [
                'nomor_transaksi', 'total_biaya', 'jumlah_bayar', 'kembalian',
                'status_bayar', 'metode_bayar', 'cashier_id', 'tgl_bayar', 'catatan'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('transactions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
