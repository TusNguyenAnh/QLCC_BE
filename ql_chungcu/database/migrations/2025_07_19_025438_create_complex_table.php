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
        Schema::create('complex', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('complex_name')->unique();
            $table->string('address')->unique();
            $table->integer('total_building')->default(1);
            $table->integer('total_apartment')->default(1);
            $table->string('name_contact');
            $table->string('phone_contact');
            $table->string('email_contact');
            $table->string('description')->default('');
            $table->string('financial_model')->nullable();
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
        Schema::dropIfExists('complex');
    }
};
