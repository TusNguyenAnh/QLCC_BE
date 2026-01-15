<?php

namespace App\Repositories\AptResidentRepository;

interface IAptResidentRepository
{
    public function findResidentByBuildingId($bdId,$orgId);
    public function store(array $data);
}
