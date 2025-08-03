<?php

namespace App\Repositories\OrgBuildingRepository;

use App\Models\Building;
use App\Models\OrgBuilding;

class OrgBuildingRepository implements IOrgBuildingRepository
{

    public function store(array $data)
    {
        $orgBuilding = OrgBuilding::insert($data);
        return $orgBuilding;
    }

    public function findByOrgId(string $orgId): ?OrgBuilding
    {
        $listOrgBuilding = OrgBuilding::where('org_id', $orgId)->get();
        return $listOrgBuilding;
    }

    public function delete(string $orgId)
    {
        OrgBuilding::where('org_id', $orgId)->delete();
    }

}
