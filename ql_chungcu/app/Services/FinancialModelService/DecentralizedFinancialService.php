<?php

namespace App\Services\FinancialModelService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Repositories\BuildingRepository\IBuildingRepository;
use App\Repositories\ComplexRepository\IComplexRepository;
use App\Repositories\FinancialModelRepository\IFinancialModelRepository;

class DecentralizedFinancialService implements IFinancialModelService
{
    private IFinancialModelRepository $financialModelRepository;
    private IComplexRepository $complexRepository;
    private IBuildingRepository $buildingRepository;

    public function __construct(IFinancialModelRepository $financialModelRepository, IComplexRepository $complexRepository, IBuildingRepository $buildingRepository)
    {
        $this->financialModelRepository = $financialModelRepository;
        $this->complexRepository = $complexRepository;
        $this->buildingRepository = $buildingRepository;
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
        }
    }
}
