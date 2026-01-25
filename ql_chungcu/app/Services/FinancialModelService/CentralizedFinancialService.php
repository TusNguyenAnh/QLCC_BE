<?php

namespace App\Services\FinancialModelService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Repositories\ComplexRepository\IComplexRepository;
use App\Repositories\FinancialModelRepository\IFinancialModelRepository;
use App\Repositories\LedgerRepository\ILedgerSummaryRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CentralizedFinancialService implements IFinancialModelService
{

    private IFinancialModelRepository $financialModelRepository;
    private IComplexRepository $complexRepository;
    private ILedgerSummaryRepository $ledgerSummaryRepository;

    public function __construct(IFinancialModelRepository $financialModelRepository,
                                IComplexRepository        $complexRepository,
                                ILedgerSummaryRepository  $ledgerSummaryRepository)
    {
        $this->financialModelRepository = $financialModelRepository;
        $this->complexRepository = $complexRepository;
        $this->ledgerSummaryRepository = $ledgerSummaryRepository;
    }

    public function setupFinancialModel(array $data)
    {
        if (isset($data['type'])) {
            $finanType = $this->financialModelRepository->findByModelName($data['type']);
            if (!$finanType) {
                throw new AppException(ErrorCode::NOT_FOUND);
            }

            $dataUpdateComplex = [
                'financial_model' => $finanType->type
            ];
            $this->complexRepository->update($dataUpdateComplex, $data['complex_id']);

            $preTime = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1)->subMonth();
            $initLedgerSummary = [
                'id' => (string)Str::uuid(),
                'complex_id' => $data['complex_id'],
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
        }
    }
}
