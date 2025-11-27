<?php

namespace App\Services\MediaFileService;

use App\Repositories\MediaFileRepository\IMediaFileRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaFileService implements IMediaFileService
{
    private IMediaFileRepository $mediaFileRepository;

    public function __construct(IMediaFileRepository $mediaFileRepository)
    {
        $this->mediaFileRepository = $mediaFileRepository;
    }

    public function getUrlFile($ownerId)
    {
        return $this->mediaFileRepository->findByOwnerId($ownerId);
    }

    public function add(array $data, string $ownerId)
    {
        foreach ($data["files"] as $file) {
            $fileType = explode('/', $file->getMimeType())[0];
            $path = $this->saveFileInDisk($file, $data["owner_type"], $fileType); // Lưu từng ảnh và lấy đường dẫn
            $dataMediaFile[] = [
                'id' => (string)Str::uuid(),
                'owner_type' => $data["owner_type"],
                'owner_id' => $ownerId,
                'file_type' => $fileType,
                'file_name' => basename($path),
                'file_url' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        return $this->mediaFileRepository->store($dataMediaFile);
    }

    public function delete(array $data)
    {
        // TODO: Implement delete() method.
    }

    private function saveFileInDisk($file, $folder, $type)
    {
        $extension = $file->getClientOriginalExtension();
        $uniqueName = Str::uuid() . '.' . $extension; // tạo tên duy nhất
        $path = $file->storeAs("uploads/{$type}/{$folder}", $uniqueName, 'public');
        return Storage::disk('public')->url($path);
    }

    private function deleteFileInDisk($fileUrl, $folder, $type)
    {
        $path = "uploads/{$type}/{$folder}/{$fileUrl}";
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
