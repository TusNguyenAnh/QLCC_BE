<?php

namespace App\Services\PriorityService;

use App\Repositories\PriorityRepository\IPriorityRepository;

class PriorityService implements IPriorityService
{
    private IPriorityRepository $priorityRepository;

    public function __construct(IPriorityRepository $priorityRepository)
    {
        $this->priorityRepository = $priorityRepository;
    }

    public function show($perPage)
    {
        return $this->priorityRepository->show($perPage);
    }
}
