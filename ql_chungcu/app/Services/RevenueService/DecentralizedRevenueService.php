<?php

namespace App\Services\RevenueService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Models\Revenue;
use App\Repositories\ApartmentRepository\IApartmentRepository;
use App\Repositories\BuildingRepository\IBuildingRepository;
use App\Repositories\RevenueRepository\IRevenueRepository;
use App\Repositories\ServiceUnitPriceRepository\IServiceUnitPriceRepository;
use App\Services\AdjustmentTransactionService\IAdjustmentTransactionService;
use App\Services\LedgerService\ILedgerService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PHPUnit\TextUI\CliArguments\Exception;

class DecentralizedRevenueService implements IRevenueService
{
    private IRevenueRepository $revenueRepository;
    private IServiceUnitPriceRepository $serviceUnitPriceRepository;
    private IBuildingRepository $buildingRepository;


    public function __construct(
        IRevenueRepository            $revenueRepository,
        ILedgerService                $ledgerService,
        IAdjustmentTransactionService $adjustmentService,
        IApartmentRepository          $apartmentRepository,
        IServiceUnitPriceRepository   $serviceUnitPriceRepository,
        IBuildingRepository           $buildingRepository,

    )
    {
        $this->revenueRepository = $revenueRepository;
        $this->serviceUnitPriceRepository = $serviceUnitPriceRepository;
        $this->buildingRepository = $buildingRepository;
    }

    public function getRevenueByFilters(array $filters, int $perPage, string $complexId)
    {
        return $this->revenueRepository->getByFilters($filters, $perPage, $complexId);
    }

    public function createRevenue(array $data)
    {
        $finanRatio = $this->buildingRepository->getBuildingRatio($data['building_id'], jwt_claim('complex_id'));
        if (count($finanRatio) == 0) {
            throw new AppException(ErrorCode::NOT_FOUND);
        }

        $dataRevenue = [];
        $revenueType = $data['revenue_type'];
        foreach ($finanRatio as $buildingId => $ratio) {
            //0: toan khu -> theo ti le ; 1 : noi bo toa
            $amount = $revenueType == 0 ? ((float)$data['original_amount'] * ((float)$ratio / 100)) : (float)$data['original_amount'];
            $aptId = $revenueType != 0 ? $data['apartment_id'] : null;

            $dataRevenue[] = [
                'id' => (string)Str::uuid(),
                'task_id' => $data['task_id'],
                'title' => $data['title'],
                'original_amount' => $amount,
                'description' => $data['description'],
                'building_id' => $buildingId,
                'apartment_id' => $aptId,
                'created_by' => jwt_claim('sub'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return $this->revenueRepository->store($dataRevenue);
    }

    public function updateRevenue(string $id, array $data): ?Revenue
    {
        return null;
    }

    public function deleteRevenue(string $id): bool
    {
        return true;
    }

    public function generateMonthlyRevenues(string $buildingId, int $year, int $month)
    {
        return null;
    }

    public function approveRevenue(array $ids, string $approvedBy)
    {
        try {
            return $this->revenueRepository->approveRevenue($ids, $approvedBy);
        } catch (Exception $exception) {
            throw new AppException(ErrorCode::EXPENSE_NOT_UPDATE);
        }
    }
}
