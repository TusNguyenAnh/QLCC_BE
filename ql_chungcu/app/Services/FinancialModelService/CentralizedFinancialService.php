<?php

namespace App\Services\FinancialModelService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Repositories\ComplexRepository\IComplexRepository;
use App\Repositories\FinancialModelRepository\IFinancialModelRepository;

class CentralizedFinancialService implements IFinancialModelService
{

    private IFinancialModelRepository $financialModelRepository;
    private IComplexRepository $complexRepository;

    public function __construct(IFinancialModelRepository $financialModelRepository, IComplexRepository $complexRepository)
    {
        $this->financialModelRepository = $financialModelRepository;
        $this->complexRepository = $complexRepository;
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
    }
}
