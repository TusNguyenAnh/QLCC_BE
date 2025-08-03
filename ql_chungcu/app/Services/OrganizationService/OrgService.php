<?php

namespace App\Services\OrganizationService;

use App\Http\Requests\OrganizationRequest\OrganizationRequest;
use App\Models\Organization;
use App\Repositories\OrganizationRepository\IOrgRepository;
use App\Repositories\OrgBuildingRepository\IOrgBuildingRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OrgService implements IOrgService
{
    private IOrgRepository $orgRepository;
    private IOrgBuildingRepository $orgBuildingRepository;


    public function __construct(IOrgRepository $orgRepository, IOrgBuildingRepository $orgBuildingRepository)
    {
        $this->orgRepository = $orgRepository;
        $this->orgBuildingRepository = $orgBuildingRepository;
    }

    public function show($perPage)
    {
        return $this->orgRepository->show($perPage);
    }

    public function findById(string $id): ?Organization
    {
        return $this->orgRepository->getById($id);
    }

    public function add(array $data): Organization
    {
        $createdOrg = $this->orgRepository->store(Arr::except($data, ['building']));
        $dataOrgBuilding = [];

        foreach ($data["building"] as $bd) {
            $dataOrgBuilding[] = [
                'id' => (string) Str::uuid(),
                'building_id' => $bd,
                'org_id' => $createdOrg->id
            ];
        }
        $this->orgBuildingRepository->store($dataOrgBuilding);
        return $createdOrg;
    }

    public function update(string $id, array $data): ?Organization
    {
        $this->orgBuildingRepository->delete($id);

        $dataOrgBuilding = [];

        foreach ($data["building"] as $bd) {
            $dataOrgBuilding[] = [
                'id' => (string) Str::uuid(),
                'building_id' => $bd,
                'org_id' => $id
            ];
        }

        $this->orgBuildingRepository->store($dataOrgBuilding);


        return $this->orgRepository->update(Arr::except($data, ['building']), $id);
    }

    public function delete(array $listOrg): ?Organization
    {
        return $this->orgRepository->delete($listOrg);
    }

    public function getAllWithoutChild($parentOrgId)
    {
        return $this->orgRepository->getAllWithoutChild($parentOrgId);
    }
}
