<?php

namespace App\Repositories\ExpenseRepository;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseRepository implements IExpenseRepository
{
    public function getByFilters(array $filters, int $perPage = 50)
    {
        $query = Expense::query();

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['vendor'])) {
            $query->where('vendor', 'like', '%' . $filters['vendor'] . '%');
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['approved'])) {
            $query->where('approved', $filters['approved']);
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

        $expenses = $query->paginate($perPage);

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

    public function approveExpense(array $listExpense, string $approvedBy)
    {
        return DB::transaction(function () use ($listExpense, $approvedBy) {
            return Expense::whereIn('id', $listExpense)
                ->update([
                    'approved_by' => $approvedBy,
                    'approved_at' => Carbon::now(),
                    'approved' => 1,
                ]);
        });
    }
}
