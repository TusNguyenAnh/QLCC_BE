<?php

namespace App\Repositories\RevenueRepository;

use App\Models\Revenue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueRepository implements IRevenueRepository
{
    public function findById(string $id): ?Revenue
    {
        return Revenue::find($id);
    }

    public function findByTaskId(string $taskId)
    {
        $revenue = Revenue::where('task_id', $taskId)->get();
        return $revenue;
    }

    public function getByFilters(array $filters, int $perPage, string $complexId)
    {
        $query = Revenue::join('task', 'task.id', '=', 'revenues.task_id')
            ->where('task.complex_id', $complexId);

        if (isset($filters['building_id'])) {
            $query->where('building_id', $filters['building_id']);
        }

        if (isset($filters['status'])) {
            $query->where('revenues.status', $filters['status']);
        }

        if (isset($filters['approved'])) {
            $query->where('approved', $filters['approved']);
        }

        $query->when($filters['proposed_from'] ?? null,
            fn($q, $v) => $q->whereDate('approved_at', '>=', $v)
        );

        $query->when($filters['proposed_to'] ?? null,
            fn($q, $v) => $q->whereDate('approved_at', '<=', $v)
        );

        $query->orderBy('approved_at', 'desc');

        $summary = (clone $query)->selectRaw(
            'SUM(amount_paid) as paid,
            SUM(original_amount) as total_expect')->first();

        $revenues = $query
            ->select('revenues.*')
            ->paginate($perPage);

        return [
            'revenues' => $revenues,
            'summary' => $summary,
        ];
    }

    public function getApartmentsWithoutRevenueByMonth(string $buildingId, int $year, int $month)
    {
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

    public function approveRevenue(array $listRevenue, string $approvedBy)
    {
        return DB::transaction(function () use ($listRevenue, $approvedBy) {
            return Revenue::whereIn('task_id', $listRevenue)
                ->update([
                    'approved_by' => $approvedBy,
                    'approved_at' => Carbon::now(),
                    'approved' => 1,
                ]);
        });
    }
}
