<?php

namespace App\Services\AdjustmentTransactionService;

use App\Enums\Constant;
use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Models\AdjustmentTransaction;
use App\Models\Ledger;
use App\Repositories\AdjustmentTransactionRepository\IAdjustmentTransactionRepository;
use App\Repositories\ExpenseRepository\IExpenseRepository;
use App\Repositories\LedgerRepository\ILedgerRepository;
use App\Repositories\RevenueRepository\IRevenueRepository;
use App\Services\LedgerService\ILedgerSummaryService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

/**
 * LỚP 3 - ADJUSTMENT SERVICE
 * Quản lý điều chỉnh cho các ledger cụ thể
 * Adjustment luôn tham chiếu đến ledger_id, không tham chiếu revenue/expense
 */
class AdjustmentTransactionService implements IAdjustmentTransactionService
{
    private ILedgerSummaryService $ledgerSummaryService;
    private IAdjustmentTransactionRepository $adjustmentRepository;
    private ILedgerRepository $ledgerRepository;
    private IRevenueRepository $revenueRepository;
    private IExpenseRepository $expenseRepository;

    public function __construct(IAdjustmentTransactionRepository $adjustmentRepository,
                                ILedgerRepository                $ledgerRepository,
                                IRevenueRepository               $revenueRepository,
                                IExpenseRepository               $expenseRepository,
                                ILedgerSummaryService            $ledgerSummaryService
    )
    {
        $this->adjustmentRepository = $adjustmentRepository;
        $this->ledgerRepository = $ledgerRepository;
        $this->revenueRepository = $revenueRepository;
        $this->expenseRepository = $expenseRepository;
        $this->ledgerSummaryService = $ledgerSummaryService;
    }

    public function createAdjustment(array $data)
    {
        DB::beginTransaction();
        try {
            $ledger = $this->ledgerRepository->findById($data['ledger_id']);
            if (!$ledger) {
                throw new AppException(ErrorCode::NOT_FOUND);
            }

            $transDateInYear = Carbon::parse($ledger->transaction_date)->isSameYear(Carbon::now());
            if (!$transDateInYear) {
                throw new AppException(ErrorCode::NOT_CREATED);
            }

            $adjustment = $this->adjustmentRepository->store($data);

            // Cập nhật cache amount_paid của revenue/expense
            $this->updateParentCache($ledger);

            //recalculate summary tu thang cua ledger dc bo sung but toan chinh sua
            $monthLgSummary = $ledger->transaction_date->month;
            $yearLgSummary = $ledger->transaction_date->year;
            $this->ledgerSummaryService->updateManyLedgerSummary(['month' => $monthLgSummary, 'year' => $yearLgSummary, 'complex_id' => $data['complex_id']]);

            DB::commit();
            return $adjustment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    // Cập nhật cache của revenue/expense khi có adjustment
    private function updateParentCache(Ledger $ledger): void
    {
        if ($ledger->type === Constant::REVENUE->value) {
            // Tính lại tổng cho tất cả ledgers của revenue này
            $revenue = $ledger->revenue;
            if (!$revenue) {
                throw new AppException(ErrorCode::NOT_FOUND);
            }
            //tong ledger cua revenue
            $totalPaid = $revenue->ledgers()->sum('amount');
            //tong amount cua cac but toan dieu chinh cua cac ledger cua revenue
            $totalAdjustment = $this->adjustmentRepository->getTotalAmountByLedger($revenue->ledgers()->pluck('id')->toArray());
            $totalPaid += $totalAdjustment;

            $this->revenueRepository->update($revenue->id, [
                'amount_paid' => $totalPaid,
                'updated_at' => now()
            ]);
        }

        if ($ledger->type === Constant::EXPENSE->value) {
            // Tính lại tổng cho tất cả ledgers của expense này
            $expense = $ledger->expense;
            if (!$expense) {
                throw new AppException(ErrorCode::NOT_FOUND);
            }

            $totalPaid = $expense->ledgers()->sum('amount');
            $totalAdjustment = $this->adjustmentRepository->getTotalAmountByLedger($expense->ledgers()->pluck('id')->toArray());

            $totalPaid += $totalAdjustment;

            $this->expenseRepository->update($expense->id, [
                'amount_paid' => $totalPaid,
                'updated_at' => now()
            ]);
        }
    }

    public function getAdjustmentsByLedger(string $ledgerId)
    {
        return $this->adjustmentRepository->getByLedgerId($ledgerId);
    }
}
