<?php

namespace App\Repositories\MoneyAccountRepository;

use App\Models\MoneyAccount;

class MoneyAccountRepository implements IMoneyAccountRepository
{

    /**
     * Lấy tài khoản theo tòa nhà (có phân trang)
     */
    public function findByBuildingId(string $bdId, string $perPage)
    {
        return MoneyAccount::where('building_id', $bdId)
            ->paginate($perPage);
    }

    /**
     * Tìm tài khoản duy nhất bằng tòa nhà + số tài khoản
     */
    public function findByBuildingAndAccNumber(string $bdId, string $accNumber)
    {
        return MoneyAccount::where('building_id', $bdId)
            ->where('account_number', $accNumber)
            ->first();
    }

    /**
     * Tạo tài khoản mới và trả về bản ghi fresh
     */
    public function store(array $data)
    {
        return MoneyAccount::create($data)->fresh();
    }

    /**
     * Upsert: Nếu (building_id, account_number) tồn tại thì update
     * Nếu không tồn tại thì tạo mới (nhưng không cập nhật trường nào)
     */
    public function storeFromFile(array $data)
    {
        $moneyAcc = MoneyAccount::upsert(
            $data,
            ['building_id', 'account_number'],  // Khóa để kiểm tra sự tồn tại
            []                                   // Không cập nhật trường nào
        );
        return $moneyAcc;
    }
}
