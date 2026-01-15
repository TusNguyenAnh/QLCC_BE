<?php

namespace App\Factory;

use App\Services\FinancialModelService\CentralizedFinancialService;
use App\Services\FinancialModelService\DecentralizedFinancialService;
use App\Services\FinancialModelService\IFinancialModelService;
use InvalidArgumentException;

class FinancialModelFactory
{
    public static function make(string $type): IFinancialModelService
    {
        return match ($type) {
            'centralized'   => app(CentralizedFinancialService::class),
            'decentralized' => app(DecentralizedFinancialService::class),
            default => throw new InvalidArgumentException("Economic model [$type] not supported")
        };
    }
}
