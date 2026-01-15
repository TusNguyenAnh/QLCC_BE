<?php

namespace App\Repositories\AptResidentRepository;

use App\Models\AptResident;
use Illuminate\Support\Facades\DB;

class AptResidentRepository implements IAptResidentRepository
{

    public function store(array $data)
    {
        $aptResident = AptResident::insert($data);
        return $aptResident;
    }

    public function storeFromFile(array $data)
    {
        $aptResident = AptResident::upsert(
            $data,
            ['apt_id', 'resident_id'], // neu cap field nay k co se them moi con co se update
            ['status', 'updated_at'] //update cac truong nay neu trung cao field tren
        );
        return $aptResident;
    }


    public function findResidentByBuildingId($bdId, $orgId)
    {
        $result = AptResident::join('residents', 'residents.id', '=', 'resident_id')
            ->join('apartments', 'apartments.id', '=', 'apt_id')
            ->join('users', 'users.res_id', '=', 'residents.id')
            ->whereIn('apartments.building_id', $bdId)
            ->whereNotExists(function ($q) use ($orgId) {
                $q->select(DB::raw(1))
                    ->from('org_user')
                    ->whereColumn('org_user.user_id', 'users.id')
                    ->where('org_user.org_id', $orgId);
            })
            ->select(
                'residents.*',
                'users.id as id'
            )
            ->get();

        return $result;
    }
}
