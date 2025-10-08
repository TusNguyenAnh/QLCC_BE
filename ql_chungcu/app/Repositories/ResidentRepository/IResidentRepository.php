<?php

namespace App\Repositories\ResidentRepository;

interface IResidentRepository
{
    public function show($perPage);
    public function store(array $data);
    public function addResInOrg(array $id,string $org_id);

    public function findByOrgId($orgId,$perPage);
    public function findByBuildingId($bdId,$perPage);

}
