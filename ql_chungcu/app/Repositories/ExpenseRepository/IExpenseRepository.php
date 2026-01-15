<?php

namespace App\Repositories\ExpenseRepository;

use App\Models\Expense;

interface IExpenseRepository
{
    public function getByFilters(array $filters, int $perPage, string $complexId);
    public function store(array $data);
    public function update(string $id, array $data): ?Expense;
    public function delete(string $id): bool;
    public function findById(string $id): ?Expense;
    public function approveExpense(array $listExpense,string $approvedBy);
    public function findByTaskId(string $taskId);

}
