<?php

namespace App\Repositories\OrgBuildingRepository;

use App\Models\OrgBuilding;

interface IOrgBuildingRepository
{
    public function findByOrgId(string $orgId): ?OrgBuilding;
    public function findByBuildingId(string $bdId);
    public function getBdIdByParentOrgId(string $parentId);
    public function store(array $data);
    public function delete(string $orgId);

}
