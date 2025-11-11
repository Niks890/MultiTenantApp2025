<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'm_provinces';

    protected $fillable = [
        'province_code',
        'name',
        'short_name',
        'code',
        'place_type',
    ];

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
}
