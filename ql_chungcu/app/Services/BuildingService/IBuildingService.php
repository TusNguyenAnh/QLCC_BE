<?php

namespace App\Services\BuildingService;

use App\Models\Building;

interface IBuildingService
{
    public function show($complexId);

    public function findById(string $id): ?Building;

    public function add(array $data): Building;

    public function update(string $id, array $data): ?Building;
    public function updateRatio(array $data);
    public function delete(array $listBd): ?Building;
}
