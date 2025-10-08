<?php

namespace App\Services\WorkflowService;

use App\Models\Workflow;

interface IWorkflowService
{
    public function show($perPage);
    public function add(array $data);
}
