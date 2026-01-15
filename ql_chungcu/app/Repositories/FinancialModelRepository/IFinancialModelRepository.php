<?php

namespace App\Repositories\FinancialModelRepository;

interface IFinancialModelRepository
{
    public function findById(string $id);
    public function findByModelName(string $name);
    public function show();
}
