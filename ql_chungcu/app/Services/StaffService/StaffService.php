<?php

namespace App\Services\StaffService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Helpers\StringHelper;
use App\Mail\GenericMail;
use App\Repositories\StaffRepository\IStaffRepository;
use App\Repositories\UserRepository\IUserRepository;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StaffService implements IStaffService
{
    private IStaffRepository $staffRepository;
    private IUserRepository $userRepository;

    public function __construct(IStaffRepository $staffRepository, IUserRepository $userRepository)
    {
        $this->staffRepository = $staffRepository;
        $this->userRepository = $userRepository;
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
            $staff = $this->staffRepository->store($data);

            $passwordRaw = StringHelper::randomStrongCode();
            $dataUser[] = [
                'id' => (string)Str::uuid(),
                'username' => $staff->phone_number,
                'staff_id' => $staff->id,
                'complex_id' => $staff->complex_id,
                'password' => Hash::make($passwordRaw),
                'created_at' => Date::now(),
                'updated_at' => Date::now()
            ];

            $this->userRepository->store($dataUser);
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
