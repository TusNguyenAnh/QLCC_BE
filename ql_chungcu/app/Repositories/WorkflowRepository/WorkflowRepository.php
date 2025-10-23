<?php

namespace App\Repositories\WorkflowRepository;

use App\Models\Workflow;

class WorkflowRepository implements IWorkflowRepository
{

    public function show($perPage, $complexId)
    {
        return Workflow::with('workflowStep')
            ->where('complex_id', $complexId)
            ->paginate($perPage);
    }

    public function findById($id)
    {
        // TODO: Implement findById() method.
    }

    public function store(array $data)
    {
        $workflow = Workflow::create($data)->fresh();
        return $workflow;
    }

    public function update($id, array $data)
    {
        // TODO: Implement update() method.
    }
}
