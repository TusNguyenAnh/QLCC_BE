<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory;

    protected $table = 'task';
    public $incrementing = false; // Không tự tăng ID
    protected $keyType = 'string'; // Vì UUID là chuỗi
    protected $fillable = [
        'complex_id',
        'tasktype_id',
        'current_step',
        'current_org_id',
        'user_id',
        'description',
        'task_name',
        'status',
        "created_at",
    ];

    protected $hidden = [
        "updated_at",
        "deleted_at"
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Nếu chưa có id → tự động tạo UUID
            if (empty($model->id)) {
                $model->id = (string)Str::uuid();
            }
        });
    }
}
