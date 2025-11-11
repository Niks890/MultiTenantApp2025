<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $table = 't_wards';

    protected $fillable = [
        'ward_code',
        'name',
        'province_id',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
