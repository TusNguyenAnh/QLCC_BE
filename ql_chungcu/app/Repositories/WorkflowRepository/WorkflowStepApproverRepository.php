<?php

namespace App\Repositories\WorkflowRepository;

use App\Models\WorkflowStepApprover;

class WorkflowStepApproverRepository implements IWorkflowStepApproverRepository
{

    public function store(array $data)
    {
        $workflowStepApprover = WorkflowStepApprover::insert($data);
        return $workflowStepApprover;
    }

    public function findByWfStepId($wfStepId)
    {
        return WorkflowStepApprover::where('workflow_step_id', $wfStepId)
            ->pluck('position')
            ->toArray();
    }
}
