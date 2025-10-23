<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TaskHistory extends Model
{
    use HasFactory;

    protected $table = 'task_history';
    public $incrementing = false; // Không tự tăng ID
    protected $keyType = 'string'; // Vì UUID là chuỗi
    public $timestamps = false;

    protected $fillable = [
        'task_id',
        'step_id',
        'user_id',
        'action',
        'org_id',
        'comment'
    ];

    protected $hidden = [
        "created_at",
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
