<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Apartment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'apartments';
    public $incrementing = false; // Không tự tăng ID
    protected $keyType = 'string'; // Vì UUID là chuỗi
    protected $fillable = [
        'building_id',
        'complex_id',
        'floor',
        'apt_number',
        'apt_area',
        'apt_type',
        'description',
        'status',
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
