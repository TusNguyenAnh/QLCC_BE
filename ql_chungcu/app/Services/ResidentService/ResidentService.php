<?php

namespace App\Services\ResidentService;

use App\Enums\Constant;
use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Imports\AptResidentImport;
use App\Models\Resident;
use App\Repositories\ApartmentRepository\IApartmentRepository;
use App\Repositories\AptResidentRepository\IAptResidentRepository;
use App\Repositories\BuildingRepository\IBuildingRepository;
use App\Repositories\OrgUserRepository\IOrgUserRepository;
use App\Repositories\ResidentRepository\IResidentRepository;
use App\Imports\ResidentImport;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ResidentService implements IResidentService
{
    private IResidentRepository $residentRepository;
    private IBuildingRepository $buildingRepository;
    private IApartmentRepository $apartmentRepository;
    private IAptResidentRepository $aptResidentRepository;
    private IOrgUserRepository $orgUserRepository;


    public function __construct(IResidentRepository $residentRepository, IAptResidentRepository $aptResidentRepository,
                                IBuildingRepository $buildingRepository, IApartmentRepository $apartmentRepository,
                                IOrgUserRepository  $orgUserRepository
    )
    {
        $this->residentRepository = $residentRepository;
        $this->aptResidentRepository = $aptResidentRepository;
        $this->buildingRepository = $buildingRepository;
        $this->apartmentRepository = $apartmentRepository;
        $this->orgUserRepository = $orgUserRepository;
    }

    public function show(array $filters)
    {
        $complexId = jwt_claim('complex_id');
        return $this->residentRepository->show($filters, $complexId);
    }

    public function add(array $data): ?Resident
    {
        // Check hàng loạt các CCCD,email,sdt đã tồn tại trong DB
        $existingCCCD = $this->residentRepository->findByCondition('cccd', [$data['cccd']], $data['complex_id']);
        $existingEmail = $this->residentRepository->findByCondition('email', [$data['email']], $data['complex_id']);
        $existingPhone = $this->residentRepository->findByCondition('phone_number', [$data['phone_number']], $data['complex_id']);

        if ($existingEmail || $existingPhone || $existingCCCD) {
            throw new AppException(ErrorCode::RESIDENT_EXISTED);
        }

        $resident = $this->residentRepository->store($data);

        return $resident;
    }

    public function findByOrgId($orgId)
    {
        return $this->residentRepository->findByOrgId($orgId);
    }

    public function findResidentByBuildingId($bdId,$orgId)
    {
        return $this->aptResidentRepository->findResidentByBuildingId($bdId,$orgId);
    }

    public function addResInOrg(array $ids, string $org_id)
    {
        DB::beginTransaction();
        try {
            $orgUser = [];
            foreach ($ids as $id) {
                $orgUser[] = [
                    'id' => (string)Str::uuid(),
                    'org_id' => $org_id,
                    'user_id' => $id,
//                    'position' => Constant::MEMBER->value,
                    'created_at' => Date::now(),
                    'updated_at' => Date::now()
                ];
            }

            $response = $this->orgUserRepository->store($orgUser);
            DB::commit();

            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function removeResInOrg(array $ids, string $org_id)
    {
        DB::beginTransaction();
        try {
            $response = $this->orgUserRepository->delete($ids, $org_id);
            DB::commit();

            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function updatePosition($userId, $orgId, $roleId)
    {
        return $this->orgUserRepository->update($userId, $orgId, $roleId);
    }

    /**
     * Import residents from Excel file
     * Validate all rows before saving
     * Return errors with specific row numbers if validation fails
     */
    public function importResFromExcel($file)
    {
        try {
            $complexId = jwt_claim('complex_id');
            // Đọc file Excel
            $resImportEx = new ResidentImport();
            $data = Excel::toCollection($resImportEx, $file)->first();

            // Validate tất cả các dòng
            $validationResult = $resImportEx->validateRows($data);

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

            // Lấy tất cả CCCD trong file
            $cccdList = $data->pluck('cccd')->filter()->toArray();
            $emailList = $data->pluck('email')->filter()->toArray();
            $phoneList = $data->pluck('so_dien_thoai')->filter()->toArray();

            // Check hàng loạt các CCCD,email,sdt đã tồn tại trong DB
            $existingCCCDs = $this->residentRepository->findByCondition('cccd', $cccdList, $complexId);
            $existingEmails = $this->residentRepository->findByCondition('email', $emailList, $complexId);
            $existingPhones = $this->residentRepository->findByCondition('phone_number', $phoneList, $complexId);

            $rowsErrors = [];
            if ($existingEmails->isNotEmpty() || $existingPhones->isNotEmpty() || $existingCCCDs->isNotEmpty()) {
                foreach ($data as $index => $row) {
                    $errors = false;
                    $rowNumber = $index + $this->startRow();
                    $stringError = "Dòng\n" . $rowNumber . ":\n";
                    if ($existingCCCDs->has($row['cccd'])) {
                        $stringError = $stringError . "CCCD,\n";
                        $errors = true;
                    }

                    if ($existingPhones->has($row['so_dien_thoai'])) {
                        $stringError = $stringError . "Số điện thoại,\n";
                        $errors = true;
                    }

                    if ($existingEmails->has($row['email'])) {
                        $stringError = $stringError . "Email\n";
                        $errors = true;
                    }

                    if ($errors) {
                        $rowsErrors[] = [
                            $stringError . "đã tồn tại.\n"
                        ];
                    }
                }
                return [
                    'success' => false,
                    'message' => $rowsErrors,
                    'errors' => $rowsErrors,
                ];
            }

            // Nếu tất cả đều hợp lệ, bắt đầu lưu vào database
            DB::beginTransaction();

            try {
                $result = $this->residentRepository->storeFromFile($validationResult['data']);

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

    public function importResAptFromExcel($file)
    {
        try {
            $complexId = jwt_claim('complex_id');
            // Đọc file Excel
            $resAptImportEx = new AptResidentImport();
            $data = Excel::toCollection($resAptImportEx, $file)->first();

            // Validate tất cả các dòng
            $validationResult = $resAptImportEx->validateRows($data);

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

            // Lấy tất cả CCCD trong file
            //check cccd ton tai k
            $cccdList = $rowsCollection->pluck('cccd')->unique()->toArray();
            $existingCCCDs = $this->residentRepository->findByCondition('cccd', $cccdList, $complexId); // tra ve [['cccd'->id]]
            $missingCccds = array_diff(
                $cccdList,
                $existingCCCDs->keys()->toArray()
            );

            // Check tòa nhà tồn tại k
            $buildingList = $rowsCollection->pluck('building_name')->unique()->toArray();
            $existingBuildings = $this->buildingRepository->findByCondition('building_name', $buildingList, $complexId);
            $missingBuildings = array_diff(
                $buildingList,
                $existingBuildings->keys()->toArray()
            );

            if (count($missingCccds) > 0 || count($missingBuildings) > 0) {
                $rowsErrors = [];
                foreach ($data as $index => $row) {
                    $errors = false;
                    $rowNumber = $index + $this->startRow();
                    $stringError = "Dòng\n" . $rowNumber . ":\n";
                    if (in_array($row['cccd'], $missingCccds)) {
                        $stringError = $stringError . "CCCD,\n";
                        $errors = true;
                    }

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

            //check can ho co thuoc toa nha
            $apartments = $this->apartmentRepository->getAptIdByBuildingAndApartmentNumber($validationResult['data'], $complexId);
            $errorsApt = [];
            $dataAptRes = [];

            foreach ($validationResult['data'] as $i => $row) {
                $key = $row['building_name'] . '_' . $row['apt_number'];
                $rowNumber = $i + $this->startRow();
                $stringError = "Dòng\n" . $rowNumber . ":\n";

                if (!isset($apartments[$key])) {
                    $errorsApt[] = $stringError . "Căn hộ {$row['apt_number']} không thuộc {$row['building_name']},\n";
                    continue;
                }

                $dataAptRes[] = [
                    'id' => (string)Str::uuid(),
                    'resident_id' => $existingCCCDs[$row['cccd']],
                    'apt_id' => $apartments[$key]->id,
                    'status' => 0,
                    'created_at' => Date::now(),
                    'updated_at' => Date::now()
                ];
            }

            if ($errorsApt) {
                return [
                    'success' => false,
                    'message' => $errorsApt
                ];
            }

            // Nếu tất cả đều hợp lệ, bắt đầu lưu vào database
            DB::beginTransaction();

            try {
                $result = $this->aptResidentRepository->storeFromFile($dataAptRes);

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
