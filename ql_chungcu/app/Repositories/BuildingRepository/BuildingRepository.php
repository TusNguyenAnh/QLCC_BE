<?php

namespace App\Repositories\BuildingRepository;

use App\Models\Building;

class BuildingRepository implements IBuildingRepository
{
    public function show($perPage = 10)
    {
        return Building::paginate($perPage);
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

    public function delete(array $listBd)
    {
        Building::whereIn('id', $listBd)->update(['status' => '1']);
    }

}
