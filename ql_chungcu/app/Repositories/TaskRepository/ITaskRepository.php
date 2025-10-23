<?php

namespace App\Repositories\TaskRepository;

interface ITaskRepository
{
    public function store(array $data);
    public function findByOrgId(string $orgId,string $taskStatus);
    public function findById(string $taskId);
    public function update(array $data, string $id);

}
