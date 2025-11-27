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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username')->default('resident');
            $table->string('password')->default('');
            $table->uuid('complex_id')->default('');
            $table->uuid('res_id')->default('');
            $table->integer('status')->default('0');
            $table->string('refresh_token')->default('');

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps(); // Created_at và updated_at
            $table->softDeletes(); // Trường để xoá mềm (soft delete)

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
