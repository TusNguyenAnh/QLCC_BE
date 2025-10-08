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
            $table->string('res_id');
            $table->string('fullname');
            $table->integer('gender');
            $table->string('email')->unique();
            $table->datetime('birthday')->nullable();
            $table->string('relationship');
            $table->string('phone_number')->unique();
            $table->string('cccd')->unique();
            $table->integer('status')->default('0');

            $table->uuid('org_id')->nullable();
            $table->timestamps();
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
