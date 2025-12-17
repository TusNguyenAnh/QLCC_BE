<?php

namespace App\Services\LedgerService;

use App\Enums\Constant;
use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Models\LedgerSummary;
use App\Repositories\LedgerRepository\ILedgerRepository;
use App\Repositories\LedgerRepository\ILedgerSummaryRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class LedgerSummaryService implements ILedgerSummaryService
{
    private ILedgerSummaryRepository $ledgerSummaryRepository;
    private ILedgerRepository $ledgerRepository;

    public function __construct(ILedgerSummaryRepository $ledgerSummaryRepository, ILedgerRepository $ledgerRepository)
    {
        $this->ledgerSummaryRepository = $ledgerSummaryRepository;
        $this->ledgerRepository = $ledgerRepository;
    }

    public function createLedgerSummary(array $data)
    {
        $legerSummary = $this->ledgerSummaryRepository->findByMonth($data['month'], $data['year'], $data['complex_id']);
        if ($legerSummary) {
            throw new AppException(ErrorCode::LEDGER_SUMMARY_EXISTED);
        }

        $createLedgerSummary = $this->calculateLedgerSummary((int)$data['month'], (int)$data['year'], $data['complex_id']);
        $createLedgerSummary['complex_id'] = $data['complex_id'];
        return $this->ledgerSummaryRepository->store($createLedgerSummary);
    }

    /**
     * @throws AppException
     */
    public function updateManyLedgerSummary(array $data)
    {
        $dataUpdate = $this->recalculateLedgerSummary($data['month'], $data['year'], $data['complex_id']);
        return $this->ledgerSummaryRepository->updateManySummary($dataUpdate);
    }

    // 2 th can tinh lai
    //th1: khi nhan tao ledger summary ma chua ket thuc thang -> update ledger summary thang hien tai
    //th2: khi tao adjust cho 1 ledger trong 1 thang trong qua khu -> update ledger thang trong qua khu cho den thang hien tai
    /**
     * @throws AppException
     */
    private function recalculateLedgerSummary(int $month, int $year, string $complex_id)
    {
        $dataUpdate = [];
        // lay ra khoang thoi gian can update summary (tu thang tao ledger/adjust -> thang truoc thoi diem hien tai)
        $startTime = Carbon::create($year, $month, 1)->startOfMonth();
        $endTime = now()->startOfMonth()->subMonth();;

        //update summary cua tung thang
        while ($startTime <= $endTime) {
            $ledgerSummary = $this->ledgerSummaryRepository->findByMonth($startTime->month, $startTime->year, $complex_id);
            if ($ledgerSummary) {
                $data = $this->calculateLedgerSummary($startTime->month, $startTime->year, $complex_id);
                $dataUpdate[] = [
                    'id' => $ledgerSummary->id,
                    'total_in' => $data['total_in'],
                    'total_out' => $data['total_out'],
                    'opening_balance' => $data['opening_balance'],
                    'closing_balance' => $data['closing_balance'],
                ];
            }
            $startTime->addMonth();
        }

        return $dataUpdate;
    }

    /**
     * @throws AppException
     */
    private function calculateLedgerSummary(int $month, int $year, string $complex_id)
    {
        // Tổng thu – chi trong tháng
        $legerRevenue = $this->ledgerRepository->getByTypeAndMonth(Constant::REVENUE->value, $month, $year, $complex_id);
        $legerExpense = $this->ledgerRepository->getByTypeAndMonth(Constant::EXPENSE->value, $month, $year, $complex_id);

        $totalIn = 0;
        $totalOut = 0;

        if ($legerRevenue->count() != 0) {
            foreach ($legerRevenue as $ledger) {
                $totalIn += $ledger->final_amount;  // amount + SUM(adjustments)
            }
        }

        if ($legerExpense->count() != 0) {
            foreach ($legerExpense as $ledger) {
                $totalOut += $ledger->final_amount;  // amount + SUM(adjustments)
            }
        }

        $preTime = Carbon::create($year, $month, 1)->subMonth();
        $legerSummaryPre = $this->ledgerSummaryRepository->findByMonth($preTime->month, $preTime->year, $complex_id);

        if (!$legerSummaryPre) {
            throw new AppException(ErrorCode::NOT_FOUND);
        }

        $opening = $legerSummaryPre->closing_balance;
        $closing = $opening + ($totalIn - $totalOut);

        return $data = [
            'month' => $month,
            'year' => $year,
            'total_in' => $totalIn,
            'total_out' => $totalOut,
            'opening_balance' => $opening,
            'closing_balance' => $closing,
        ];
    }


}
