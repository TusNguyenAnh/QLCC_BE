<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class WorkflowStepApprover extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'workflow_step_approver';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'workflow_step_id',
        'position',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string)Str::uuid();
            }
        });
    }

    public function workflowStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'workflow_step_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'position');
    }

}
