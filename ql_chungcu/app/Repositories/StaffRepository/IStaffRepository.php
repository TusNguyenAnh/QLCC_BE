<?php

namespace App\Repositories\StaffRepository;

interface IStaffRepository
{
    public function store(array $data);
    public function findByCondition($field, $listItem, $complexId);
}
