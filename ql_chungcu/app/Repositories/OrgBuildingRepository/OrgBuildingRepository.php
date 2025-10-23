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

    public function findByBuildingId(string $bdId)
    {
        $listOrgBuilding = OrgBuilding::join('organization', 'organization.id', '=', 'org_building.org_id')
            ->where('org_building.building_id', $bdId)
            ->select('org_building.*', 'organization.org_name', 'organization.level') // chọn thêm cột từ org
            ->get();
        return $listOrgBuilding;
    }
}
