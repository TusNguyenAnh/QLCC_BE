<?php

namespace App\Repositories\WorkflowRepository;

interface IWorkflowStepApproverRepository
{
    public function store(array $data);
    public function findByWfStepId($wfStepId);
}
