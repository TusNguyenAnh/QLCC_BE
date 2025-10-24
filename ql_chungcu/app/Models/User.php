<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users';
    public $incrementing = false; // Không tự tăng ID
    protected $keyType = 'string';
    protected $fillable = [
        'username',
        'password',
        'res_id',
        'status',
        'refresh_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'refresh_token',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role ? $this->role->role_name : null,   // lấy tên role
            'org_id' => $this->resident ? $this->resident->org_id : null,
//            'buildings' => $this->getBuildingIdsManage(), // dùng accessor ở trên
        ];
    }

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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function resident()
    {
        // belongsTo vì user giữ khóa ngoại resident_id
        return $this->belongsTo(Resident::class, 'res_id', 'id');
    }

}
