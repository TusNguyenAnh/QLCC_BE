<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Workflow extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'workflow';
    public $incrementing = false; // Không tự tăng ID
    protected $keyType = 'string'; // Vì UUID là chuỗi
    protected $fillable = [
        'complex_id',
        'workflow_name',
        'description',
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

    public function workflowStep()
    {
        return $this->hasMany(WorkflowStep::class, 'workflow_id', 'id')
            ->orderBy('step_order');
    }

    public function taskType()
    {
        return $this->hasMany(TaskType::class, 'workflow_id', 'id');
    }
}
