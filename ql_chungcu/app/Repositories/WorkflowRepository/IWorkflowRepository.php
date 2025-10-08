<?php

namespace App\Repositories\WorkflowRepository;

interface IWorkflowRepository
{
    public function show($perPage);
    public function findById($id);
    public function store(array $data);
    public function update($id, array $data);

}
