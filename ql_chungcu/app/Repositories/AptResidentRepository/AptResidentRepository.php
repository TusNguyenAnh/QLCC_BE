<?php

namespace App\Repositories\AptResidentRepository;

use App\Models\AptResident;

class AptResidentRepository implements IAptResidentRepository
{

    public function store(array $data)
    {
        $aptResident = AptResident::insert($data);
        return $aptResident;
    }

    public function findResidentByBuildingId($bdId, $perPage = 10)
    {
        return AptResident::join('residents', 'residents.id', '=', 'resident_id')
            ->join('apartments', 'apartments.id', '=', 'apt_id')
            ->whereIn('apartments.building_id', $bdId)
            ->where('residents.org_id', 'null')
            ->select('residents.*') // chỉ lấy các cột của bảng resident
            ->distinct()
            ->paginate($perPage);
    }
}
