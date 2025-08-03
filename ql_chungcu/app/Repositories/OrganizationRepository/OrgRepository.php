<?php

namespace App\Repositories\OrganizationRepository;

use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class OrgRepository implements IOrgRepository
{
    public function show($perPage = 10)
    {
        return Organization::with(['children','buildings'])
            ->whereNull('parent_org_id') // chỉ bản ghi cha
            ->paginate($perPage);
    }

    public function getAllWithoutChild($parentOrgId)
    {
        // Bước 1: Truy vấn CTE đệ quy để lấy ID các con cháu
        $sql = "
        WITH RECURSIVE excluded AS (
        -- Bước 1: lấy các con trực tiếp của target node
        SELECT id
        FROM organization
        WHERE parent_org_id = ?

        UNION ALL

        -- Bước 2: tiếp tục tìm con của các node đã tìm được
        SELECT o.id
        FROM organization o
        JOIN excluded e ON o.parent_org_id = e.id
        )

        -- Bước 3: Trả ra tất cả tổ chức không nằm trong danh sách bị loại
        SELECT id,org_name
        FROM organization
        WHERE id NOT IN (SELECT id FROM excluded) AND id <> ? AND status = '0';";

        return DB::select($sql, [$parentOrgId,$parentOrgId]);
    }


    public function getById(string $id)
    {
        return Organization::where('id', $id)->first();
    }

    public function store(array $data)
    {
        $org = Organization::create($data)->fresh();
        return $org->load('children');
    }

    public function update(array $data, string $id)
    {
        $org = Organization::where('id', $id)->first();
        if (!$org) return null;

        $org->update($data);
        return $org->fresh();
    }

    public function delete(array $listOrg)
    {
        Organization::whereIn('id', $listOrg)->update(['status' => '1']);
    }

//    public function getByBookingId(string $bookingId){
//        return BookingCourt::where('booking_id', $bookingId)->get();
//    }

}
