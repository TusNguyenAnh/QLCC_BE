<?php

namespace App\Services\ResidentService;

use App\Models\Resident;
use App\Repositories\AptResidentRepository\IAptResidentRepository;
use App\Repositories\ResidentRepository\IResidentRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ResidentService implements IResidentService
{
    private IResidentRepository $residentRepository;
    private IAptResidentRepository $aptResidentRepository;


    public function __construct(IResidentRepository $residentRepository, IAptResidentRepository $aptResidentRepository)
    {
        $this->residentRepository = $residentRepository;
        $this->aptResidentRepository = $aptResidentRepository;
    }

    public function show($perPage)
    {
        return $this->residentRepository->show($perPage);
    }

    public function add(array $data): ?Resident
    {
        $resident = $this->residentRepository->store(Arr::except($data, ['apt_id']));
        $this->aptResidentRepository->store([
                'id' => (string) Str::uuid(),
                'apt_id' => $data['apt_id'],
                'resident_id' => $resident->id
            ]);

        return $resident;
    }

    public function findByOrgId($orgId, $perPage)
    {
        return $this->residentRepository->findByOrgId($orgId, $perPage);
    }

    public function findResidentByBuildingId($bdId, $perPage)
    {
        return $this->aptResidentRepository->findResidentByBuildingId($bdId, $perPage);
    }

    public function addResInOrg(array $id, string $org_id)
    {
        return $this->residentRepository->addResInOrg($id, $org_id);
    }
}
