<?php

namespace App\Factory;

use App\Services\LedgerService\CentralizedLedgerSummaryService;
use App\Services\LedgerService\DecentralizedLedgerSummaryService;
use App\Services\LedgerService\ILedgerSummaryService;
use InvalidArgumentException;

class LedgerSummaryFactory
{
    public static function make(string $type): ILedgerSummaryService
    {
        return match ($type) {
            'centralized'   => app(CentralizedLedgerSummaryService::class),
            'decentralized' => app(DecentralizedLedgerSummaryService::class),
            default => throw new InvalidArgumentException("Economic model [$type] not supported")
        };
    }
}
