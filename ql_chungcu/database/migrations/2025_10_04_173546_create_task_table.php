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
            $table->uuid('tasktype_id')->default('');
            $table->uuid('current_org_id');

            $table->uuid('creator');// ng tao task

            $table->integer('current_step')->nullable();
            $table->string('task_name');
            $table->string('description')->default('');
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED','UNFINISHED'])->default('PENDING');
            $table->string('category')->nullable();

            $table->timestamps(); // Created_at và updated_at
            $table->softDeletes(); // Trường để xoá mềm (soft delete)
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
