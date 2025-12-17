<?php

namespace App\Repositories\LedgerRepository;

use App\Models\Ledger;

interface ILedgerRepository
{
    public function getByFilters(array $filters, int $perPage, string $complexId);
    public function getByTypeAndMonth(string $type, string $month, string $year,string $complex_id);
    public function getOldestLedger(string $complexId);
    public function getTotalLedgerAmountByTime(string $type, string $transFrom, string $transTo, string $complexId);
    public function findById(string $id): ?Ledger;
    public function store(array $data): Ledger;
    public function generateVoucherNumber(string $type,$complexId);


}
