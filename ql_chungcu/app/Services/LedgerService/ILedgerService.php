<?php

namespace App\Services\LedgerService;

use App\Models\Expense;
use App\Models\Revenue;

interface ILedgerService
{
    // Record transactions with actual amount
    // Có thể gọi nhiều lần cho cùng 1 revenue/expense (thanh toán nhiều lần)
    public function storeRevenue(string $revenueId, array $data);

    public function storeExpense(string $expenseId, array $data);

    public function getLedgerByFilters(array $filters, int $perPage, string $complexId);
}
