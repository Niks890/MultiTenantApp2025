<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\Contract;
use Illuminate\Support\Facades\DB;

class UpdateTenantExpiryStatus extends Command
{
    // Tên lệnh gọi
    protected $signature = 'tenant:check-expiry';

    protected $description = 'Khóa các Tenant có hợp đồng đã hết hạn';

    public function handle()
    {
        $this->info('Bắt đầu kiểm tra thời hạn hợp đồng của các Tenant...');

        // 1. Lấy danh sách ID của các Tenant có hợp đồng đã hết hạn
        // Điều kiện: Ngày kết thúc < Hiện tại và chưa bị xóa
        $expiredTenantIds = Contract::where('end_at', '<', now())
            ->where('delete_flg', 0)
            ->pluck('tenant_id')
            ->unique();

        if ($expiredTenantIds->isEmpty()) {
            $this->info('Không có Tenant nào hết hạn.');
            return;
        }

        $count = 0;
        foreach ($expiredTenantIds as $tenantId) {
            $tenant = Tenant::find($tenantId);

            if ($tenant && $tenant->is_active == 1) {
                $hasActiveContract = Contract::where('tenant_id', $tenantId)
                    ->where('end_at', '>=', now())
                    ->where('delete_flg', 0)
                    ->exists();

                if (!$hasActiveContract) {
                    $maintenanceData = [
                        'time' => now()->timestamp,
                        'retry' => null,
                        'allowed' => [],
                        'message' => 'Cửa hiệu đã hết hạn dịch vụ. Vui lòng gia hạn.'
                    ];
                    $tenant->update([
                        'is_active' => 0,
                        'maintenance_mode' => json_encode($maintenanceData, JSON_UNESCAPED_UNICODE)
                    ]);

                    $this->line("Tenant ID: {$tenantId} - {$tenant->name} đã bị khóa do hết hạn.");
                    $count++;
                }
            }
        }

        $this->info("Hoàn tất! Đã khóa {$count} Tenant.");
    }
}
