<?php

namespace App\Services\ResidentService;

use App\Models\Resident;

interface IResidentService
{
    public function show(array $filters);
    public function add(array $data): ?Resident;
    public function addResInOrg(array $ids, string $org_id);
    public function removeResInOrg(array $ids, string $org_id);
    public function updatePosition($userId, $orgId, $roleId);
    public function findByOrgId($orgId);
    public function findResidentByBuildingId($bdId,$orgId);
    public function importResFromExcel($file);
    public function importResAptFromExcel($file);

}
