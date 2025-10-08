<?php

namespace App\Repositories\AptResidentRepository;

interface IAptResidentRepository
{
    public function findResidentByBuildingId($bdId,$perPage);
    public function store(array $data);
}
