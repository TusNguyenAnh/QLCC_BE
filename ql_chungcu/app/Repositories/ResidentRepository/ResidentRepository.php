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


    // chua xong
    public function findByBuildingId($bdId, $perPage)
    {
        return Resident::join('apartments', 'residents.apt_id', '=', 'apartments.user_id')
            ->join('comments', 'posts.id', '=', 'comments.post_id')
            ->where('users.status', 'active')
            ->where('comments.approved', true)
            ->select('users.name', 'posts.title', 'comments.content')
            ->get();
    }

    public function addResInOrg(array $id, string $org_id)
    {
        Resident::whereIn('id', $id)->update(['org_id' => $org_id]);
    }
}
