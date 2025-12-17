<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AdjustmentTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    // LỚP 3 - ĐIỀU CHỈNH LEDGER CỤ THỂ
    // Adjustment LUÔN reference đến 1 ledger cụ thể
    // amount: dùng số âm để giảm, số dương để tăng
    protected $table = 'adjustment_trans';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ledger_id',        // Tham chiếu ledger cụ thể cần điều chỉnh
        'amount',           // Số tiền điều chỉnh (âm = giảm, dương = tăng)
        'reason',           // Lý do điều chỉnh
        'created_by',
        "created_at",
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
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

    public function ledger()
    {
        return $this->belongsTo(Ledger::class, 'ledger_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
