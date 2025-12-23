<?php

namespace App\Services\ApartmentService;

use App\Models\Apartment;

interface IApartmentService
{
    public function findByBuildingId(string $bdId, string $perPage);

    public function add(array $data): Apartment;
    public function importAptFromExcel($file);
    public function update(string $id, array $data): ?Apartment;

}
