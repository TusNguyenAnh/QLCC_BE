<?php

namespace App\Repositories\ServiceUnitPriceRepository;

use App\Models\ServiceUnitPrice;

class ServiceUnitPriceRepository implements IServiceUnitPriceRepository
{
    public function all($perPage, $complexId)
    {
        return ServiceUnitPrice::where('complex_id', $complexId)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate($perPage);
    }

    public function findByYear(int $year, $perPage, $complexId): ?ServiceUnitPrice
    {
        return ServiceUnitPrice::where('complex_id', $complexId)
            ->where('year', $year)
            ->orderBy('month', 'desc')
            ->paginate($perPage);
    }

    public function store(array $data): ServiceUnitPrice
    {
        $price = ServiceUnitPrice::create($data)->fresh();
        return $price;
    }

    public function update(string $id, array $data): ?ServiceUnitPrice
    {
        $price = ServiceUnitPrice::where('id', $id)->first();
        if (!$price) return null;

        $price->update($data);
        return $price->fresh();
    }

    public function delete(string $id): bool
    {
        $price = ServiceUnitPrice::where('id', $id)->first();
        if (!$price) return false;

        return $price->delete();
    }

    public function findById(int $id): ?ServiceUnitPrice
    {
        return ServiceUnitPrice::where('id', $id)->first();
    }

    public function findByYearAndMonth(int $year, int $month): ?ServiceUnitPrice
    {
        return ServiceUnitPrice::where('year', $year)
            ->where('month', $month)
            ->first();
    }
}
