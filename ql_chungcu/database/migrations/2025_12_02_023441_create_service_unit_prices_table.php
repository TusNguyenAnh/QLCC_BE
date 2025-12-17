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
        Schema::create('service_unit_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('complex_id');

            $table->integer('year');
            $table->integer('month');
            $table->decimal('price_per_m2', 10, 2)->comment('Đơn giá/m²/tháng');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_unit_prices');
    }
};
