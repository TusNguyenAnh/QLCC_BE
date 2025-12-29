<?php

namespace App\Repositories\ResidentRepository;

interface IResidentRepository
{
    public function show(array $filters, $complexId);

    public function store(array $data);

    public function storeFromFile(array $data);

    public function updateResInOrg(array $id, string $org_id);

    public function findByOrgId($orgId);

    public function findByCondition($field, $listItem, $complexId);

}
