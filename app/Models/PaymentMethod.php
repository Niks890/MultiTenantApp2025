<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'm_payment_methods';
    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'delete_flg' => 'boolean',
    ];

    protected $hidden = [
        'delete_flg',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
