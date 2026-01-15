<?php

namespace App\Factory;

use App\Services\ExpenseService\CentralizedExpenseService;
use App\Services\ExpenseService\DecentralizedExpenseService;
use App\Services\ExpenseService\IExpenseService;
use InvalidArgumentException;

class ExpenseFactory
{
    public static function make(string $type): IExpenseService
    {
        return match ($type) {
            'centralized' => app(CentralizedExpenseService::class),
            'decentralized' => app(DecentralizedExpenseService::class),
            default => throw new InvalidArgumentException("Economic model [$type] not supported")
        };
    }
}
