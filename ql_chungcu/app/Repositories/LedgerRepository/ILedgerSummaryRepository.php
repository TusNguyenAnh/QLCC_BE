<?php

namespace App\Repositories\LedgerRepository;

interface ILedgerSummaryRepository
{
    public function findByMonth(int $month,int $year,string $complexId);
    public function findByMonthNearest(int $month,int $year,string $complexId);
    public function findByMonthAndBuilding(int $month, int $year, string $complexId,string $buildingId);
    public function findByMonthAndBuildingNearest(int $month, int $year, string $complexId,string $buildingId);

    public function store(array $data);
    public function update(array $data, string $id);
    public function updateManySummary(array $data);
}
