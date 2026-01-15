<?php

namespace App\Factory;

use App\Services\RevenueService\CentralizedRevenueService;
use App\Services\RevenueService\DecentralizedRevenueService;
use App\Services\RevenueService\IRevenueService;
use InvalidArgumentException;

class RevenueFactory
{
    public static function make(string $type): IRevenueService
    {
        return match ($type) {
            'centralized' => app(CentralizedRevenueService::class),
            'decentralized' => app(DecentralizedRevenueService::class),
            default => throw new InvalidArgumentException("Economic model [$type] not supported")
        };
    }
}
