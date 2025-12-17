<?php

namespace App\Repositories\RevenueRepository;

use App\Models\Revenue;
use Illuminate\Support\Facades\DB;

class RevenueRepository implements IRevenueRepository
{
    public function findById(string $id): ?Revenue
    {
        return Revenue::find($id);
    }

    public function getByFilters(array $filters, int $perPage = 50)
    {
        $query = Revenue::query();

        if (isset($filters['apartment_id'])) {
            $query->where('apartment_id', $filters['apartment_id']);
        }

        if (isset($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        if (isset($filters['month'])) {
            $query->where('month', $filters['month']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $query->orderBy('year', 'desc')
            ->orderBy('month', 'desc');

        $summary = (clone $query)->selectRaw(
            'SUM(amount_paid) as paid,
            SUM(original_amount) as total_expect')->first();

        $revenues = $query->paginate($perPage);

        return [
            'revenues' => $revenues,
            'summary' => $summary,
        ];
    }
    public function getApartmentsWithoutRevenueByMonth(string $buildingId, int $year, int $month){
        $apartmentIds = DB::table('apartments as a')
            ->leftJoin('revenues as r', function ($join) use ($year, $month) {
                $join->on('r.apartment_id', '=', 'a.id')
                    ->where('r.year', '=', $year)
                    ->where('r.month', '=', $month);
            })
            ->where('a.building_id', $buildingId)
            ->whereNull('r.id')
            ->select('a.id', 'a.apt_area') // Thêm các cột bạn muốn lấy, có thể thêm cột bên revenue nếu cần
            ->get();

        return $apartmentIds;
    }



    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $revenue = Revenue::insert($data);
            return $revenue;
        });
    }

    public function update(string $id, array $data): ?Revenue
    {
        $revenue = Revenue::where('id', $id)->first();
        if (!$revenue) return null;

        $revenue->update($data);
        return $revenue->fresh();
    }

    public function delete(string $id): bool
    {
        $revenue = Revenue::where('id', $id)->first();
        if (!$revenue) return false;

        return $revenue->delete();
    }
}
