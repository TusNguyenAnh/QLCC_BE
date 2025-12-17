<?php

namespace App\Services\RevenueService;

use App\Models\Revenue;

interface IRevenueService
{
    public function getRevenueByFilters(array $filters, int $perPage = 50);
    public function createRevenue(array $data);
    public function updateRevenue(string $id, array $data): ?Revenue;
    public function deleteRevenue(string $id): bool;
    public function generateMonthlyRevenues(string $buildingId, int $year, int $month);
}
