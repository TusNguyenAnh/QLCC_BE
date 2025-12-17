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
        Schema::create('ledgers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Chứng từ (Số phiếu thu/chi)
            $table->string('voucher_number', 50)->unique()->comment('Số phiếu thu/chi (PT-2025-0001, PC-2025-0001)');

            // Lớp 2: Giao dịch thực tế (IMMUTABLE - KHÔNG được sửa/xóa)
            $table->enum('type', ['revenue', 'expense'])->comment('Loại: revenue (thu) | expense (chi)');

            // Phân loại quỹ
            $table->enum('fund_type', [
                'operating_fund',
                'maintenance_fund',
                'reserve_fund',
                'other_fund'
            ])->default('operating_fund')->comment('Loại quỹ');

            $table->uuid('related_id')->comment('ID của revenue hoặc expense');
            $table->uuid('building_id')->nullable()->comment('Tòa nhà (để tách quỹ riêng)');
            $table->uuid('complex_id');

            // Thông tin giao dịch
            $table->decimal('amount', 15, 2)->comment('Số tiền thực tế (dương)');
            $table->date('transaction_date')->comment('Ngày phát sinh THỰC TẾ');
            $table->enum('payment_method', ['cash', 'bank_transfer'])
                ->default('cash')
                ->comment('Phương thức thanh toán');

            // Thông tin ngân hàng (khi chuyển khoản)
            $table->string('bank_transaction_id', 100)->nullable()->comment('Mã giao dịch ngân hàng');
            $table->string('bank_name', 100)->nullable()->comment('Tên ngân hàng');
            $table->string('bank_account', 50)->nullable()->comment('Số tài khoản');

            $table->text('description')->nullable()->comment('Mô tả giao dịch');

            // Người liên quan
            $table->string('payer_name', 200)->nullable()->comment('Người đóng tiền (khi thu)');
            $table->string('receiver_name', 200)->nullable()->comment('Người nhận tiền (khi chi)');
            $table->string('contact_info', 200)->nullable()->comment('SĐT/Email liên hệ');

            // Người thực hiện
            $table->uuid('created_by')->comment('Người ghi sổ');

            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('set null');

            // Indexes
            $table->index('voucher_number');
            $table->index('type');
            $table->index('fund_type');
            $table->index('transaction_date');
            $table->index('related_id');
            $table->index(['type', 'related_id']);
            $table->index(['fund_type', 'building_id', 'transaction_date']); // Báo cáo theo quỹ
            $table->index(['type', 'transaction_date']); // Sổ quỹ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
