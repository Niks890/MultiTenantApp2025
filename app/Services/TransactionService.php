<?php

namespace App\Services;

use App\Models\Contract;
use App\Repositories\Contracts\ContractRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    protected $transactionRepository;
    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {

            if (isset($data['file_path']) && $data['file_path'] instanceof \Illuminate\Http\UploadedFile) {
                $data['file_path'] = $this->storeTransactionImage($data['file_path']);
            }
            $contract = Contract::where('id', $data['contract_id'])
                ->lockForUpdate()
                ->firstOrFail();
            if ($contract->total_paid + $data['amount'] > $contract->amount_after_tax) {
                throw new \Exception('Số tiền thanh toán vượt quá tổng hợp đồng');
            }
            $transaction = $this->transactionRepository->create([
                'contract_id' => $data['contract_id'],
                'payment_method_id' => $data['payment_method'],
                'amount' => $data['amount'],
                'payment_date' => $data['payment_date'],
                'file_path' => $data['file_path'] ?? null,
            ]);
            $contract->total_paid += $data['amount'];
            if ($contract->total_paid >= $contract->amount_after_tax) {
                $contract->status = 1;
            } else {
                $contract->status = 3;
            }
            $contract->save();
            DB::commit();
            Log::channel('system_user')->info('Tạo giao dịch thành công', [
                'ip' => request()->ip(),
                'route' => '/transaction.create',
                'data' => json_encode($data)
            ]);
            return $transaction;
        } catch (\Exception $e) {

            DB::rollBack();

            Log::channel('system_user')->error('Tạo giao dịch thất bại ' . $e->getMessage(), [
                'ip' => request()->ip(),
                'route' => '/transaction.create',
                'data' => json_encode($data)
            ]);

            throw $e;
        }
    }
    private function storeTransactionImage($file)
    {
        return $file->store('/transaction_uploads', 'public');
    }

    public function update($id, array $data)
    {
        return $this->transactionRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->transactionRepository->delete($id);
    }
}
