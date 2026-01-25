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
        Schema::create('expenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('task_id');
            $table->uuid('building_id')->nullable();

            // Lớp 1: Nghĩa vụ phải chi (khoản phải chi)
            $table->string('title')->comment('Tiêu đề khoản chi');
            $table->string('category');
            $table->decimal('original_amount', 12, 2)->comment('Số tiền gốc phải chi (KHÔNG thay đổi)');
            $table->text('description')->nullable()->comment('Mô tả chi tiết');

            // Cache từ ledgers (tự động tính)
            $table->decimal('amount_paid', 12, 2)->default(0)->comment('Cache: Tổng đã chi từ ledgers');
            $table->string('status')->default('unpaid');
            // Thông tin bổ sung
            $table->string('vendor')->nullable()->comment('Nhà cung cấp / Đơn vị nhận');
            $table->uuid('created_by')->comment('Người tạo');
            $table->integer('approved')->default(0);
            $table->uuid('approved_by')->nullable()->comment('Người duyệt');
            $table->timestamp('approved_at')->nullable()->comment('Ngày duyệt');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');

            $table->index('category');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};