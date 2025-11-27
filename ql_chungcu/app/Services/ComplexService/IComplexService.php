<?php

namespace App\Services\ComplexService;

interface IComplexService
{
    public function show($complexFilterRequest,$status,$perPage);
    public function findById(string $id);
    public function add(array $data);
    public function update(string $id, array $data);
    public function delete(array $listBd);
    public function approveComplex(array $ids);
    public function rejectComplex(array $ids);


}
