<?php

namespace App\Services\OrganizationService;

use App\Http\Requests\OrganizationRequest\OrganizationRequest;
use App\Models\Organization;

interface IOrgService
{
    public function show($perPage);
    public function getAllWithoutChild($parentOrgId);

    public function findById(string $id): ?Organization;

    public function add(array $data): Organization;

    public function update(string $id, array $data): ?Organization;

    public function delete(array $listOrg): ?Organization;
}
