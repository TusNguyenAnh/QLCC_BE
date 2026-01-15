<?php

namespace App\Repositories\BuildingRepository;

use App\Models\Building;
use Illuminate\Support\Facades\DB;

class BuildingRepository implements IBuildingRepository
{
    public function show($complexId)
    {
        return Building::where("complex_id", $complexId)
            ->get();
    }


    public function getById(string $id)
    {
        return Building::where('id', $id)->first();
    }

    public function store(array $data)
    {
        $bd = Building::create($data)->fresh();
        return $bd;
    }

    public function update(array $data, string $id)
    {
        $bd = Building::where('id', $id)->first();
        if (!$bd) return null;

        $bd->update($data);
        return $bd->fresh();
    }

    public function updateRatio(array $data)
    {
        return DB::transaction(function () use ($data) {
            foreach ($data as $update) {
                Building::where('id', $update['id'])
                    ->update(['financial_ratio' => $update['financial_ratio']]);
            }
        });
    }

    public function delete(array $listBd)
    {
        Building::whereIn('id', $listBd)->update(['status' => '1']);
    }

    public function findByCondition($field, $listItem, $complexId)
    {
        return Building::whereIn($field, $listItem)
            ->where('complex_id', $complexId)
            ->pluck('id', $field);
    }

    public function getBuildingRatio($listItem, $complexId)
    {
        return Building::whereIn('id', $listItem)
            ->where('complex_id', $complexId)
            ->whereNotNull('financial_ratio')
            ->pluck('financial_ratio', 'id');
    }

}
