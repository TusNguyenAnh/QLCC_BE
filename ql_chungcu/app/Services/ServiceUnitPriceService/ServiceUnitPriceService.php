<?php

namespace App\Services\ServiceUnitPriceService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Models\ServiceUnitPrice;
use App\Repositories\RevenueRepository\IRevenueRepository;
use App\Repositories\ServiceUnitPriceRepository\IServiceUnitPriceRepository;

class ServiceUnitPriceService implements IServiceUnitPriceService
{
    private IServiceUnitPriceRepository $serviceUnitPriceRepository;
    private IRevenueRepository $revenueRepository;

    public function __construct(IServiceUnitPriceRepository $serviceUnitPriceRepository,IRevenueRepository $revenueRepository)
    {
        $this->serviceUnitPriceRepository = $serviceUnitPriceRepository;
        $this->revenueRepository = $revenueRepository;
    }

    public function getAll($perPage, $complexId)
    {
        return $this->serviceUnitPriceRepository->all($perPage,$complexId);
    }

    public function getByYear(int $year, $perPage, $complexId): ?ServiceUnitPrice
    {
        return $this->serviceUnitPriceRepository->findByYear($year,$perPage,$complexId);
    }

    public function create(array $data): ServiceUnitPrice
    {
        return $this->serviceUnitPriceRepository->store($data);
    }

    public function update(string $id, array $data): ?ServiceUnitPrice
    {
        $price = $this->serviceUnitPriceRepository->findById($id);
        if ($price == null) {
            throw new AppException(ErrorCode::PRICE_NON_EXISTED);
        }

        $revenue = $this->revenueRepository->getByFilters(['year' => $price->year, 'month' => $price->month]);
        if ($revenue) {
            throw new AppException(ErrorCode::PRICE_HAS_REVENUE);
        }
        return $this->serviceUnitPriceRepository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        $price = $this->serviceUnitPriceRepository->findById($id);
        if ($price == null) {
            throw new AppException(ErrorCode::PRICE_NON_EXISTED);
        }

        $revenue = $this->revenueRepository->getByFilters(['year' => $price->year, 'month' => $price->month]);
        if ($revenue) {
            throw new AppException(ErrorCode::PRICE_HAS_REVENUE);
        }

        return $this->serviceUnitPriceRepository->delete($id);
    }
}
