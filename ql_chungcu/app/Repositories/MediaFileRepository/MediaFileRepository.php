<?php

namespace App\Repositories\MediaFileRepository;

use App\Models\MediaFile;
use Illuminate\Support\Facades\DB;

class MediaFileRepository implements IMediaFileRepository
{
    public function store(array $data)
    {
        $mF = MediaFile::insert($data);
        return $mF;
    }

    public function findByOwnerId(string $ownerId)
    {
        // neu can lay them loai file gi ma file do k co du lieu thi d/n o day de tra ve mang []
        $default = collect([
            'image' => collect(),
            'video' => collect(),
            'application' => collect(),
        ]);

        $listMediaFile = MediaFile::where('owner_id', $ownerId)
            ->get(['file_type', 'file_url'])
            ->groupBy('file_type')
            ->map(fn($items) => $items->pluck('file_url'))
            ->union($default);
        return $listMediaFile;
    }
}
