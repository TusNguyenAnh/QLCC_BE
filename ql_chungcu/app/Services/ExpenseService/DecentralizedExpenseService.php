<?php

namespace App\Services\ExpenseService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Models\Expense;
use App\Repositories\BuildingRepository\IBuildingRepository;
use App\Repositories\ExpenseRepository\IExpenseRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PHPUnit\Exception;

class DecentralizedExpenseService implements IExpenseService
{
    private IExpenseRepository $expenseRepository;
    private IBuildingRepository $buildingRepository;

    public function __construct(
        IExpenseRepository  $expenseRepository,
        IBuildingRepository $buildingRepository,
    )
    {
        $this->expenseRepository = $expenseRepository;
        $this->buildingRepository = $buildingRepository;
    }

    public function createExpense(array $data)
    {
        $finanRatio = $this->buildingRepository->getBuildingRatio($data['building_id'], jwt_claim('complex_id'));
        if (count($finanRatio) == 0) {
            throw new AppException(ErrorCode::NOT_FOUND);
        }
        $dataExpense = [];
        $expenseType = $data['expense_type'];
        foreach ($finanRatio as $buildingId => $ratio) {
            //0: toan khu -> theo ti le ; 1 : noi bo toa
            $amount = $expenseType == 0 ? ((float)$data['original_amount'] * ((float)$ratio / 100)) : (float)$data['original_amount'];
            $dataExpense[] = [
                'id' => (string)Str::uuid(),
                'task_id' => $data['task_id'],
                'title' => $data['title'],
                'category' => $data['category'],
                'original_amount' => $amount,
                'description' => $data['description'],
                'vendor' => $data['vendor'],
                'building_id' => $buildingId,
                'created_by' => jwt_claim('sub'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return $this->expenseRepository->store($dataExpense);
    }

    public function updateExpense(string $id, array $data): ?Expense
    {
        return null;
    }

    public function deleteExpense(string $id): bool
    {
        return true;
    }

    public function getExpenseByFilters(array $filters, int $perPage, $complexId)
    {
        return $this->expenseRepository->getByFilters($filters, $perPage, $complexId);
    }

    public function approveExpense(array $ids, string $approvedBy)
    {
        try {
            return $this->expenseRepository->approveExpense($ids, $approvedBy);
        } catch (Exception $exception) {
            throw new AppException(ErrorCode::EXPENSE_NOT_UPDATE);
        }
    }
}
