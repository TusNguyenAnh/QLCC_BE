<?php

namespace App\Http\Controllers;

use App\Responses\APIResponse;
use App\Services\MediaFileService\IMediaFileService;
use Illuminate\Http\Request;

class MediaFileController extends Controller
{
    protected IMediaFileService $mediaFileService;

    public function __construct(IMediaFileService $mediaFileService)
    {
        $this->mediaFileService = $mediaFileService;
    }

    public function getUrlFile(string $ownerId)
    {
        return APIResponse::success($this->mediaFileService->getUrlFile($ownerId));
    }
}
