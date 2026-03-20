<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contract;
use Illuminate\Support\Facades\Log;

class UpdateContractStatus extends Command
{

    protected $signature = 'contract:update-status';

    protected $description = 'Cập nhật trạng thái hợp đồng dựa trên ngày hết hạn và số tiền đã thanh toán';

    public function handle()
    {
        $this->info('Đang bắt đầu cập nhật trạng thái hợp đồng...');
        Contract::where('delete_flg', 0)->chunk(100, function ($contracts) {
            foreach ($contracts as $contract) {
                $totalPaid = (float) $contract->total_paid;
                $amountAfterTax = (float) $contract->amount_after_tax;
                $oldStatus = $contract->status;
                if (now()->gt($contract->end_at)) {
                    $contract->status = 4;
                }
                elseif ($totalPaid >= $amountAfterTax) {
                    $contract->status = 1;
                }
                elseif ($totalPaid > 0 && $totalPaid < $amountAfterTax) {
                    $contract->status = 2;
                }
                elseif ($totalPaid == 0) {
                    $contract->status = 3;
                }
                if ($contract->isDirty('status')) {
                    $contract->save();
                    $this->line("Contract ID: {$contract->id} chuyển từ {$oldStatus} sang {$contract->status}");
                }
            }
        });
        $this->info('Cập nhật trạng thái hoàn tất!');
    }
}
