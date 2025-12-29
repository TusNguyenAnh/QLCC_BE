<?php

namespace App\Repositories\StaffRepository;

use App\Models\Staff;

class StaffRepository implements IStaffRepository
{
    public function store(array $data)
    {
        $resident = Staff::create($data)->fresh();
        return $resident;
    }
    public function findByCondition($field, $listItem, $complexId)
    {
        return Staff::whereIn($field, $listItem)
            ->where('complex_id', $complexId)
            ->pluck('id', $field);
    }

}
