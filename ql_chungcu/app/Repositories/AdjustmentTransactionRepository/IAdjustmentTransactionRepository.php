<?php

namespace App\Repositories\AdjustmentTransactionRepository;

use App\Models\AdjustmentTransaction;

interface IAdjustmentTransactionRepository
{
    public function store(array $data);
    public function findById(string $id);
    public function getByLedgerId(string $ledgerId);
    public function getTotalAmountByLedger(array $listLedId);
}
