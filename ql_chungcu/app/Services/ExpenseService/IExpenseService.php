<?php

namespace App\Services\ExpenseService;

use App\Models\Expense;

interface IExpenseService
{
    public function createExpense(array $data);

    public function updateExpense(string $id, array $data): ?Expense;

    public function deleteExpense(string $id): bool;

    public function getExpenseByFilters(array $filters, int $perPage, string $complexId);

    public function approveExpense(array $ids, string $approvedBy);
}
