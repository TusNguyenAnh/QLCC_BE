<?php

namespace App\Repositories\ApartmentRepository;

use App\Models\Apartment;

class ApartmentRepository implements IApartmentRepository
{
    public function getById(string $id)
    {
        return Apartment::where('id', $id)->first();
    }

    public function findByBuildingId(string $bdId, string $perPage)
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

    public function getAptIdByBuildingAndApartmentNumber(array $apartmentPairs, $complexId)
    {
        return Apartment::join('buildings', 'buildings.id', '=', 'apartments.building_id')
            ->where('apartments.complex_id', $complexId)
            ->where(function ($q) use ($apartmentPairs) {
                foreach ($apartmentPairs as $pair) {
                    $q->orWhere(function ($q2) use ($pair) {
                        $q2->where('building_name', $pair['building_name'])
                            ->where('apt_number', $pair['apt_number']);
                    });
                }
            })
            ->select('apartments.id', 'buildings.building_name', 'apartments.apt_number')
            ->get()
            ->keyBy(fn($a) => $a->building_name . '_' . $a->apt_number);

    }

    public function storeFromFile(array $data)
    {
        $aptResident = Apartment::upsert(
            $data,
            ['building_id', 'apt_number'], // neu cap field nay k co se them moi con co se update
            [] //update cac truong nay neu trung cao field tren
        );
        return $aptResident;
    }

    public function findByBuildingAndAptNumber(string $bdId, string $aptNumber)
    {
        return Apartment::where('building_id', $bdId)
            ->where('apt_number', $aptNumber)
            ->first();
    }
}
