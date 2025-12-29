<?php

namespace App\Repositories\UserRepository;

use App\Models\User;

class UserRepository implements IUserRepository
{
    public function show($perPage = 10)
    {
        return User::where('status', 0)
            ->paginate($perPage);
    }

    public function findById($id)
    {
        return User::find($id) ?? null;
    }

    public function findByUsername($username)
    {
        return User::where('username', $username)->first();
    }

    public function store(array $data)
    {
        $user = User::insert($data);
        return $user;
    }

    public function update($id, array $data)
    {
        $user = $this->findById($id);

        $user->update([
            'refresh_token' => $data['refresh_token'] ?? $user->refresh_token
        ]);

        return $user->fresh();
    }

    public function getBuildingIdsManage($userId)
    {
        return User::query()
            ->join('residents', 'users.res_id', '=', 'residents.id')
            ->join('org_building', 'residents.org_id', '=', 'org_building.org_id')
            ->where('users.id', $userId)
            ->pluck('org_building.building_id')
            ->toArray();
    }

    public function findByOrgId($orgId, $table)
    {
        $tab = $table['table'];
        return User::join($tab, $table['left'], '=', $table['right'])
            ->where([
                ['users.status', '=', '0'],
                [$table['org'], '=', $orgId],
            ])
            ->select('users.*', "$tab.email", "$tab.phone_number", "$tab.fullname")
            ->get();
    }

    public function findByCondition($field, $listItem, $complexId)
    {
        return User::whereIn($field, $listItem)
            ->where('complex_id', $complexId)
            ->pluck('id', $field);
    }

    public function findByBuildingId(array $filters, $complexId)
    {
        $query = User::join('residents', 'users.res_id', '=', 'residents.id')
            ->join('apt_res', 'residents.id', '=', 'apt_res.resident_id')
            ->join('apartments', 'apt_res.apt_id', '=', 'apartments.id')
            ->join('buildings', 'apartments.building_id', '=', 'buildings.id')
            ->where('residents.complex_id', $complexId)
            ->select('users.*', 'residents.email', 'residents.phone_number', 'residents.fullname');

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
}
