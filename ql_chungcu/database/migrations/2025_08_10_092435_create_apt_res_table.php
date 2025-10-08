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
        Schema::create('apt_res', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('apt_id');
            $table->uuid('resident_id');
            $table->integer('status')->default('0');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apt_res');
    }
};
