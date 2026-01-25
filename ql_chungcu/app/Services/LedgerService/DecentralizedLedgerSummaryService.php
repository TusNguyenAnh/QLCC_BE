<?php

namespace App\Services\LedgerService;

use App\Enums\Constant;
use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Repositories\LedgerRepository\ILedgerRepository;
use App\Repositories\LedgerRepository\ILedgerSummaryRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Exception;

class DecentralizedLedgerSummaryService implements ILedgerSummaryService
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
        DB::beginTransaction();
        try {
//            $legerSummary = $this->ledgerSummaryRepository->findByMonthAndBuilding($data['month'], $data['year'], $data['complex_id'], $data['building_id']);
//            if ($legerSummary) {
//                $this->recalculateLedgerSummary($data['month'], $data['year'], $data['complex_id'], $data['building_id']);
//            } else {
            //truong hop chua co ledger summary cua thang tao ledger
            $legerSummaryNearest = $this->ledgerSummaryRepository->findByMonthAndBuildingNearest($data['month'], $data['year'], $data['complex_id'], $data['building_id']);
            if ($legerSummaryNearest) {
                $nextTime = Carbon::create($legerSummaryNearest->year, $legerSummaryNearest->month, 1)->addMonth();
                $this->recalculateLedgerSummary($nextTime->month, $nextTime->year, $legerSummaryNearest->complex_id, $legerSummaryNearest->building_id);
            } else {
                $preTime = Carbon::create($data['year'], $data['month'], 1)->subMonth();
                $initLedgerSummary = [
                    'id' => (string)Str::uuid(),
                    'complex_id' => $data['complex_id'],
                    'building_id' => $data['building_id'],
                    'year' => $preTime->year,
                    'month' => $preTime->month,
                    'total_in' => 0,
                    'total_out' => 0,
                    'opening_balance' => 0,
                    'closing_balance' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
                $this->ledgerSummaryRepository->store($initLedgerSummary);
//                $createLedgerSummary = $this->calculateLedgerSummary((int)$data['month'], (int)$data['year'], $data['complex_id'], $data['building_id']);
//                $createLedgerSummary['complex_id'] = $data['complex_id'];
//                $createLedgerSummary['id'] = (string)Str::uuid();
//                $this->ledgerSummaryRepository->store($createLedgerSummary);
                $this->recalculateLedgerSummary((int)$data['month'], (int)$data['year'], $data['complex_id'], $data['building_id']);
            }
//            }
            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new AppException(ErrorCode::NOT_CREATED);
        }
    }

    public function updateManyLedgerSummary(array $data)
    {
//        $this->recalculateLedgerSummary($data['month'], $data['year'], $data['complex_id'], $data['building_id']);
//        return $this->ledgerSummaryRepository->updateManySummary($dataUpdate);
        return true;
    }

    private function recalculateLedgerSummary(int $month, int $year, string $complex_id, string $building_id)
    {
        DB::beginTransaction();
        try {
            $dataUpdate = [];
            // lay ra khoang thoi gian can update summary (tu thang tao ledger/adjust -> thang thoi diem hien tai)
            $startTime = Carbon::create($year, $month, 1)->startOfMonth();
            $endTime = now()->startOfMonth();
            //update summary cua tung thang
            while ($startTime <= $endTime) {
                $ledgerSummary = $this->ledgerSummaryRepository->findByMonthAndBuilding($startTime->month, $startTime->year, $complex_id, $building_id);
                if ($ledgerSummary) {
                    $data = $this->calculateLedgerSummary($startTime->month, $startTime->year, $complex_id, $building_id);
                    $dataUpdate = [
                        'id' => $ledgerSummary->id,
                        'year' => $ledgerSummary->year,
                        'month' => $ledgerSummary->month,
                        'complex_id' => $ledgerSummary->complex_id,
                        'total_in' => $data['total_in'],
                        'total_out' => $data['total_out'],
                        'opening_balance' => $data['opening_balance'],
                        'closing_balance' => $data['closing_balance'],
                    ];
//                    $this->ledgerSummaryRepository->updateManySummary($dataUpdate);
                } else {
                    $data = $this->calculateLedgerSummary($startTime->month, $startTime->year, $complex_id, $building_id);
                    $dataUpdate = [
                        'id' => (string)Str::uuid(),
                        'year' => $startTime->year,
                        'month' => $startTime->month,
                        'complex_id' => $complex_id,
                        'building_id' => $building_id,
                        'total_in' => $data['total_in'],
                        'total_out' => $data['total_out'],
                        'opening_balance' => $data['opening_balance'],
                        'closing_balance' => $data['closing_balance'],
                    ];
                }
                $this->ledgerSummaryRepository->updateManySummary($dataUpdate);
                $startTime->addMonth();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws AppException
     */
    private function calculateLedgerSummary(int $month, int $year, string $complex_id, string $building_id)
    {
        DB::beginTransaction();
        try {

            // Tổng thu – chi trong tháng cua toa
            $legerRevenue = $this->ledgerRepository->getByTypeAndMonthAndBd(Constant::REVENUE->value, $month, $year, $complex_id, $building_id);
            $legerExpense = $this->ledgerRepository->getByTypeAndMonthAndBd(Constant::EXPENSE->value, $month, $year, $complex_id, $building_id);

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
            $legerSummaryPre = $this->ledgerSummaryRepository->findByMonthAndBuilding($preTime->month, $preTime->year, $complex_id, $building_id);

            if (!$legerSummaryPre) {
                throw new AppException(ErrorCode::NOT_FOUND);
            }

            $opening = $legerSummaryPre->closing_balance;
            $closing = $opening + ($totalIn - $totalOut);

            DB::commit();
            return $data = [
                'month' => $month,
                'year' => $year,
                'building_id' => $building_id,
                'total_in' => $totalIn,
                'total_out' => $totalOut,
                'opening_balance' => $opening,
                'closing_balance' => $closing,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
