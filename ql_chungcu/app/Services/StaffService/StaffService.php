<?php

namespace App\Services\StaffService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Helpers\StringHelper;
use App\Mail\GenericMail;
use App\Repositories\OrgUserRepository\IOrgUserRepository;
use App\Repositories\StaffRepository\IStaffRepository;
use App\Repositories\UserRepository\IUserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StaffService implements IStaffService
{
    private IStaffRepository $staffRepository;
    private IUserRepository $userRepository;
    private IOrgUserRepository $orgUserRepository;

    public function __construct(IStaffRepository   $staffRepository, IUserRepository $userRepository,
                                IOrgUserRepository $orgUserRepository)
    {
        $this->staffRepository = $staffRepository;
        $this->userRepository = $userRepository;
        $this->orgUserRepository = $orgUserRepository;
    }

    public function add(array $data)
    {
        //kiem tra thong tin thanh vien BQL
        $existingEmail = $this->staffRepository->findByCondition('email', [$data['email']], $data['complex_id']);
        $existingPhone = $this->staffRepository->findByCondition('phone_number', [$data['phone_number']], $data['complex_id']);

        if (count($existingEmail) > 0 || count($existingPhone) > 0) {
            throw new AppException(ErrorCode::STAFF_EXISTED);
        }

        //kiem tra thong tin account
        //kiem tra tai khoan da ton tai chua (sdt la ten tai khoan)
        $existingUsername = $this->userRepository->findByCondition('username', [$data['phone_number']], $data['complex_id']);

        if ($existingUsername->count() != 0) {
            throw new AppException(ErrorCode::USER_EXISTED);
        }

        // Nếu tất cả đều hợp lệ, bắt đầu lưu vào database
        DB::beginTransaction();
        try {
            $staff = $this->staffRepository->store(Arr::except($data, ['org_id', 'role_id']));

            $passwordRaw = StringHelper::randomStrongCode();
            $userId = (string)Str::uuid();
            $dataUser[] = [
                'id' => $userId,
                'username' => $staff->phone_number,
                'staff_id' => $staff->id,
                'complex_id' => $staff->complex_id,
                'password' => Hash::make($passwordRaw),
                'created_at' => Date::now(),
                'updated_at' => Date::now()
            ];

            $user = $this->userRepository->store($dataUser);

            $orgUser = $this->orgUserRepository->store([
                'id' => (string)Str::uuid(),
                'org_id' => $data['org_id'],
                'user_id' => $userId,
                'role_id' => $data['role_id'],
                'created_at' => Date::now(),
                'updated_at' => Date::now()
            ]);
            DB::commit();

            //gui thong tin
            Mail::to($staff->email)->queue(
                new GenericMail(
                    'Thông tin đăng ký tài khoản',
                    'emails.register_account',
                    [
                        'name' => $staff->fullname,
                        'username' => $staff->phone_number,
                        'password' => $passwordRaw,
                    ]
                )
            );

            return $staff;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
