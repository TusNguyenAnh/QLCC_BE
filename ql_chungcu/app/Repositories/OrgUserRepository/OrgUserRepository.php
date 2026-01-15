<?php

namespace App\Repositories\OrgUserRepository;

use App\Models\OrgUser;

class OrgUserRepository implements IOrgUserRepository
{
    public function store(array $data)
    {
        $orgUser = OrgUser::insert($data);
        return $orgUser;
    }

    public function update(string $userId, string $orgId, string $role_id)
    {
        $orgUser = OrgUser::where('org_id', $orgId)
            ->where('user_id', $userId)
            ->first();
        if (!$orgUser) return null;

        $orgUser->update([
            'role_id' => $role_id
        ]);
        return $orgUser->fresh();
    }

    public function delete(array $userIds, string $org_id)
    {
        $orgUser = OrgUser::whereIn('user_id', $userIds)
            ->where('org_id', $org_id)
            ->forceDelete();
        return $orgUser;
    }

    public function findByOrgId(string $orgId, array $role_id)
    {
        return OrgUser::where('org_id', $orgId)
            ->whereIn('role_id', $role_id)
            ->pluck('user_id')
            ->toArray();
    }

    public function getRoleByUserId(string $userId, string $org_id)
    {
        return OrgUser::where('org_id', $org_id)
            ->where('user_id', $userId)
            ->value('role_id');
    }

    public function findUserByOrgId(string $userId, string $org_id)
    {
        return OrgUser::where('user_id', $userId)
            ->where('org_id', $org_id)
            ->first();
    }
}
