<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'm_taxes';
    protected $fillable = [
        'rate',
        'is_active',
        'delete_flg',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'delete_flg' => 'boolean',
    ];

    protected $hidden = [
        'delete_flg',
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('delete_flg', false);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function markAsDeleted(): bool
    {
        $this->delete_flg = true;
        return $this->save();
    }
}
