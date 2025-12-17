<?php

namespace App\Services\LedgerService;

use App\Enums\Constant;
use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Models\Expense;
use App\Models\Ledger;
use App\Models\Revenue;
use App\Repositories\ExpenseRepository\IExpenseRepository;
use App\Repositories\LedgerRepository\ILedgerRepository;
use App\Repositories\LedgerRepository\ILedgerSummaryRepository;
use App\Repositories\RevenueRepository\IRevenueRepository;
use App\Services\VoucherService\IVoucherService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * LỚP 2 - LEDGER SERVICE
 * Quản lý giao dịch thực tế (IMMUTABLE)
 * ⚠️ LEDGER KHÔNG BAO GIỜ ĐƯỢC SỬA/XÓA
 * Nếu cần điều chỉnh → tạo adjustment_trans (Lớp 3)
 */
class LedgerService implements ILedgerService
{
    private ILedgerRepository $ledgerRepository;
    private ILedgerSummaryRepository $ledgerSummaryRepository;
    private IRevenueRepository $revenueRepository;
    private IExpenseRepository $expenseRepository;

    public function __construct(
        ILedgerRepository        $ledgerRepository,
        ILedgerSummaryRepository $ledgerSummaryRepository,
        IRevenueRepository       $revenueRepository,
        IExpenseRepository       $expenseRepository
    )
    {
        $this->ledgerRepository = $ledgerRepository;
        $this->revenueRepository = $revenueRepository;
        $this->expenseRepository = $expenseRepository;
        $this->ledgerSummaryRepository = $ledgerSummaryRepository;
    }

    public function getLedgerByFilters(array $filters, int $perPage, string $complexId)
    {
        $listLedger = $this->ledgerRepository->getByFilters($filters, $perPage, $complexId);

        if ($listLedger) {
            $firstLedger = $listLedger->first();
            $transForm = $firstLedger->transaction_date;
            //thuc hien tinh balance cho tung ledger
            $ledgers = $this->calculateBalanceLedger($transForm, $complexId, $listLedger);
            return $ledgers;
        }
        return $listLedger;
    }

    public function storeRevenue(string $revenueId, array $data)
    {
        DB::beginTransaction();
        try {
            $revenue = $this->revenueRepository->findById($revenueId);
            if (!$revenue) {
                throw new AppException(ErrorCode::REVENUE_NOT_UPDATE);
            }

            // 1. Tự động sinh số phiếu thu (PT-2025-0001)
            $voucherNumber = $this->generateVoucherNumber('PT', $data['complex_id']);
            // 2. Ghi ledger
            $data['voucher_number'] = $voucherNumber;
            $data["type"] = "revenue";
            $data['related_id'] = $revenue->id;
            $data['created_by'] = jwt_claim('sub');

            $ledger = $this->ledgerRepository->store($data);

            // 3. Update cache amount_paid (atomic - tránh race condition)
            DB::table('revenues')
                ->where('id', $revenue->id)
                ->update([
                    'amount_paid' => DB::raw('amount_paid + ' . $ledger->amount),
                    'updated_at' => now()
                ]);

            // 4. Refresh và update status
            $revenue->refresh();
            $this->updateRevenueStatusFromCache($revenue);

            DB::commit();
            return $ledger;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new AppException(ErrorCode::NOT_CREATED);
        }
    }

    public function storeExpense(string $expenseId, array $data)
    {
        DB::beginTransaction();
        try {
            $expense = $this->expenseRepository->findById($expenseId);
            if (!$expense) {
                throw new AppException(ErrorCode::EXPENSE_NOT_UPDATE);
            }

            // 1. Tự động sinh số phiếu chi (PC-202-0001)
            $voucherNumber = $this->generateVoucherNumber('PC', $data['complex_id']);

            // 2. Ghi ledger (IMMUTABLE)
            $data['voucher_number'] = $voucherNumber;
            $data["type"] = "expense";
            $data['related_id'] = $expense->id;
            $data['created_by'] = jwt_claim('sub');

            $ledger = $this->ledgerRepository->store($data);

            // 3. Update cache amount_paid (tránh race condition)
            DB::table('expenses')
                ->where('id', $expense->id)
                ->update([
                    'amount_paid' => DB::raw('amount_paid + ' . $ledger->amount),
                    'updated_at' => now()
                ]);

            // 4. Refresh và update status
            $expense->refresh();
            $this->updateExpenseStatusFromCache($expense);

            DB::commit();
            return $ledger;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new AppException(ErrorCode::NOT_CREATED);
        }
    }

    private function updateRevenueStatusFromCache(Revenue $revenue): void
    {
        $totalPaid = (float)$revenue->amount_paid;
        $expected = (float)$revenue->original_amount;

        if ($totalPaid <= 0) {
            $status = 'unpaid';
        } elseif ($totalPaid < $expected) {
            $status = 'partial';
        } elseif ($totalPaid == $expected) {
            $status = 'paid';
        } else {
            $status = 'overpaid';
        }
        $revenue->update(['status' => $status]);
    }

    private function updateExpenseStatusFromCache(Expense $expense): void
    {
        $totalPaid = (float)$expense->amount_paid;
        $expected = (float)$expense->original_amount;

        if ($totalPaid <= 0) {
            $status = 'unpaid';
        } elseif ($totalPaid < $expected) {
            $status = 'partial';
        } else {
            $status = 'paid';
        }

        $expense->update(['status' => $status]);
    }

    private function generateVoucherNumber(string $type, $complexId)
    {
        if (!in_array($type, ['PT', 'PC'])) {
            throw new AppException(ErrorCode::VOUCHER_NOT_VALID);
        }

        return $this->ledgerRepository->generateVoucherNumber($type, $complexId);
    }

    // tinh balance cho ledger
    private function calculateBalanceLedger(string $transFrom, string $complexId, $ledgers)
    {
        // tach khoang thoi gian
        $from = Carbon::parse($transFrom);
        $month = $from->month;
        $year = $from->year;
        // lay so du dau ky cua khoang thoi gian dang filter
        $ledgerSummary = $this->ledgerSummaryRepository->findByMonth($month, $year, $complexId);
        if (!$ledgerSummary) {
            throw new AppException(ErrorCode::LEDGER_SUMMARY_NOT_EXISTED);
        }
        $openingBalance = $ledgerSummary->opening_balance;

        // lay ra tong thu chi tu dau ky den thoi diem loc
        $totalRevenue = $this->ledgerRepository->getTotalLedgerAmountByTime(Constant::REVENUE->value, $from->copy()->startOfMonth(), $from->copy()->subDay(), $complexId);
        $totalExpense = $this->ledgerRepository->getTotalLedgerAmountByTime(Constant::EXPENSE->value, $from->copy()->startOfMonth(), $from->copy()->subDay(), $complexId);

        //so du cuoi cung den truoc thoi diem loc
        $realOpeningBalance = (float)$openingBalance + (float)$totalRevenue - (float)$totalExpense;

        // tinh balance cho tung ledger
        $currentBalance = $realOpeningBalance;
        foreach ($ledgers as $item) {
            if ($item->type === Constant::REVENUE->value) {
                $currentBalance += (float)$item->final_amount;
            } else {
                $currentBalance -= (float)$item->final_amount;
            }

            $item->balance = $currentBalance; // gắn vào kết quả trả về
        }

        return $ledgers;
    }

}
