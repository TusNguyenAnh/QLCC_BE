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
        return User::create($data)->fresh();
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

}
