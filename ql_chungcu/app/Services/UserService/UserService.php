<?php

namespace App\Services\UserService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Helpers\StringHelper;
use App\Mail\GenericMail;
use App\Models\User;
use App\Repositories\ComplexRepository\IComplexRepository;
use App\Repositories\OrgUserRepository\IOrgUserRepository;
use App\Repositories\ResidentRepository\IResidentRepository;
use App\Repositories\UserRepository\IUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserService implements IUserService
{
    private IUserRepository $userRepository;
    private IResidentRepository $residentRepository;
    private IOrgUserRepository $orgUserRepository;
    private IComplexRepository $complexRepository;


    public function __construct(IUserRepository    $userRepository, IResidentRepository $residentRepository,
                                IOrgUserRepository $orgUserRepository, IComplexRepository $complexRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->residentRepository = $residentRepository;
        $this->orgUserRepository = $orgUserRepository;
        $this->complexRepository = $complexRepository;
    }

    public function show($perPage)
    {
        return $this->userRepository->show($perPage);
    }

    public function add(array $data)
    {
        $complexId = jwt_claim('complex_id');
        $rowsCollection = collect($data['listRes']);

        //lay thong tin complex
        $complex = $this->complexRepository->getById($complexId);

        // kiem tra cu dan ton tai trong chung cu ?
        $cccdList = $rowsCollection->pluck('cccd')->unique()->toArray();
        $existingCCCD = $this->residentRepository->findByCondition('cccd', $cccdList, $complexId);

        if ($existingCCCD->count() != count($cccdList)) {
            throw new AppException(ErrorCode::NOT_FOUND);
        }

        // kiem tra tai khoan da ton tai chua (sdt la ten tai khoan)
        $phoneList = $rowsCollection->pluck('phone_number')->unique()->toArray();
        $existingUsername = $this->userRepository->findByCondition('username', $phoneList, $complexId);

        if ($existingUsername->count() != 0) {
            throw new AppException(ErrorCode::USER_EXISTED);
        }

        $dataImport = [];
        $dataSendEmail = [];
        $dataOrgUser = [];


        foreach ($data['listRes'] as &$item) {
//            $passwordRaw = StringHelper::randomStrongCode();
            $passwordRaw = "1";

            $userId = (string)Str::uuid();
            $dataImport[] = [
                'id' => $userId,
                'username' => $item['phone_number'],
                'res_id' => $item['id'],
                'complex_id' => $complexId,
                'password' => Hash::make($passwordRaw),
                'created_at' => Date::now(),
                'updated_at' => Date::now()
            ];

            $dataOrgUser[] = [
                'id' => (string)Str::uuid(),
                'org_id' => "",
                'user_id' => $userId,
                'created_at' => Date::now(),
                'updated_at' => Date::now()
            ];

            $dataSendEmail[] = [
                'fullname' => $item['fullname'],
                'username' => $item['phone_number'],
                'passwordRaw' => $passwordRaw,
                'email' => $item['email'],
                'complexName' => $complex->complex_name,
            ];
        }
        unset($item);

        // Nếu tất cả đều hợp lệ, bắt đầu lưu vào database
        DB::beginTransaction();
        try {
            $user = $this->userRepository->store($dataImport);
            $orgUser = $this->orgUserRepository->store($dataOrgUser);

            DB::commit();

            //gui thong tin
            foreach ($dataSendEmail as $user) {
                Mail::to($user['email'])->queue(
                    new GenericMail(
                        'Thông tin đăng ký tài khoản',
                        'emails.register_account',
                        [
                            'name' => $user['fullname'],
                            'username' => $user['username'],
                            'password' => $user['passwordRaw'],
                            'complexName' => "Đại diện ".$user['complexName'],
                        ]
                    )
                );
            }

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function findByOrgId($orgId, $type)
    {
        if ($type != 0 && $type != 1) {
            throw new AppException(ErrorCode::NOT_FOUND);
        }

        $type = $type == 0 ? 'res' : 'st';

        $joinMap = [
            'res' => [
                'table' => 'residents',
                'left' => 'users.res_id',
                'right' => 'residents.id',
                'org' => 'residents.org_id',
            ],
            'st' => [
                'table' => 'staffs',
                'left' => 'users.staff_id',
                'right' => 'staffs.id',
                'org' => 'staffs.org_id',
            ]
        ];

        $listUser = $this->userRepository->findByOrgId($orgId, $joinMap[$type]);
        return $listUser;
    }

    public function findByBuildingId(array $filters)
    {
        $complexId = jwt_claim('complex_id');
        $listUser = $this->userRepository->findByBuildingId($filters, $complexId);
        return $listUser;
    }

}
