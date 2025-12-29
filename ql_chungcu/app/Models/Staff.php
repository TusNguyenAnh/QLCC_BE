<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Staff extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'staffs';
    public $incrementing = false; // Không tự tăng ID
    protected $keyType = 'string'; // Vì UUID là chuỗi
    protected $fillable = [
        'org_id',
        'complex_id',
        'fullname',
        'position',
        'email',
        'phone_number',
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

    public function user()
    {
        return $this->hasOne(User::class, 'staff_id', 'id');
    }
}
