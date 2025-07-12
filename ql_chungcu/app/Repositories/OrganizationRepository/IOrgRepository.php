<?php

namespace App\Repositories\OrganizationRepository;

interface IOrgRepository
{
    public function show($perPage);
//    public function getById(string $id);
//    public function getByBookingId(string $bookingId);
    public function getAllWithoutChild($parentOrgId);

    public function store(array $data);

    public function update(array $data, string $id);

    public function delete(array $listOrg);
}
