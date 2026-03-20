<?php

namespace App\Services;

use App\Models\Contract;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TransactionService
{
    protected $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    private function refreshContractStatus(Contract $contract)
    {
        $actualTotalPaid = DB::table('t_transactions')
            ->where('delete_flg', 0)
            ->where('contract_id', $contract->id)
            ->sum('amount');

        $contract->total_paid = $actualTotalPaid;
        if ($contract->total_paid >= $contract->amount_after_tax) {
            $contract->status = 1;
        } else {
            $contract->status = 3;
        }

        $contract->save();
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $contract = Contract::where('id', $data['contract_id'])->lockForUpdate()->firstOrFail();

            if ($contract->total_paid + $data['amount'] > $contract->amount_after_tax) {
                throw new \Exception('Số tiền thanh toán vượt quá tổng hợp đồng');
            }

            if (isset($data['file_path']) && $data['file_path'] instanceof \Illuminate\Http\UploadedFile) {
                $data['file_path'] = $this->storeTransactionImage($data['file_path']);
            }

            $transaction = $this->transactionRepository->create([
                'contract_id'       => $data['contract_id'],
                'payment_method_id' => $data['payment_method'],
                'amount'            => $data['amount'],
                'payment_date'      => $data['payment_date'],
                'file_path'         => $data['file_path'] ?? null,
            ]);
            $this->refreshContractStatus($contract);

            $this->logAction('Tạo giao dịch thành công', $data);
            return $transaction;
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $transaction = $this->transactionRepository->find($id);
            $contract = Contract::where('id', $transaction->contract_id)->lockForUpdate()->firstOrFail();
            $diff = $data['amount'] - $transaction->amount;
            if ($contract->total_paid + $diff > $contract->amount_after_tax) {
                throw new \Exception('Số tiền cập nhật vượt quá tổng hợp đồng');
            }

            if (isset($data['file_path']) && $data['file_path'] instanceof \Illuminate\Http\UploadedFile) {
                if ($transaction->file_path) Storage::disk('public')->delete($transaction->file_path);
                $data['file_path'] = $this->storeTransactionImage($data['file_path']);
            } elseif (isset($data['keep_file']) && $data['keep_file'] == '0') {
                if ($transaction->file_path) Storage::disk('public')->delete($transaction->file_path);
                $data['file_path'] = null;
            }

            $this->transactionRepository->update($id, [
                'payment_method_id' => $data['payment_method'],
                'amount'            => $data['amount'],
                'payment_date'      => $data['payment_date'],
                'file_path'         => $data['file_path'] ?? $transaction->file_path,
            ]);
            $this->refreshContractStatus($contract);

            $this->logAction('Cập nhật giao dịch thành công', $data);
            return true;
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $transaction = $this->transactionRepository->find($id);
            if (!$transaction) return false;
            $contract = Contract::where('id', $transaction->contract_id)->lockForUpdate()->firstOrFail();
            if ($contract->total_paid < $contract->amount_after_tax) {
                throw new \Exception('Không thể xóa giao dịch khi hợp đồng chưa được thanh toán đầy đủ.');
            }
            if ($transaction->file_path) {
                Storage::disk('public')->delete($transaction->file_path);
            }
            $this->transactionRepository->delete($id);
            $this->refreshContractStatus($contract);

            $this->logAction('Xóa giao dịch thành công', ['id' => $id]);
            return true;
        });
    }

    private function storeTransactionImage($file)
    {
        return $file->store('/transaction_uploads', 'public');
    }

    private function logAction($message, $data, $level = 'info')
    {
        Log::channel('system_user')->$level($message, [
            'ip' => request()->ip(),
            'data' => json_encode($data)
        ]);
    }
}
