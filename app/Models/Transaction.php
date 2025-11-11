<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 't_transactions';
    protected $fillable = [
        'amount',
        'payment_date',
        'file_path',
        'payment_method_id',
        'contract_id',
    ];

    protected $casts = [
        'delete_flg' => 'boolean',
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    protected $hidden = [
        'delete_flg',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
