<?php

namespace App\Repositories\LedgerRepository;

use App\Models\LedgerSummary;
use Illuminate\Support\Facades\DB;

class LedgerSummaryRepository implements ILedgerSummaryRepository
{

    public function store(array $data)
    {
        $lgSummary = LedgerSummary::create($data)->fresh();
        return $lgSummary;
    }

    public function update(array $data, string $id)
    {
        $lgSummary = LedgerSummary::where('id', $id)->first();
        if (!$lgSummary) return null;

        $lgSummary->update($data);
        return $lgSummary->fresh();
    }

    public function findByMonth(int $month, int $year, string $complexId)
    {
        return LedgerSummary::where('month', $month)
            ->where('year', $year)
            ->where('complex_id', $complexId)
            ->first();
    }

    public function findByMonthAndBuilding(int $month, int $year, string $complexId, string $buildingId)
    {
        return LedgerSummary::where('month', $month)
            ->where('year', $year)
            ->where('building_id', $buildingId)
            ->where('complex_id', $complexId)
            ->first();
    }

    public function updateManySummary(array $data)
    {
        //upsert neu chua co thi se insert
        return DB::transaction(function () use ($data) {
            $lgSummary = LedgerSummary::upsert(
                $data,          // dữ liệu nhiều dòng
                ['id'],         // unique key để xác định bản ghi đã tồn tại
                ['total_in', 'total_out', 'opening_balance', 'closing_balance']   // các cột được update khi trùng
            );
            return $lgSummary;
        });
    }
}
