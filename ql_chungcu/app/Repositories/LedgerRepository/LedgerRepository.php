<?php

namespace App\Repositories\LedgerRepository;

use App\Models\Ledger;
use Illuminate\Support\Facades\DB;

class LedgerRepository implements ILedgerRepository
{
    public function getByFilters(array $filters, int $perPage, string $complexId)
    {
        $query = Ledger::query();
        $query->where('complex_id', $complexId);

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['trans_from'])) {
            $query->whereDate('transaction_date', '>=', $filters['trans_from']);
        }

        if (isset($filters['trans_to'])) {
            $query->whereDate('transaction_date', '<=', $filters['trans_to']);
        }

        if (isset($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (isset($filters['building_id'])) {
            $query->where('building_id', $filters['building_id']);
        }

        return $query->orderBy('transaction_date')->paginate($perPage);
    }

    public function findById(string $id): ?Ledger
    {
        return Ledger::where('id', $id)->first();
    }

    public function generateVoucherNumber(string $type, $complexId)
    {
        return DB::transaction(function () use ($type, $complexId) {
            $year = date('Y');

            $maxNumber = Ledger::where('complex_id', $complexId)
                ->where('voucher_number', 'LIKE', "{$type}-{$year}-%")
                ->lockForUpdate()
                ->selectRaw("
                MAX(
                    CAST(SUBSTRING_INDEX(voucher_number, '-', -1) AS UNSIGNED)
                ) as max_number
            ")
                ->value('max_number');

            $nextNumber = ($maxNumber ?? 0) + 1;

            // Pad tối thiểu 4 số – KHÔNG GIỚI HẠN
            $number = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            return "{$type}-{$year}-{$number}";
        });
    }

    public function store(array $data): Ledger
    {
        $ledger = Ledger::create($data)->fresh();
        return $ledger;
    }

    public function getByTypeAndMonth(string $type, string $month, string $year, string $complex_id)
    {
        return Ledger::whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->where('complex_id', $complex_id)
            ->get();
    }

    public function getOldestLedger(string $complexId)
    {
        return Ledger::where('complex_id', $complexId)
            ->min('transaction_date');
    }

    public function getTotalLedgerAmountByTime(string $type, string $transFrom, string $transTo, string $complexId)
    {
        $query = Ledger::query();
        $query->where('complex_id', $complexId)
            ->where('type', $type);

        if ($transFrom) {
            $query->whereDate('transaction_date', '>=', $transFrom);
        }

        if ($transTo) {
            $query->whereDate('transaction_date', '<=', $transTo);
        }

        $ledgers = $query->get();

        $total = 0.0;
        foreach ($ledgers as $l) {
            $total += (float)$l->final_amount;
        }
        return $total;
    }
}
