<?php

namespace App\Repositories\MoneyAccountRepository;

interface IMoneyAccountRepository
{
    public function findByBuildingId(string $bdId, string $perPage);
    public function findByBuildingAndAccNumber(string $bdId, string $accNumber);
    public function store(array $data);
    public function storeFromFile(array $data);

}
