<?php

namespace App\Services\OrganizationService;

use App\Http\Requests\OrganizationRequest\OrganizationRequest;
use App\Models\Organization;

interface IOrgService
{
    public function show($complexId,$perPage);
    public function getAllWithoutChild($parentOrgId,$complexId);

    public function findById(string $id): ?Organization;
    public function getBdIdByParentOrgId(string $complexId,string $parentId);
    public function getTopLevel(string $complex_id);
    public function add(array $data): Organization;

    public function update(string $id, array $data): ?Organization;

    public function delete(array $listOrg): ?Organization;
}
