<?php

namespace App\Services\FinancialModelService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Repositories\BuildingRepository\IBuildingRepository;
use App\Repositories\ComplexRepository\IComplexRepository;
use App\Repositories\FinancialModelRepository\IFinancialModelRepository;
use App\Repositories\LedgerRepository\ILedgerRepository;
use App\Repositories\LedgerRepository\ILedgerSummaryRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DecentralizedFinancialService implements IFinancialModelService
{
    private IFinancialModelRepository $financialModelRepository;
    private IComplexRepository $complexRepository;
    private IBuildingRepository $buildingRepository;
    private ILedgerSummaryRepository $ledgerSummaryRepository;

    public function __construct(IFinancialModelRepository $financialModelRepository,
                                IComplexRepository        $complexRepository,
                                IBuildingRepository       $buildingRepository,
                                ILedgerSummaryRepository  $ledgerSummaryRepository)
    {
        $this->financialModelRepository = $financialModelRepository;
        $this->complexRepository = $complexRepository;
        $this->buildingRepository = $buildingRepository;
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
        }

//        $data['ratio'] = [
//            ['id' => 10, 'status' => 'done'],
//            ['id' => 11, 'status' => 'pending'],
//        ];

        if (isset($data['ratio'])) {
            $buildingIds = collect($data['ratio'])->pluck('id')
                ->unique()
                ->values()
                ->toArray();

            $buildingExist = $this->buildingRepository->findByCondition('id', $buildingIds, $data['complex_id']);
            if (count($buildingIds) != $buildingExist->count()) {
                throw new AppException(ErrorCode::NOT_FOUND);
            }

            $this->buildingRepository->updateRatio($data['ratio']);

            //tao ledger summary cho thang truoc thang ma khoi tao model
            $preTime = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1)->subMonth();
            $initLedgerSummary = [];
            foreach ($buildingIds as $buildingId) {
                $initLedgerSummary[] = [
                    'id' => (string)Str::uuid(),
                    'complex_id' => $data['complex_id'],
                    'building_id' => $buildingId,
                    'year' => $preTime->year,
                    'month' => $preTime->month,
                    'total_in' => 0,
                    'total_out' => 0,
                    'opening_balance' => 0,
                    'closing_balance' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            if (count($buildingIds) > 0) {
                $this->ledgerSummaryRepository->store($initLedgerSummary);
            }
        }
    }
}
