<?php

namespace App\Services\OrganizationService;

use App\Http\Requests\OrganizationRequest\OrganizationRequest;
use App\Models\Organization;
use App\Repositories\OrganizationRepository\IOrgRepository;

class OrgService implements IOrgService
{
    private IOrgRepository $orgRepository;

    public function __construct(IOrgRepository $orgRepository)
    {
        $this->orgRepository = $orgRepository;
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
        return $this->orgRepository->store($data);
    }

    public function update(string $id, array $data): ?Organization
    {
        return $this->orgRepository->update($data, $id);
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
