<?php

namespace App\Services\FinancialModelService;

use PhpOffice\PhpSpreadsheet\Calculation\Financial;

interface IFinancialModelService
{
    // validate data
//    public function validate(array $data);
    public function setupFinancialModel(array $data);
}
