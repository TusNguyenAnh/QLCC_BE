<?php

namespace App\Repositories\ApartmentRepository;

use App\Models\Apartment;

interface IApartmentRepository
{
    public function findByBuildingId(string $bdId, string $perPage);
    public function store(array $data);
    public function storeFromFile(array $data);
    public function update(array $data, string $id);
    public function getAptIdByBuildingAndApartmentNumber(array $apartmentPairs,$complexId);
}
