<?php

namespace App\Repositories\OrganizationRepository;

interface IOrgRepository
{
    public function show($perPage);
    public function getAllWithoutChild($parentOrgId,$complexId);
    public function getById(string $id);
    public function getTopLevel(string $complex_id);
    public function store(array $data);
    public function update(array $data, string $id);
    public function delete(array $listOrg);
}
