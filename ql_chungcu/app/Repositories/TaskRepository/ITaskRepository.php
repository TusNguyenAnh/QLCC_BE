<?php

namespace App\Repositories\TaskRepository;

interface ITaskRepository
{
    public function store(array $data);
    public function findByOrgId(array $filters,string $orgId,string $taskStatus,$perPage,$approverId);
    public function findById(string $taskId);
    public function update(array $data, string $id);

}
