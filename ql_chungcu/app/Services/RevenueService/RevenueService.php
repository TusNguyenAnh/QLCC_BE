<?php

namespace App\Services\RevenueService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Models\Apartment;
use App\Models\Revenue;
use App\Repositories\ApartmentRepository\IApartmentRepository;
use App\Repositories\RevenueRepository\IRevenueRepository;
use App\Repositories\ServiceUnitPriceRepository\IServiceUnitPriceRepository;
use App\Services\LedgerService\ILedgerService;
use App\Services\AdjustmentTransactionService\IAdjustmentTransactionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\TextUI\CliArguments\Exception;

class RevenueService implements IRevenueService
{
    private IRevenueRepository $revenueRepository;
    private IServiceUnitPriceRepository $serviceUnitPriceRepository;

    public function __construct(
        IRevenueRepository            $revenueRepository,
        ILedgerService                $ledgerService,
        IAdjustmentTransactionService $adjustmentService,
        IApartmentRepository          $apartmentRepository,
        IServiceUnitPriceRepository   $serviceUnitPriceRepository
    )
    {
        $this->revenueRepository = $revenueRepository;
        $this->serviceUnitPriceRepository = $serviceUnitPriceRepository;
    }

    public function getRevenueByFilters(array $filters, int $perPage = 50)
    {
        return $this->revenueRepository->getByFilters($filters, $perPage);
    }

    public function createRevenue(array $data)
    {
        $data["id"] = Str::uuid();
        return $this->revenueRepository->store($data);
    }

    // neu muon sua, xoa thi chua phat sinh ledger
    // neu da phat sinh bat buoc tao adj dieu chinh ledger de dieu chinh amount paid cua revenue cu ve 0 -> tao revenue moi
    public function updateRevenue(string $id, array $data): ?Revenue
    {
        $revenue = $this->revenueRepository->findById($id);

        if ($revenue === null || $revenue->ledgers()->count() != 0) {
            throw new AppException(ErrorCode::REVENUE_NOT_UPDATE);
        }
        return $this->revenueRepository->update($id, $data);
    }

    public function deleteRevenue(string $id): bool
    {
        $revenue = $this->revenueRepository->findById($id);
        if ($revenue === null || $revenue->ledgers()->count() != 0) {
            throw new AppException(ErrorCode::REVENUE_NOT_UPDATE);
        }
        return $this->revenueRepository->delete($id);
    }

    public function generateMonthlyRevenues(string $buildingId, int $year, int $month)
    {
        try {
            // lay gia dich vu cua thang/nam do neu chua co yeu cau BQT tao
            $priceRecord = $this->serviceUnitPriceRepository->findByYearAndMonth($year, $month);

            if ($priceRecord == null) {
                throw new AppException(ErrorCode::PRICE_NON_EXISTED);
            }

            $amount = $priceRecord->price_per_m2;

            // Lấy tất cả căn hộ trong tòa nhà ma chua co revenue
            $apartmentIds = $this->revenueRepository->getApartmentsWithoutRevenueByMonth($buildingId, $year, $month);

            $data = $apartmentIds->map(function ($apartmentId) use ($year, $month, $amount) {
                return [
                    'id' => (string)Str::uuid(),   // nếu bạn dùng UUID
                    'apartment_id' => $apartmentId->id,
                    'year' => $year,
                    'month' => $month,
                    'original_amount' => (float)$amount * (float)$apartmentId->apt_area,   // tiền mặc định
                    'amount_paid' => 0.0,
                    'status' => 'UNPAID',
                    'description' => "Thu phí dịch vụ tháng ".$month."/".$year,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            $this->revenueRepository->store($data);

            return [
                'total_revenue' => $apartmentIds->count(),
                'building_id' => $buildingId,
                'year' => $year,
                'month' => $month,
                'amount' => $amount,
            ];
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
