<?php

namespace App\Repositories\BuildingRepository;

interface IBuildingRepository
{
    public function show($complexId);

    public function getById(string $id);

    public function store(array $data);

    public function update(array $data, string $id);
    public function updateRatio(array $data);
    public function delete(array $listBd);

    public function findByCondition($field, $listItem, $complexId);
    public function getBuildingRatio($listItem, $complexId);
}
