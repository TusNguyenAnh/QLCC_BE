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
        Schema::create('revenues', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('task_id');
            $table->uuid('building_id')->nullable();
            $table->uuid('apartment_id')->nullable();
            $table->string('title')->comment('Tiêu đề khoản thu');

            // Lớp 1: Nghĩa vụ phải thu (khoản phải thu)
            $table->decimal('original_amount', 12, 2)->comment('Số tiền gốc phải thu (KHÔNG thay đổi)');

            // Cache từ ledgers (tự động tính)
            $table->decimal('amount_paid', 12, 2)->default(0)->comment('Cache: Tổng đã thu từ ledgers');
            $table->enum('status', ['unpaid', 'partial', 'paid', 'overpaid'])->default('unpaid')
                ->comment('Trạng thái: unpaid | partial | paid | overpaid');

            $table->text('description')->nullable()->comment('Mô tả khoản thu');
            $table->uuid('created_by')->comment('Người tạo');
            $table->integer('approved')->default(0);
            $table->uuid('approved_by')->nullable()->comment('Người duyệt');
            $table->timestamp('approved_at')->nullable()->comment('Ngày duyệt');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('apartment_id')->references('id')->on('apartments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenues');
    }
};
