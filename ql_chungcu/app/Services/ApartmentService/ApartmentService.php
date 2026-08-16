<?php

namespace App\Services\ApartmentService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Imports\ApartmentImport;
use App\Models\Apartment;
use App\Repositories\ApartmentRepository\IApartmentRepository;
use App\Repositories\BuildingRepository\IBuildingRepository;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ApartmentService implements IApartmentService
{
    private IApartmentRepository $apartmentRepository;
    private IBuildingRepository $buildingRepository;

    public function __construct(IApartmentRepository $apartmentRepository, IBuildingRepository $buildingRepository)
    {
        $this->apartmentRepository = $apartmentRepository;
        $this->buildingRepository = $buildingRepository;
    }

    public function findByBuildingId(string $bdId, string $perPage)
    {
        return $this->apartmentRepository->findByBuildingId($bdId, $perPage);
    }

    public function add(array $data): Apartment
    {
        $apt = $this->apartmentRepository->findByBuildingAndAptNumber($data['building_id'], $data['apt_number']);
        if ($apt) {
            throw new AppException(ErrorCode::APT_NUMBER_EXISTED);
        }

        $data['carpet_area'] = floatval($data['coefficient']) * floatval($data['gross_area']);

        return $this->apartmentRepository->store($data);
    }

    public function update(string $id, array $data): ?Apartment
    {
        $apt = $this->apartmentRepository->getById($id);
        if (!$apt) {
            throw new AppException(ErrorCode::NOT_FOUND);
        }
        $aptNumber = $this->apartmentRepository->findByBuildingAndAptNumber($apt->building_id, $data['apt_number']);
        if ($aptNumber) {
            throw new AppException(ErrorCode::APT_NUMBER_EXISTED);
        }

        $data['carpet_area'] = floatval($data['coefficient']) * floatval($data['gross_area']);
        return $this->apartmentRepository->update($data, $id);
    }

    public function importAptFromExcel($file)
    {
        try {
            $complexId = jwt_claim('complex_id');
            // Đọc file Excel
            $aptImportEx = new ApartmentImport();
            $data = Excel::toCollection($aptImportEx, $file)->first();

            // Validate tất cả các dòng
            $validationResult = $aptImportEx->validateRows($data);

            // Nếu có lỗi, trả về chi tiết lỗi từng dòng
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

            // Check tòa nhà tồn tại k
            $buildingList = $rowsCollection->pluck('building_name')->unique()->toArray();
            $existingBuildings = $this->buildingRepository->findByCondition('building_name', $buildingList, $complexId);
            $missingBuildings = array_diff(
                $buildingList,
                $existingBuildings->keys()->toArray()
            );

            if (count($missingBuildings) > 0) {
                $rowsErrors = [];
                foreach ($data as $index => $row) {
                    $errors = false;
                    $rowNumber = $index + $this->startRow();
                    $stringError = "Dòng\n" . $rowNumber . ":\n";

                    if (in_array($row['toa_nha'], $missingBuildings)) {
                        $stringError = $stringError . "Tòa nhà\n";
                        $errors = true;
                    }

                    if ($errors) {
                        $rowsErrors[] = [
                            $stringError . "không tồn tại.\n"
                        ];
                    }
                }
                return [
                    'success' => false,
                    'message' => $rowsErrors,
                    'errors' => $rowsErrors,
                ];
            }

            $dataApt = [];

            foreach ($validationResult['data'] as $i => $row) {
                $dataApt[] = [
                    'id' => (string)Str::uuid(),
                    'building_id' => $existingBuildings[$row['building_name']],
                    'complex_id' => $complexId,
                    'apt_number' => $row['apt_number'],
                    'floor' => $row['floor'],
                    'gross_area' => $row['gross_area'],
                    'coefficient' => $row['coefficient'],
                    'carpet_area' => floatval($row['coefficient']) * floatval($row['gross_area']),
                    'apt_type' => $row['apt_type'],
                    'description' => $row['description'],
                    'status' => 0,
                    'created_at' => Date::now(),
                    'updated_at' => Date::now()
                ];
            }

            // Nếu tất cả đều hợp lệ, bắt đầu lưu vào database
            DB::beginTransaction();

            try {
                $result = $this->apartmentRepository->storeFromFile($dataApt);
                DB::commit();
                return [
                    'success' => true,
                ];
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
        return 5;
    }

}
