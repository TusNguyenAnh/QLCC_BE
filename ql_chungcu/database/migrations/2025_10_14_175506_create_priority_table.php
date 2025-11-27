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
        Schema::create('priority', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('priority_name');
            $table->string('description')->default('');
            $table->integer('weight')->default(1);

            $table->timestamps(); // Created_at và updated_at
            $table->softDeletes(); // Trường để xoá mềm (soft delete)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('priority');
    }
};
