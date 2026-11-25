<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make legacy columns nullable using Laravel Schema Builder (cross-DB compatible)
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dateTime('visit_date')->nullable()->change();
        });

        // Also check and make other legacy columns nullable if they exist
        $columns = ['symptoms', 'diagnosis', 'treatment', 'prescription', 'notes'];
        foreach ($columns as $column) {
            if (Schema::hasColumn('medical_records', $column)) {
                try {
                    Schema::table('medical_records', function (Blueprint $table) use ($column) {
                        $table->text($column)->nullable()->change();
                    });
                } catch (\Exception $e) {
                    // Column might already be nullable
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't restore NOT NULL as it would break existing data
    }
};
