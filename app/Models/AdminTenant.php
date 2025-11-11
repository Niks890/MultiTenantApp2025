<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminTenant extends Model
{
    protected $table = 'm_admin_tenants';
    protected $fillable = [
        'username',
        'password',
        'display_name',
        'date_of_birth',
        'address',
        'phone_number',
        'email',
        'email_verified_at',
        'delete_flg',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'delete_flg' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'delete_flg',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function scopeSearch($query, string $search)
    {
        $search = '%' . $search . '%';
        return $query->where(function ($q) use ($search) {
            $q->where('display_name', 'like', $search)
                ->orWhere('username', 'like', $search)
                ->orWhere('email', 'like', $search)
                ->orWhere('phone_number', 'like', $search);
        });
    }

    public function scopeByTenant($query, string $tenantId)
    {
        return $query->whereHas('tenants', function ($q) use ($tenantId) {
            $q->where('id', $tenantId);
        });
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('delete_flg', false);
    }

    public function markAsDeleted()
    {
        $this->delete_flg = true;
        return $this->save();
    }
}
