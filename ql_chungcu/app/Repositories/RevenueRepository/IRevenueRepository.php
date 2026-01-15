<?php

namespace App\Repositories\RevenueRepository;

use App\Models\Revenue;
use Illuminate\Pagination\Paginator;

interface IRevenueRepository
{
    public function findById(string $id): ?Revenue;

    public function getByFilters(array $filters, int $perPage,string $complexId);

    public function getApartmentsWithoutRevenueByMonth(string $buildingId, int $year, int $month);

    public function store(array $data);

    public function update(string $id, array $data): ?Revenue;

    public function delete(string $id): bool;
    public function approveRevenue(array $listRevenue, string $approvedBy);
    public function findByTaskId(string $taskId);

}
