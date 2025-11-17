<?php

namespace App\Services\MediaFileService;

interface IMediaFileService
{
    public function getUrlFile($ownerId);

    public function add(array $data,string $ownerId);
    public function delete(array $data);

}
