<?php

namespace App\Repositories\ComplexRepository;

interface IComplexRepository
{
    public function show($filters, $status, $perPage);

    public function getById(string $id);

    public function store(array $data);

    public function update(array $data, string $id);

    public function approveComplex(array $listCpl);

    public function delete(array $listCpl);

    public function findByComplexName(string $complexName);

    public function findByComplexAddress(string $complexAddress);
    public function findByComplexPhoneContact(string $phoneContact);


}
