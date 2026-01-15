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

    public function getBdIdByParentOrgId(string $parentId)
    {
        return OrgBuilding::join('organization', 'org_building.org_id', '=', 'organization.id')
            ->where('organization.parent_org_id', $parentId)
            ->select('org_building.building_id')
            ->distinct()
            ->pluck('org_building.building_id')
            ->toArray();
    }


    public function delete(string $orgId)
    {
        OrgBuilding::where('org_id', $orgId)->delete();
    }

    public function findByBuildingId(array $bdId)
    {
        $listOrgBuilding = OrgBuilding::join('organization', 'organization.id', '=', 'org_building.org_id')
            ->whereIn('org_building.building_id', $bdId)
            ->groupBy('org_building.org_id', 'organization.org_name', 'organization.level')
            ->havingRaw('COUNT(DISTINCT org_building.building_id) = ?', [count($bdId)])
            ->select(
                'org_building.org_id',
                'organization.org_name',
                'organization.level'
            )
            ->get();
        return $listOrgBuilding;
    }
}
