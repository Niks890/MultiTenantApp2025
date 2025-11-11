<?php

namespace App\Observers;

use App\Models\Plan;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PlanObserver
{
    /**
     * Handle the Plan "created" event.
     */
    public function created(Plan $plan): void
    {
        Log::info('Thêm mới gói dịch vụ thành công', ['id' => $plan->id, 'id_user' => Auth::id()]);
    }

    /**
     * Handle the Plan "updated" event.
     */
    public function updated(Plan $plan): void
    {
        if ($plan->wasChanged('delete_flg')) {
            if ($plan->delete_flg) {
                Log::info('Xóa gói dịch vụ thành công', ['id' => $plan->id, 'id_user' => Auth::id()]);
            } else {
                Log::info('Phục hồi gói dịch vụ thành công', ['id' => $plan->id, 'id_user' => Auth::id()]);
            }
        } else {
            Log::info('Cập nhật gói dịch vụ thành công', ['id' => $plan->id, 'id_user' => Auth::id()]);
        }
    }

    /**
     * Handle the Plan "deleted" event.
     */
    public function deleted(Plan $plan): void
    {
        //
    }

    /**
     * Handle the Plan "restored" event.
     */
    public function restored(Plan $plan): void
    {
        //
    }

    /**
     * Handle the Plan "force deleted" event.
     */
    public function forceDeleted(Plan $plan): void
    {
        //
    }

    public function deleting(Plan $plan): void
    {
        if ($plan->id === Plan::DEFAULT_FREE_PLAN_ID) {
            throw new Exception(__('cannotDeleteDefaultPlan'));
        }
    }
}
