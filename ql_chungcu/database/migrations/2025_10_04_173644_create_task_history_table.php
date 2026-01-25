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
        Schema::create('task_history', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('task_id');
            $table->uuid('approver_id')->nullable(); //id cua nguoi duyet task
            $table->uuid('org_id');
            $table->integer('step_order')->default('1');
            $table->enum('action', ['PENDING','APPROVED', 'REJECTED','UNFINISHED'])->default('APPROVED');
            $table->string('comment')->nullable();

            $table->timestamps(); // Created_at và updated_at
            $table->softDeletes(); // Trường để xoá mềm (soft delete)

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_history');
    }
};
