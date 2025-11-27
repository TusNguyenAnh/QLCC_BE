<?php

namespace App\Repositories\ResidentRepository;

interface IResidentRepository
{
    public function show($perPage);
    public function store(array $data);
    public function updateResInOrg(array $id,string $org_id);
    public function findByOrgId($orgId,$perPage);
    public function getComplexId(string $id);

}
