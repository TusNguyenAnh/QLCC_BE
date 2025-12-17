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
        Schema::create('adjustment_trans', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Lớp 3: Điều chỉnh LEDGER (KHÔNG sửa ledger, tạo adjustment mới)
            $table->uuid('ledger_id')->comment('ID của ledger bị điều chỉnh');

            // Số tiền điều chỉnh (ÂM để giảm, DƯƠNG để tăng)
            $table->decimal('amount', 12, 2)->comment('Số tiền điều chỉnh: âm (giảm) | dương (tăng)');

            $table->text('reason')->comment('Lý do điều chỉnh chi tiết');

            // Audit trail
            $table->uuid('created_by')->comment('Người tạo điều chỉnh');

            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('ledger_id')->references('id')->on('ledgers')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users');

            // Indexes
            $table->index('ledger_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustment_trans');
    }
};
