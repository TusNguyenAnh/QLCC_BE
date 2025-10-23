<?php

namespace App\Repositories\TaskRepository;

interface ITaskTypeRepository
{
    public function show($perPage,$complexId);
    public function store(array $data);
    public function update(array $data, string $id);
    public function findById(string $id);


}
