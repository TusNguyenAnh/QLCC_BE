<?php

namespace App\Repositories\BuildingRepository;

interface IBuildingRepository
{
    public function show($complexId, $perPage);

    public function getById(string $id);

    public function store(array $data);

    public function update(array $data, string $id);

    public function delete(array $listBd);

    public function findByCondition($field, $listItem, $complexId);
}
