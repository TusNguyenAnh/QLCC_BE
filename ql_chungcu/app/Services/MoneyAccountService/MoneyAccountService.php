<?php

namespace App\Services\MoneyAccountService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Imports\MoneyAccountImport;
use App\Repositories\BuildingRepository\IBuildingRepository;
use App\Repositories\MoneyAccountRepository\IMoneyAccountRepository;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class MoneyAccountService implements IMoneyAccountService
{
    private IMoneyAccountRepository $moneyAccountRepository;
    private IBuildingRepository $buildingRepository;

    public function __construct(
        IMoneyAccountRepository $moneyAccountRepository,
        IBuildingRepository     $buildingRepository
    )
    {
        $this->moneyAccountRepository = $moneyAccountRepository;
        $this->buildingRepository = $buildingRepository;
    }

    /**
     * Lấy danh sách tài khoản theo tòa nhà
     */
    public function findByBuildingId(string $bdId, string $perPage)
    {
        return $this->moneyAccountRepository->findByBuildingId($bdId, $perPage);
    }

    /**
     * Thêm tài khoản mới
     * Kiểm tra duplicate (building_id + account_number) trước khi tạo
     */
    public function add(array $data)
    {
        // Kiểm tra xem tài khoản đã tồn tại chưa
        $moneyAcc = $this->moneyAccountRepository->findByBuildingAndAccNumber(
            $data['building_id'],
            $data['account_number']
        );

        if ($moneyAcc) {
            throw new AppException(ErrorCode::NOT_CREATED);
        }

        $data['type'] = 'deposit';  // Mặc định loại là tiết kiệm
        return $this->moneyAccountRepository->store($data);
    }

    /**
     * Import tài khoản từ file Excel
     */
    public function importMoneyAccountFromExcel($file)
    {
        try {
            $complexId = jwt_claim('complex_id');

            // Đọc file Excel sử dụng MoneyAccountImport
            $moneyAccImportEx = new MoneyAccountImport();
            $data = Excel::toCollection($moneyAccImportEx, $file)->first();

            // Validate tất cả các dòng
            $validationResult = $moneyAccImportEx->validateRows($data);

            // Nếu có lỗi validation, trả về chi tiết lỗi
            if (!$validationResult['valid']) {
                return [
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ. Vui lòng kiểm tra lại các dòng bị lỗi.',
                    'errors' => $validationResult['errors'],
                    'total_rows' => count($data),
                    'error_rows' => count($validationResult['errors']),
                ];
            }

            $rowsCollection = collect($validationResult['data']);

            // Kiểm tra tòa nhà có tồn tại không
            $buildingList = $rowsCollection->pluck('building_name')->unique()->toArray();
            $existingBuildings = $this->buildingRepository
                ->findByCondition('building_name', $buildingList, $complexId);

            $missingBuildings = array_diff(
                $buildingList,
                $existingBuildings->keys()->toArray()
            );

            if (count($missingBuildings) > 0) {
                $rowsErrors = [];
                foreach ($data as $index => $row) {
                    $errors = false;
                    $rowNumber = $index + $this->startRow();
                    $stringError = "Dòng " . $rowNumber . ": ";

                    if (in_array($row['toa_nha'], $missingBuildings)) {
                        $stringError .= "Tòa nhà không tồn tại";
                        $errors = true;
                    }

                    if ($errors) {
                        $rowsErrors[] = $stringError;
                    }
                }
                return [
                    'success' => false,
                    'message' => $rowsErrors,
                    'errors' => $rowsErrors,
                ];
            }

            // Chuẩn bị dữ liệu để lưu
            $dataMoneyAcc = [];
            foreach ($validationResult['data'] as $i => $row) {
                $dataMoneyAcc[] = [
                    'id' => (string)Str::uuid(),
                    'building_id' => $existingBuildings[$row['building_name']],
                    'bank_name' => $row['bank_name'],
                    'account_number' => $row['account_number'],
                    'term' => $row['term'],
                    'deposit_date' => $row['deposit_date'],
                    'maturity_date' => $row['maturity_date'],
                    'interest_rate' => $row['interest_rate'],
                    'money' => $row['money'],
                    'type' => 'deposit',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now()
                ];
            }

            // Lưu dữ liệu với transaction
            DB::beginTransaction();
            try {
                $result = $this->moneyAccountRepository->storeFromFile($dataMoneyAcc);
                DB::commit();
                return ['success' => true];
            } catch (\Exception $e) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Lỗi khi lưu dữ liệu vào database: ' . $e->getMessage(),
                    'errors' => [],
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi đọc file Excel: ' . $e->getMessage(),
                'errors' => [],
            ];
        }
    }

    private function startRow(): int
    {
        return 5;  // Dữ liệu bắt đầu từ dòng 5
    }

}
