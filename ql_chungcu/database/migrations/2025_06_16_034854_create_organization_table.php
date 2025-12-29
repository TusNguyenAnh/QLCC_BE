<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization', function (Blueprint $table) {
            // Tạo UUID làm khóa chính
            $table->uuid('id')->primary();

            // cac thuoc tinh khac
            $table->string('org_code')->default('');
            $table->string('org_name')->default('');
            $table->uuid('complex_id')->nullable()->default('');
            $table->uuid('parent_org_id')->nullable();
            $table->text('description')->nullable();
            $table->integer('status')->default('0');
            $table->integer('level')->default('1');

            $table->timestamps(); // Created_at và updated_at
            $table->softDeletes(); // Trường để xoá mềm (soft delete)

//            $table->foreign('parent_org_id')->references('id')->on('organization');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization');
    }
};
