<?php

namespace App\Repositories\BuildingRepository;

interface IBuildingRepository
{
    public function show($perPage);

    public function store(array $data);

    public function update(array $data, string $id);

    public function delete(array $listBd);
}
