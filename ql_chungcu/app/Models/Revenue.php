<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Revenue extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'revenues';
    public $incrementing = false;
    protected $keyType = 'string';
    // LỚP 1 - NGHĨA VỤ PHẢI THU
    // Revenue = Khoản phải thu (KHÔNG phải giao dịch tiền)
    // amount_paid là CACHE tự động từ ledgers để tăng performance
    protected $fillable = [
        'task_id',
        'title',
        'apartment_id',
        'building_id',
        'original_amount',  // Số tiền GỐC phải thu (KHÔNG đổi)
        'amount_paid',      // Cache tổng đã thu (TỰ ĐỘNG từ ledgers)
        'status',           // unpaid | partial | paid | overpaid
        'description',
        'approved_by',
        'approved_at',
        'approved'
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
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
            if (empty($model->id)) {
                $model->id = (string)Str::uuid();
            }
        });
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class, 'apartment_id');
    }

    /**
     * LỚP 2: Giao dịch thực tế (ledgers)
     */
    public function ledgers()
    {
        return $this->hasMany(Ledger::class, 'related_id')->where('type', 'revenue');
    }

    /**
     * Tính tổng tiền đã thu (từ ledgers + adjustments)
     */
    public function getTotalPaidAttribute()
    {
        $ledgerTotal = $this->ledgers()->sum('amount');

        // Cộng thêm adjustments
        $adjustmentTotal = 0;
        foreach ($this->ledgers as $ledger) {
            $adjustmentTotal += $ledger->adjustments()->sum('amount');
        }

        return $ledgerTotal + $adjustmentTotal;
    }

    /**
     * Tính công nợ còn lại
     */
    public function getRemainingAttribute()
    {
        return $this->original_amount - $this->total_paid;
    }
}
