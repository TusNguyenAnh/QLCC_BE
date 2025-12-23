<?php

namespace App\Repositories\ResidentRepository;

use App\Models\Resident;

class ResidentRepository implements IResidentRepository
{
    public function show(array $filters, $complexId)
    {
        $query = Resident::join('apt_res', 'residents.id', '=', 'apt_res.resident_id')
            ->join('apartments', 'apt_res.apt_id', '=', 'apartments.id')
            ->join('buildings', 'apartments.building_id', '=', 'buildings.id')
            ->where('residents.complex_id', $complexId)
            ->select('residents.*', 'apartments.floor', 'apartments.apt_number', 'buildings.id');

        //Điều kiện lọc khi có request
        $query->when(isset($filters['building_id']),
            fn($q) => $q->where('buildings.id', $filters['building_id'])
        );

        $query->when(isset($filters['floor']),
            fn($q) => $q->where('apartments.floor', $filters['floor'])
        );

        $query->when(isset($filters['apt_number']),
            fn($q) => $q->where('apartments.apt_number', $filters['apt_number'])
        );

        $query->when(isset($filters['relationship']),
            fn($q) => $q->where('residents.relationship', $filters['relationship'])
        );

        $query->orderBy('residents.created_at', 'desc');

        return $query->get();

    }

    public function store(array $data)
    {
        $resident = Resident::create($data)->fresh();
        return $resident;
    }

    public function storeFromFile(array $data)
    {
        $resident = Resident::insert($data);
        return $resident;
    }

    public function findByOrgId($orgId, $perPage = 10)
    {
        return Resident::where([
            ['status', '=', '0'],
            ['org_id', '=', $orgId],
        ])->paginate($perPage);
    }

    public function updateResInOrg(array $id, string $org_id)
    {
        Resident::whereIn('id', $id)->update(['org_id' => $org_id]);
    }

    public function findByCondition($field, $listItem, $complexId)
    {
        return Resident::whereIn($field, $listItem)
            ->where('complex_id', $complexId)
            ->pluck('id', $field);
    }

}
