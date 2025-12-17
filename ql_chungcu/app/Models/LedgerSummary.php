<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class LedgerSummary extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ledger_summary';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'year',
        'month',
        'total_in',
        'total_out',
        'opening_balance',
        'closing_balance',
        "created_at",
        'complex_id',
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
