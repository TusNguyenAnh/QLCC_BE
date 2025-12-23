<?php

namespace App\Services\ResidentService;

use App\Models\Resident;

interface IResidentService
{
    public function show(array $filters);
    public function add(array $data): ?Resident;
    public function updateResInOrg(array $id, string $org_id);

    public function findByOrgId($orgId, $perPage);
    public function findResidentByBuildingId($bdId, $perPage);
    public function importResFromExcel($file);
    public function importResAptFromExcel($file);

}
