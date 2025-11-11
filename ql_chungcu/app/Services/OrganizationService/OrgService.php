<?php

namespace App\Services\OrganizationService;

use App\Http\Requests\OrganizationRequest\OrganizationRequest;
use App\Models\Organization;
use App\Repositories\BuildingRepository\IBuildingRepository;
use App\Repositories\OrganizationRepository\IOrgRepository;
use App\Repositories\OrgBuildingRepository\IOrgBuildingRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OrgService implements IOrgService
{
    private IOrgRepository $orgRepository;
    private IBuildingRepository $buildingRepository;
    private IOrgBuildingRepository $orgBuildingRepository;


    public function __construct(
        IOrgRepository         $orgRepository,
        IOrgBuildingRepository $orgBuildingRepository,
        IBuildingRepository    $buildingRepository
    )
    {
        $this->orgRepository = $orgRepository;
        $this->orgBuildingRepository = $orgBuildingRepository;
        $this->buildingRepository = $buildingRepository;
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
        if ($data['parent_org_id']) {
            $parentOrg = $this->orgRepository->getById($data['parent_org_id']);
            $data['level'] = $parentOrg->level + 1;
        }

        $createdOrg = $this->orgRepository->store(Arr::except($data, ['building']));
        $dataOrgBuilding = [];

        foreach ($data["building"] as $bd) {
            $dataOrgBuilding[] = [
                'id' => (string)Str::uuid(),
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
                'id' => (string)Str::uuid(),
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

    public function getAllWithoutChild($parentOrgId, $complexId)
    {
        return $this->orgRepository->getAllWithoutChild($parentOrgId, $complexId);
    }

    public function getTopLevel(string $complex_id)
    {
        return $this->orgRepository->getTopLevel($complex_id);
    }

    // lay ra cac building ma trong cung 1 cap to chuc chua quan ly toa nha do de them moi/ thay doi su quan ly toa nha
    public function getBdIdByParentOrgId(string $complexId, string $parentId)
    {
        // lay ra tat ca toa nha trong complex
        $buildings = $this->buildingRepository->show($complexId)->pluck('id')->values()->all();

        $buildingManaged = $this->orgBuildingRepository->getBdIdByParentOrgId($parentId);
        return collect($buildings)->diff($buildingManaged)->values()->all();
    }
}
