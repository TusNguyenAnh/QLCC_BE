<?php

namespace App\Repositories\WorkflowRepository;

use App\Models\WorkflowStep;

class WorkflowStepRepository implements IWorkflowStepRepository
{

    public function show($perPage)
    {
        // TODO: Implement show() method.
    }

    public function findById($id)
    {
        // TODO: Implement findById() method.
    }

    public function store(array $data)
    {
        $workflowStep = WorkflowStep::insert($data);
        return $workflowStep;
    }

    public function update($id, array $data)
    {
        // TODO: Implement update() method.
    }
}
