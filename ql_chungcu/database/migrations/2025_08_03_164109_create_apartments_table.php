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
        Schema::create('apartments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('complex_id')->default('');
            $table->uuid('building_id');

            $table->string('apt_number')->default('');
            $table->float('apt_area')->default(2.0);
            $table->text('apt_type');
            $table->text('description')->nullable();
            $table->integer('floor')->default('1');
            $table->integer('status')->default('0');

            $table->timestamps(); // Created_at và updated_at
            $table->softDeletes(); // Trường để xoá mềm (soft delete)

            $table->unique(['building_id', 'apt_number']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
