<?php

namespace App\Services\ComplexService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Helpers\StringHelper;
use App\Mail\GenericMail;
use App\Repositories\AuthorizationRepository\IRoleRepository;
use App\Repositories\AuthorizationRepository\IUserRoleRepository;
use App\Repositories\ComplexRepository\IComplexRepository;
use App\Repositories\FinancialModelRepository\IFinancialModelRepository;
use App\Repositories\OrgUserRepository\IOrgUserRepository;
use App\Repositories\ResidentRepository\IResidentRepository;
use App\Repositories\UserRepository\IUserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PHPUnit\Exception;

class ComplexService implements IComplexService
{
    protected IComplexRepository $complexRepository;
    protected IUserRepository $userRepository;
    private IOrgUserRepository $orgUserRepository;
    private IRoleRepository $roleRepository;
    private IFinancialModelRepository $financialModelRepository;


    public function __construct(IComplexRepository  $complexRepository,
                                IUserRepository     $userRepository,
                                IRoleRepository     $roleRepository,
                                IOrgUserRepository $orgUserRepository,
    IFinancialModelRepository $financialModelRepository)
    {
        $this->complexRepository = $complexRepository;
        $this->userRepository = $userRepository;
        $this->orgUserRepository = $orgUserRepository;
        $this->roleRepository = $roleRepository;
        $this->financialModelRepository = $financialModelRepository;
    }

    public function show($complexFilterRequest, $status, $perPage)
    {
        return $this->complexRepository->show($complexFilterRequest, $status, $perPage);
    }

    public function findById(string $id)
    {
        $complex = $this->complexRepository->getById($id);
        if (!$complex) {
            throw new AppException(ErrorCode::NOT_FOUND);
        }
        return $complex;
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

        DB::beginTransaction();
        foreach ($ids as $complexId) {
            try {
                // tao account
                $complex = $this->complexRepository->getById($complexId);
                $passwordRaw = StringHelper::randomStrongCode();
                $data = [
                    'id' => (string)Str::uuid(),
                    'username' => $complex->phone_contact,
                    'complex_id' => $complex->id,
                    'password' => Hash::make($passwordRaw),
                    'created_at' => Date::now(),
                    'updated_at' => Date::now()
                ];

                $user = $this->userRepository->store($data);

                // gan role admin cho account
                $dataOrgUser[] = [
                    'id' => (string)Str::uuid(),
                    'user_id' => $data['id'],
                    'role_id' => $roleAdmin->id
                ];
                $this->orgUserRepository->store($dataOrgUser);

                //gui thong tin
                Mail::to($complex->email_contact)->queue(
                    new GenericMail(
                        'Thông tin đăng ký tài khoản',
                        'emails.register_account',
                        [
                            'name' => $complex->name_contact,
                            'username' => $data['username'],
                            'password' => $passwordRaw,
                            'complexName' => "Hệ thống quản lý chung cư",
                        ]
                    )
                );
                DB::commit();

            } catch (\Exception $mailException) {
                Log::error("Gửi mail thất bại cho complex ID {$complex->id}: " . $mailException->getMessage());
                DB::rollBack();
//                throw new AppException(ErrorCode::NOT_CREATED);
                throw new \Exception($mailException->getMessage());
            }
        }
    }

    public function rejectComplex(array $ids)
    {
        return $this->complexRepository->delete($ids);
    }

    public function showFinancialModel()
    {
        return $this->financialModelRepository->show();
    }
}
