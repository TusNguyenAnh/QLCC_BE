<?php

namespace App\Repositories\ServiceUnitPriceRepository;

use App\Models\ServiceUnitPrice;

interface IServiceUnitPriceRepository
{
    public function all($perPage,$complexId);
    public function findById(int $id): ?ServiceUnitPrice;
    public function findByYear(int $year,$perPage,$complexId): ?ServiceUnitPrice;
    public function findByYearAndMonth(int $year,int $month): ?ServiceUnitPrice;
    public function store(array $data): ServiceUnitPrice;
    public function update(string $id, array $data): ?ServiceUnitPrice;
    public function delete(string $id): bool;
}
