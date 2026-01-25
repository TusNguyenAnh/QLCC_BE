<?php

namespace App\Repositories\ApartmentRepository;

use App\Models\Apartment;

interface IApartmentRepository
{
    public function getById(string $id);
    public function findByBuildingId(string $bdId, string $perPage);
    public function findByBuildingAndAptNumber(string $bdId, string $aptNumber);

    public function store(array $data);
    public function storeFromFile(array $data);
    public function update(array $data, string $id);
    public function getAptIdByBuildingAndApartmentNumber(array $apartmentPairs,$complexId);
}
