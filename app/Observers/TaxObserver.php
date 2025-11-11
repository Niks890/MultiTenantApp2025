<?php

namespace App\Observers;

use App\Models\Tax;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaxObserver
{
    /**
     * Handle the Tax "created" event.
     */
    public function created(Tax $tax): void
    {
        Log::info('Thêm mới thuế thành công', ['id' => $tax->id, 'id_user' => Auth::id()]);
    }

    public function creating(Tax $tax): void
    {
        $activeExists = Tax::notDeleted()
            ->active()
            ->lockForUpdate()
            ->exists();

        $tax->is_active = !$activeExists;
    }

    /**
     * Handle the Tax "updated" event.
     */
    public function updated(Tax $tax): void
    {
        if ($tax->wasChanged('delete_flg')) {
            if ($tax->delete_flg) {
                Log::info('Xóa thuế thành công', ['id' => $tax->id, 'id_user' => Auth::id()]);
            } else {
                Log::info('Phục hồi thuế thành công', ['id' => $tax->id, 'id_user' => Auth::id()]);
            }
        } else {
            Log::info('Cập nhật thuế thành công', ['id' => $tax->id, 'id_user' => Auth::id()]);
        }
    }

    public function updating(Tax $tax): void
    {
        DB::transaction(function () use ($tax) {

            if ($tax->isDirty('delete_flg') && $tax->delete_flg === true) {
                if ($tax->getOriginal('is_active') === true) {
                    throw new \Exception('Không thể xóa bản ghi đang hoạt động.');
                }
            }

            if ($tax->isDirty('is_active') && $tax->is_active === true) {
                Tax::notDeleted()
                    ->whereKeyNot($tax->id)
                    ->active()
                    ->update(['is_active' => false]);
            }

            if ($tax->isDirty('is_active') && $tax->is_active === false && $tax->getOriginal('is_active') === true) {
                $otherActiveExists = Tax::notDeleted()
                    ->whereKeyNot($tax->id)
                    ->active()
                    ->lockForUpdate()
                    ->exists();

                if (!$otherActiveExists) {
                    throw new \Exception('Không thể khoá bản ghi đang hoạt động duy nhất.');
                }
            }
        });
    }


    /**
     * Handle the Tax "deleted" event.
     */
    public function deleted(Tax $tax): void
    {
        //
    }

    /**
     * Handle the Tax "restored" event.
     */
    public function restored(Tax $tax): void
    {
        //
    }

    /**
     * Handle the Tax "force deleted" event.
     */
    public function forceDeleted(Tax $tax): void
    {
        //
    }
}
