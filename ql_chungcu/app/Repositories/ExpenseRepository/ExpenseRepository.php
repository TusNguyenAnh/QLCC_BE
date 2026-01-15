<?php

namespace App\Repositories\ExpenseRepository;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseRepository implements IExpenseRepository
{
    public function getByFilters(array $filters, int $perPage, string $complexId)
    {
        $query = Expense::join('task', 'expenses.task_id', '=', 'task.id')
            ->where('task.complex_id', $complexId);

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['vendor'])) {
            $query->where('vendor', 'like', '%' . $filters['vendor'] . '%');
        }

        if (isset($filters['status'])) {
            $query->where('expenses.status', $filters['status']);
        }

        if (isset($filters['approved'])) {
            $query->where('approved', $filters['approved']);
        }

        if (isset($filters['building_id'])) {
            $query->where('building_id', $filters['building_id']);
        }

        // Filter theo thời gian phê duyệt
        $query->when($filters['proposed_from'] ?? null,
            fn($q, $v) => $q->whereDate('approved_at', '>=', $v)
        );

        $query->when($filters['proposed_to'] ?? null,
            fn($q, $v) => $q->whereDate('approved_at', '<=', $v)
        );

        $summary = (clone $query)->selectRaw('
            SUM(amount_paid) as paid,
            SUM(original_amount) as total_expect')->first();

        $expenses = $query
            ->select('expenses.*')
            ->paginate($perPage);

        return [
            'expenses' => $expenses,
            'summary' => $summary,
        ];
    }

    public function store(array $data)
    {
        $expense = Expense::insert($data);
        return $expense;
    }

    public function update(string $id, array $data): ?Expense
    {
        $expense = Expense::where('id', $id)->first();
        if (!$expense) return null;

        $expense->update($data);
        return $expense->fresh();
    }

    public function delete(string $id): bool
    {
        $expense = Expense::where('id', $id)->first();
        if (!$expense) return false;

        return $expense->delete();
    }

    public function findById(string $id): ?Expense
    {
        return Expense::where('id', $id)->first();
    }

    public function findByTaskId(string $taskId)
    {
        $expense = Expense::where('task_id', $taskId)->get();
        return $expense;
    }

    public function approveExpense(array $listExpense, string $approvedBy)
    {
        return DB::transaction(function () use ($listExpense, $approvedBy) {
            return Expense::whereIn('task_id', $listExpense)
                ->update([
                    'approved_by' => $approvedBy,
                    'approved_at' => Carbon::now(),
                    'approved' => 1,
                ]);
        });
    }
}
