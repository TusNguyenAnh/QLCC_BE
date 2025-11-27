<?php

namespace App\Services\ComplexService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Helpers\StringHelper;
use App\Mail\GenericMail;
use App\Repositories\AuthorizationRepository\IRoleRepository;
use App\Repositories\AuthorizationRepository\IUserRoleRepository;
use App\Repositories\ComplexRepository\IComplexRepository;
use App\Repositories\ResidentRepository\IResidentRepository;
use App\Repositories\UserRepository\IUserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PHPUnit\Exception;

class ComplexService implements IComplexService
{
    protected IComplexRepository $complexRepository;
    protected IUserRepository $userRepository;
    private IUserRoleRepository $userRoleRepository;
    private IRoleRepository $roleRepository;


    public function __construct(IComplexRepository  $complexRepository,
                                IUserRepository     $userRepository,
                                IRoleRepository     $roleRepository,
                                IUserRoleRepository $userRoleRepository)
    {
        $this->complexRepository = $complexRepository;
        $this->userRepository = $userRepository;
        $this->userRoleRepository = $userRoleRepository;
        $this->roleRepository = $roleRepository;
    }

    public function show($complexFilterRequest, $status, $perPage)
    {
        return $this->complexRepository->show($complexFilterRequest, $status, $perPage);
    }

    public function findById(string $id)
    {
        // TODO: Implement findById() method.
    }

    public function add(array $data)
    {
        $complexName = $this->complexRepository->findByComplexName($data['complex_name']);
        if ($complexName) {
            throw new AppException(ErrorCode::COMPLEX_NAME_EXISTED);
        }

        $complexAddress = $this->complexRepository->findByComplexAddress($data['address']);
        if ($complexAddress) {
            throw new AppException(ErrorCode::ADDRESS_EXISTED);
        }

        $complexPhoneContact = $this->complexRepository->findByComplexPhoneContact($data['phone_contact']);
        if ($complexPhoneContact) {
            throw new AppException(ErrorCode::PHONE_CONTACT_EXISTED);
        }

        $complex = $this->complexRepository->store($data);


        return $complex;
    }

    public function update(string $id, array $data)
    {
        // TODO: Implement update() method.
    }

    public function delete(array $listBd)
    {
        // TODO: Implement delete() method.
    }

    public function approveComplex(array $ids)
    {
        try {
            $this->complexRepository->approveComplex($ids);
        } catch (Exception $exception) {
            throw new AppException(ErrorCode::UNCATEGORIZED_EXCEPTION);
        }

        $roleAdmin = $this->roleRepository->findByRoleName("admin");

        foreach ($ids as $complexId) {
            try {
                // tao account
                $complex = $this->complexRepository->getById($complexId);
                $passwordRaw = StringHelper::randomStrongCode();
                $data = [
                    'username' => $complex->phone_contact,
                    'complex_id' => $complex->id,
                    'password' => Hash::make($passwordRaw),
                ];
                $user = $this->userRepository->store($data);

                // gan role admin cho account
                $dataUserRole[] = [
                    'id' => (string)Str::uuid(),
                    'user_id' => $user->id,
                    'role_id' => $roleAdmin->id
                ];
                $this->userRoleRepository->store($dataUserRole);

                //gui thong tin
                Mail::to($complex->email_contact)->queue(
                    new GenericMail(
                        'Thông tin đăng ký tài khoản',
                        'emails.register_account',
                        [
                            'name' => $complex->name_contact,
                            'username' => $user->username,
                            'password' => $passwordRaw,
                        ]
                    )
                );
            } catch (\Throwable $mailException) {
                Log::error("Gửi mail thất bại cho complex ID {$complex->id}: " . $mailException->getMessage());
                // throw new AppException(ErrorCode::MAIL_SEND_FAILED);
            }
        }
    }

    public function rejectComplex(array $ids)
    {
        return $this->complexRepository->delete($ids);
    }
}
