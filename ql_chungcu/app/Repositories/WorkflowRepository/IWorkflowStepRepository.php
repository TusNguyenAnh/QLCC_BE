<?php

namespace App\Repositories\WorkflowRepository;

use App\Models\WorkflowStep;

interface IWorkflowStepRepository
{
    public function show($perPage);
    public function findById($id);
    public function store(array $data);
    public function update($id, array $data);
}
