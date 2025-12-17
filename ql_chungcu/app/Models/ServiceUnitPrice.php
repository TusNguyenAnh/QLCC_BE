<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ServiceUnitPrice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'service_unit_prices';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'year',
        'month',
        'price_per_m2',
        "created_at",
        'complex_id'
    ];

    protected $hidden = [
        "updated_at",
        "deleted_at"
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
}
