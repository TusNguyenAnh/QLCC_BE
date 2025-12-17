<?php

namespace App\Repositories\LedgerRepository;

interface ILedgerSummaryRepository
{
    public function findByMonth(int $month,int $year,string $complexId);
    public function store(array $data);
    public function update(array $data, string $id);
    public function updateManySummary(array $data);
}
