<?php

namespace App\Services\ExpenseService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Models\Expense;
use App\Repositories\ExpenseRepository\IExpenseRepository;
use App\Services\LedgerService\ILedgerService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Exception;

class CentralizedExpenseService implements IExpenseService
{
    private IExpenseRepository $expenseRepository;

    public function __construct(
        IExpenseRepository $expenseRepository,
    )
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function getExpenseByFilters(array $filters, int $perPage, string $complexId)
    {
        return $this->expenseRepository->getByFilters($filters, $perPage, $complexId);
    }

    public function createExpense(array $data)
    {
        $dataExpense = [];
        $expenseType = $data['expense_type'];

        $bdId = $expenseType != 0 ? $data['building_id'][0] : null;
        $dataExpense[] = [
            'id' => (string)Str::uuid(),
            'task_id' => $data['task_id'],
            'title' => $data['title'],
            'category' => $data['category'],
            'original_amount' => $data['original_amount'],
            'description' => $data['description'],
            'vendor' => $data['vendor'],
            'building_id' => $bdId,
            'created_by' => jwt_claim('sub'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        return $this->expenseRepository->store($dataExpense);
    }

    public function updateExpense(string $id, array $data): ?Expense
    {
        $expense = $this->expenseRepository->findById($id);
        if ($expense === null || $expense->ledgers()->count() != 0) {
            throw new AppException(ErrorCode::EXPENSE_NOT_UPDATE);
        }
        return $this->expenseRepository->update($id, $data);
    }

    public function deleteExpense(string $id): bool
    {
        $expense = $this->expenseRepository->findById($id);
        if ($expense === null || $expense->ledgers()->count() != 0) {
            throw new AppException(ErrorCode::EXPENSE_NOT_UPDATE);
        }
        return $this->expenseRepository->delete($id);
    }

    //Duyệt khoản chi
    public function approveExpense(array $ids, string $approvedBy)
    {
        try {
            return $this->expenseRepository->approveExpense($ids, $approvedBy);
        } catch (Exception $exception) {
            throw new AppException(ErrorCode::EXPENSE_NOT_UPDATE);
        }
    }
}
