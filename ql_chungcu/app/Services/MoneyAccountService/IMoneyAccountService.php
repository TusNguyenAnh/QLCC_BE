<?php

namespace App\Services\MoneyAccountService;

interface IMoneyAccountService
{
    public function add(array $data);
    public function importMoneyAccountFromExcel($file);

}
