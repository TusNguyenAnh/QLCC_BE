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
        Schema::create('ledger_summary', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('complex_id');
            $table->uuid('building_id')->nullable();
            $table->integer('year');
            $table->integer('month');

            // Tổng trong kỳ (1 ky = 1thang)
            $table->decimal('total_in', 15, 2)->default(0);     // tổng thu
            $table->decimal('total_out', 15, 2)->default(0);    // tổng chi

            // Số dư
            $table->decimal('opening_balance', 15, 2)->default(0); // dau ky
            $table->decimal('closing_balance', 15, 2)->default(0); // cuoi ky

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_summary');
    }
};
