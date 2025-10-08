<?php

namespace App\Repositories\ApartmentRepository;

use App\Models\Apartment;

class ApartmentRepository implements IApartmentRepository
{

    public function findByBuildingId(string $bdId,string $perPage)
    {
        return Apartment::where('building_id', $bdId)
            ->paginate($perPage);

    }

    public function store(array $data)
    {
        $apt = Apartment::create($data)->fresh();
        return $apt;
    }

    public function update(array $data, string $id)
    {
        $apt = Apartment::where('id', $id)->first();
        if (!$apt) return null;

        $apt->update($data);
        return $apt->fresh();
    }
}
