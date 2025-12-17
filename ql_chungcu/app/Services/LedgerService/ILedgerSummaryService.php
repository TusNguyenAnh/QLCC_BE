<?php

namespace App\Services\LedgerService;

interface ILedgerSummaryService
{
    public function createLedgerSummary(array $data);

    public function updateManyLedgerSummary(array $data);
}
