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
        Schema::create('task_type', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('complex_id')->default('');
            $table->uuid('workflow_id');
            $table->uuid('priority_id');
            $table->string('type_name');
            $table->string('description')->default('');
            $table->integer('status')->default('0');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_type');
    }
};
