<?php

namespace App\Services\BuildingService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Models\Building;
use App\Repositories\BuildingRepository\IBuildingRepository;

class BuildingService implements IBuildingService
{
    private IBuildingRepository $buildingRepository;

    public function __construct(IBuildingRepository $buildingRepository)
    {
        $this->buildingRepository = $buildingRepository;
    }

    public function show($complexId)
    {
        return $this->buildingRepository->show($complexId);
    }

    public function findById(string $id): ?Building
    {
        return $this->buildingRepository->getById($id);
    }

    public function add(array $data): Building
    {
        return $this->buildingRepository->store($data);
    }

    public function update(string $id, array $data): ?Building
    {
        return $this->buildingRepository->update($data, $id);
    }

    public function delete(array $listBd): ?Building
    {
        return $this->buildingRepository->delete($listBd);
    }

    public function updateRatio(array $data)
    {
        if (isset($data['ratio'])) {
            $buildingIds = collect($data['ratio'])->pluck('id')
                ->unique()
                ->values()
                ->toArray();

            $buildingExist = $this->buildingRepository->findByCondition('id', $buildingIds, $data['complex_id']);
            if (count($buildingIds) != $buildingExist->count()) {
                throw new AppException(ErrorCode::NOT_FOUND);
            }

            // tinh tong ti le co du 100%
            $totalRatio = collect($data['ratio'])->sum('financial_ratio');
            if ($totalRatio != 100) {
                throw new AppException(ErrorCode::FINANCIAL_TOTAL_RATIO_NOT_VALID);
            }


            $this->buildingRepository->updateRatio($data['ratio']);
        }
    }
}
