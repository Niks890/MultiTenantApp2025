<?php
namespace App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiseAccount extends Model
{
    use HasFactory;

    protected $table = 'mise_accounts';
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
    protected $hidden = [
        'password',
        'delete_flg'
    ];
    protected $casts = [
        'date_of_birth' => 'date',
        'delete_flg' => 'boolean',
        'email_verified_at' => 'datetime',
    ];
}
