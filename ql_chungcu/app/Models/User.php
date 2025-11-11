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
        'id',
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
//            'roles' => $this->roles()->pluck('roles.role_name')->toArray(), // hoặc 'name'
            'org_id' => $this->resident ? $this->resident->org_id : null,
            'permissions' => $this->roles->flatMap(function ($role) {
                return $role->permissions->pluck('name'); // Hoặc pluck('id') nếu muốn ID
            })->unique()->values()->toArray(),
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

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    public function resident()
    {
        // belongsTo vì user giữ khóa ngoại resident_id
        return $this->belongsTo(Resident::class, 'res_id', 'id');
    }

}
