<?php

namespace App\Models;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Concerns\MaintenanceMode;

// class Tenant extends Model
class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasFactory, HasDatabase, HasDomains, MaintenanceMode;
    protected $table = 't_tenants';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'access_key',
        'hash_code',
        // 'db_connection',
        // 'db_host',
        // 'db_port',
        // 'db_name',
        // 'db_username',
        // 'db_password',
        'tenancy_db_connection',
        'tenancy_db_host',
        'tenancy_db_port',
        'tenancy_db_name',
        'tenancy_db_username',
        'tenancy_db_password',
        'group_id',
        'admin_tenant_id',
        'is_active',
        'delete_flg',
        'created_at',
        'updated_at',
        'logo',
        'tiktok_url',
        'facebook_url',
        'instagram_url',
        'address',
        'site_name',
        'maintenance_mode',
    ];

    protected $casts = [
        'delete_flg' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'access_key',
        'hash_code',
        // 'db_password',
        'tenancy_db_password',
        'delete_flg',
    ];


    protected function casts(): array
    {
        return [
            'tenancy_db_password' => 'encrypted',
        ];
    }

    public function adminTenant()
    {
        return $this->belongsTo(AdminTenant::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function domains()
    {
        return $this->hasMany(Domain::class, 'tenant_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'tenant_id');
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('delete_flg', false);
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'access_key',
            'hash_code',
            // 'db_connection',
            // 'db_host',
            // 'db_port',
            // 'db_name',
            // 'db_username',
            // 'db_password',
            'tenancy_db_connection',
            'tenancy_db_host',
            'tenancy_db_port',
            'tenancy_db_name',
            'tenancy_db_username',
            'tenancy_db_password',
            'group_id',
            'admin_tenant_id',
            'is_active',
            'delete_flg',
            'created_at',
            'updated_at',
            'logo',
            'tiktok_url',
            'facebook_url',
            'instagram_url',
            'address',
            'site_name',
            'maintenance_mode',
        ];
    }


    public function getIncrementing()
    {
        return true;
    }

    // public static function getDataColumn(): string
    // {
    //     return 'my-data-column';
    // }
}
