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
        Schema::create('residents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('complex_id');
            $table->string('fullname');
            $table->integer('gender'); // nam 0 nu 1
            $table->string('email');
            $table->datetime('birthday')->nullable();
            $table->string('relationship');
            $table->string('phone_number');
            $table->string('cccd');
            $table->integer('status')->default('0');

//            $table->uuid('org_id')->nullable();
            $table->timestamps(); // Created_at và updated_at
            $table->softDeletes(); // Trường để xoá mềm (soft delete)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
