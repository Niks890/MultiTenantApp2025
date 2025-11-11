<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public const DEFAULT_FREE_PLAN_ID = 9999;
    protected $table = 'm_plans';
    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'cycle',
        'is_active',
        'delete_flg',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'delete_flg' => 'boolean',
        'price' => 'decimal:2',
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

    public function markAsDeleted(): bool
    {
        $this->delete_flg = true;
        return $this->save();
    }
}
