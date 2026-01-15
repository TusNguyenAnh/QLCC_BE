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
        Schema::create('workflow_step', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workflow_id');
//            $table->uuid('org_id')->nullable(); // su dung cho viec dinh nghia 1 wf cho cac cap cu the

            $table->integer('step_order')->default('1');
            $table->integer('org_level')->nullable(); // su dung cho viec dinh nghia wf chung cho he thong
            $table->string('description')->default('');
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
        Schema::dropIfExists('workflow_step');
    }
};
