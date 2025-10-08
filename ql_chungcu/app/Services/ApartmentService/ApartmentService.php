<?php

namespace App\Services\ApartmentService;

use App\Models\Apartment;
use App\Repositories\ApartmentRepository\IApartmentRepository;

class ApartmentService implements IApartmentService
{
    private IApartmentRepository $apartmentRepository;

    public function __construct(IApartmentRepository $apartmentRepository)
    {
        $this->apartmentRepository = $apartmentRepository;
    }
    public function findByBuildingId(string $bdId,string $perPage)
    {
        return $this->apartmentRepository->findByBuildingId($bdId,$perPage);
    }

    public function add(array $data): Apartment
    {
        return $this->apartmentRepository->store($data);
    }

    public function update(string $id, array $data): ?Apartment
    {
        return $this->apartmentRepository->update($data,$id);
    }
}
