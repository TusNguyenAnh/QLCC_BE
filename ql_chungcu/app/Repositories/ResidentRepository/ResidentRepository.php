<?php

namespace App\Repositories\ResidentRepository;

use App\Models\Resident;

class ResidentRepository implements IResidentRepository
{
    public function show($perPage = 10)
    {
        return Resident::where('status', 0)
            ->paginate($perPage);
    }

    public function store(array $data)
    {
        $resident = Resident::create($data)->fresh();
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

    public function getComplexId(string $id)
    {
        $query = Resident::join('users', 'residents.id', '=', 'users.res_id')
            ->where('residents.id', $id)
            ->select('users.complex_id')
            ->distinct()
            ->first();

        return $query->complex_id;
    }
}
