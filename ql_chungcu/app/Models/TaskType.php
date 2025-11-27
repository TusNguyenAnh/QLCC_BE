<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TaskType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'task_type';
    public $incrementing = false; // Không tự tăng ID
    protected $keyType = 'string'; // Vì UUID là chuỗi
    protected $fillable = [
        'workflow_id',
        'priority_id',
        'complex_id',
        'description',
        'type_name',
        'status',
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

    public function priority()
    {
        return $this->hasOne(Priority::class, 'id', 'priority_id')->select('id','priority_name');
    }
}
