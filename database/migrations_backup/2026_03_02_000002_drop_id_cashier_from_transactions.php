<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Hapus kolom id_cashier dari transactions
 * Karena yang memproses pembayaran = admin (id_admin) juga
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'id_cashier')) {
                $table->dropColumn('id_cashier');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'id_cashier')) {
                $table->unsignedBigInteger('id_cashier')->nullable()->after('id_admin');
            }
        });
    }
};
