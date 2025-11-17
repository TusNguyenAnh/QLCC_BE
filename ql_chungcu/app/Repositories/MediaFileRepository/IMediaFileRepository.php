<?php

namespace App\Repositories\MediaFileRepository;

interface IMediaFileRepository
{
    public function store(array $data);
    public function findByOwnerId(string $ownerId);
}
