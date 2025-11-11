<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    protected $table = 'm_groups';
    protected $fillable = [
        'name',
        'description',
        'delete_flg',
    ];

    protected $casts = [
        'delete_flg' => 'boolean',
    ];

    protected $hidden = [
        'delete_flg',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    protected static function booted(): void
    {
        static::updating(function (Group $group) {
            if ($group->isDirty('delete_flg') && $group->delete_flg == 1) {
                $isBeingSoftDeleted = $group->getOriginal('delete_flg') != 1;
                if ($isBeingSoftDeleted) {
                    $group->tenants()->update([
                        'group_id' => null,
                    ]);
                }
            }
        });
    }

}
