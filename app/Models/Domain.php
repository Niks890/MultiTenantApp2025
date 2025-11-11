<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $table = 'm_domains';
    protected $fillable = [
        'domain',
        'is_active',
        'tenant_id',
    ];

    protected $hidden = [
        'delete_flg',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
