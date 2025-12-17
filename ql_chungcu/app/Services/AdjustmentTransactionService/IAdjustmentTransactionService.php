<?php

namespace App\Services\AdjustmentTransactionService;

use App\Models\AdjustmentTransaction;

interface IAdjustmentTransactionService
{
    public function createAdjustment(array $data);
    public function getAdjustmentsByLedger(string $ledgerId);
}
