<?php

namespace App\Repositories\FinancialModelRepository;

use App\Models\FinancialModel;

class FinancialModelRepository implements IFinancialModelRepository
{
    public function show()
    {
        return FinancialModel::get();
    }
    public function findById(string $id)
    {
        return FinancialModel::where('id', $id)->first();
    }

    public function findByModelName(string $name)
    {
        return FinancialModel::where('type', $name)->first();
    }
}
