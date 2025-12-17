<?php

namespace App\Repositories\AdjustmentTransactionRepository;

use App\Models\AdjustmentTransaction;

class AdjustmentTransactionRepository implements IAdjustmentTransactionRepository
{
    public function store(array $data): AdjustmentTransaction
    {
        return AdjustmentTransaction::create($data)->fresh();
    }

    public function findById(string $id): ?AdjustmentTransaction
    {
        return AdjustmentTransaction::find($id);
    }

    public function getByLedgerId(string $ledgerId)
    {
        return AdjustmentTransaction::where('ledger_id', $ledgerId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTotalAmountByLedger(array $listLedId)
    {
        return AdjustmentTransaction::whereIn('ledger_id', $listLedId)
            ->sum('amount');
    }

}
