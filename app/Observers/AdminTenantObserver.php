<?php

namespace App\Observers;

use App\Models\AdminTenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminTenantObserver
{
    /**
     * Handle the AdminTenant "created" event.
     */
    public function created(AdminTenant $adminTenant): void
    {
        Log::info('Thêm mới chủ cửa hiệu thành công', ['id' => $adminTenant->id]);
    }

    /**
     * Handle the AdminTenant "updated" event.
     */
    public function updated(AdminTenant $adminTenant): void
    {
        if ($adminTenant->wasChanged('delete_flg')) {
            if ($adminTenant->delete_flg) {
                Log::info('Xóa chủ cửa hiệu thành công', ['id' => $adminTenant->id, 'id_user' => Auth::id()]);
            } else {
                Log::info('Phục hồi chủ cửa hiệu thành công', ['id' => $adminTenant->id, 'id_user' => Auth::id()]);
            }
        } else {
            Log::info('Cập nhật chủ cửa hiệu thành công', ['id' => $adminTenant->id, 'id_user' => Auth::id()]);
        }
    }

    /**
     * Handle the AdminTenant "deleted" event.
     */
    public function deleted(AdminTenant $adminTenant): void
    {
        //
    }

    /**
     * Handle the AdminTenant "restored" event.
     */
    public function restored(AdminTenant $adminTenant): void
    {
        //
    }

    /**
     * Handle the AdminTenant "force deleted" event.
     */
    public function forceDeleted(AdminTenant $adminTenant): void
    {
        //
    }

    public function updating(AdminTenant $adminTenant): void
    {
        if ($adminTenant->isDirty('delete_flg') && $adminTenant->delete_flg == 1) {
            $isBeingSoftDeleted = $adminTenant->getOriginal('delete_flg') != 1;

            if ($isBeingSoftDeleted) {
                $adminTenant->tenants()->update(['admin_tenant_id' => null]);
            }
        }
    }
}
