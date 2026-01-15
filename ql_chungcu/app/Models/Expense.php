<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Expense extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'expenses';
    public $incrementing = false;
    protected $keyType = 'string';
    // LỚP 1 - NGHĨA VỤ PHẢI CHI
    // Expense = Khoản phải chi (KHÔNG phải giao dịch tiền)
    // amount_paid là CACHE tự động từ ledgers để tăng performance
    protected $fillable = [
        'task_id',
        'title',
        'category',         // mua_sắm | dịch_vụ | điện_nước | sửa_chữa | lương | khác
        'original_amount',  // Số tiền GỐC phải chi (KHÔNG đổi)
        'description',
        'amount_paid',      // Cache tổng đã chi (TỰ ĐỘNG từ ledgers)
        'status',           // unpaid | partial | paid
        'vendor',
        'created_by',
        'approved_by',
        'approved_at',
        'approved'
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'approved_at' => 'datetime',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * LỚP 2: Giao dịch thực tế (ledgers)
     */
    public function ledgers()
    {
        return $this->hasMany(Ledger::class, 'related_id')->where('type', 'expense');
    }

    /**
     * Tính tổng tiền đã chi (từ ledgers + adjustments)
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
