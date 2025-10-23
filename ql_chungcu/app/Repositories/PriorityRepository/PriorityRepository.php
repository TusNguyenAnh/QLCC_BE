<?php

namespace App\Repositories\PriorityRepository;

use App\Models\Priority;

class PriorityRepository implements IPriorityRepository
{
    public function show($perPage)
    {
        return Priority::paginate($perPage);
    }
}
