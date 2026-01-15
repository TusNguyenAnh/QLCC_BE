<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TaskHistory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'task_history';
    public $incrementing = false; // Không tự tăng ID
    protected $keyType = 'string'; // Vì UUID là chuỗi

    protected $fillable = [
        'task_id',
        'step_id',
        'approver_id',
        'action',
        'org_id',
        'comment',
        "created_at",
        "updated_at",
    ];

    protected $hidden = [
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
