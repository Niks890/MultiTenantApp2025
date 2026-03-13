<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 't_contracts';
    protected $fillable = [
        'amount_before_tax',
        'amount_after_tax',
        'tax_amount',
        'total_paid',
        'start_at',
        'end_at',
        'due_date',
        'status',
        'payment_mode',
        'plan_id',
        'tax_id',
        'tenant_id',
        'delete_flg',
    ];

    protected $casts = [
        'payment_mode' => 'integer',
        'status' => 'integer',
        'delete_flg' => 'boolean',
        'start_at' => 'date',
        'end_at' => 'date',
    ];

    protected $hidden = [
        'delete_flg',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    protected static function booted(): void
    {
        static::updating(function (Contract $contract) {
            if ($contract->isDirty('total_paid')) {
                $totalPaid = (float) $contract->total_paid;
                $amountAfterTax = (float) $contract->amount_after_tax;
                if ($contract->delete_flg == 0) {
                    if (now()->gt($contract->end_at)) {
                        $contract->status = 4;
                    } elseif ($totalPaid >= $amountAfterTax) {
                        $contract->status = 1;
                    } elseif ($totalPaid > 0 && $totalPaid < $amountAfterTax) {
                        $contract->status = 2;
                    } elseif ($totalPaid == 0) {
                        $contract->status = 3;
                    }
                }
            }
        });
    }
}
