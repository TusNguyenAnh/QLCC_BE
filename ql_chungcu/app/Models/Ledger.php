<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ledger extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ledgers';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'voucher_number',   // Số phiếu thu/chi
        'type',             // revenue | expense
        'fund_type',        // quỹ_vận_hành | quỹ_bảo_trì | quỹ_dự_phòng | quỹ_khác
        'related_id',       // foreign key → revenues.id hoặc expenses.id
        'building_id',      // Tòa nhà (để tách quỹ)
        'complex_id',
        'amount',           // Số tiền giao dịch (IMMUTABLE)
        'transaction_date', // Ngày phát sinh giao dịch
        'payment_method',   // tiền_mặt | chuyển_khoản | thẻ | khác
        'bank_transaction_id', // Mã GD ngân hàng
        'bank_name',        // Tên ngân hàng
        'bank_account',     // Số tài khoản
        'description',
        'payer_name',       // Người đóng tiền
        'receiver_name',    // Người nhận tiền
        'contact_info',     // SĐT/Email
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'attachments' => 'array',
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

    /**
     * Người tạo giao dịch
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Reference đến Revenue (nếu type=revenue)
     */
    public function revenue()
    {
        return $this->belongsTo(Revenue::class, 'related_id');
    }

    /**
     * Reference đến Expense (nếu type=expense)
     */
    public function expense()
    {
        return $this->belongsTo(Expense::class, 'related_id');
    }

    /**
     * Reference đến Building
     */
    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    /**
     * LỚP 3: Các điều chỉnh cho ledger này
     */
    public function adjustments()
    {
        return $this->hasMany(AdjustmentTransaction::class, 'ledger_id');
    }

    /**
     * Media files đính kèm
     */
    public function mediaFiles()
    {
        if (!$this->attachments) {
            return collect();
        }
        return MediaFile::whereIn('id', $this->attachments)->get();
    }

    /**
     * Tính số tiền cuối cùng sau điều chỉnh
     * final_amount = ledger.amount + SUM(adjustments.amount)
     */
    public function getFinalAmountAttribute()
    {
        return (float)$this->amount + (float)$this->adjustments()->sum('amount');
    }
}
