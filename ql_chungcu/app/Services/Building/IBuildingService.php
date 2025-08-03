<?php

namespace App\Services\Building;

use App\Models\Building;

interface IBuildingService
{
    public function show($perPage);

    public function findById(string $id): ?Building;

    public function add(array $data): Building;

    public function update(string $id, array $data): ?Building;

    public function delete(array $listBd): ?Building;
}
