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
        Schema::create('task', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('complex_id');
            $table->uuid('workflow_id');
            $table->uuid('current_step_id');
            $table->uuid('user_id');

            $table->string('task_name');
            $table->string('description')->default('');
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED', 'IN_PROGRESS'])->default('PENDING');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};
