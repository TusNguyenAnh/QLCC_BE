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
        Schema::create('staffs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('complex_id');
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->integer('status')->default('0');
            $table->timestamps(); // Created_at và updated_at
            $table->softDeletes(); // Trường để xoá mềm (soft delete)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
