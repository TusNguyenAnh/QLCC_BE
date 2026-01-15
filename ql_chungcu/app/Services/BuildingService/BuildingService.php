<?php

namespace App\Services\BuildingService;

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
}
