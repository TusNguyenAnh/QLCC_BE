<?php

namespace App\Services\ServiceUnitPriceService;

use App\Models\ServiceUnitPrice;

interface IServiceUnitPriceService
{
    public function getAll($perPage,$complexId);
    public function getByYear(int $year,$perPage,$complexId): ?ServiceUnitPrice;
    public function create(array $data): ServiceUnitPrice;
    public function update(string $id, array $data): ?ServiceUnitPrice;
    public function delete(string $id): bool;
}
