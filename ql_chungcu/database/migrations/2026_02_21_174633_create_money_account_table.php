<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('money_account', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('building_id');
            $table->string('bank_name');
            $table->string('account_number');
            $table->integer('term');
            $table->datetime('deposit_date'); // ngay gui
            $table->datetime('maturity_date'); // ngay den han
            $table->float('interest_rate');
            $table->decimal('money', 12, 2);
            $table->string('type');
            $table->boolean('sent')->default(false);
            $table->timestamps(); // Created_at và updated_at
            $table->softDeletes(); // Trường để xoá mềm (soft delete)

            $table->unique(['building_id', 'account_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('money_account');
    }
};
