<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Resident extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'residents';
    public $incrementing = false; // Không tự tăng ID
    protected $keyType = 'string'; // Vì UUID là chuỗi
    protected $fillable = [
        'org_id',
        'res_id',
        'fullname',
        'gender',
        'email',
        'birthday',
        'phone_number',
        'status',
        'relationship',
        'cccd',
        'apt_id'
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

    public function user()
    {
        return $this->hasOne(User::class, 'res_id', 'id');
    }
}
