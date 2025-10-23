<?php

namespace App\Repositories\WorkflowRepository;

use App\Models\WorkflowStep;

class WorkflowStepRepository implements IWorkflowStepRepository
{

    public function show($perPage)
    {
        // TODO: Implement show() method.
    }

    public function findByWorkflowId($wfId)
    {
        return WorkflowStep::where('workflow_id', $wfId)
            ->orderBy('step_order', 'asc')
            ->get();

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
