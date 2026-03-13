<?php

namespace App\Services;

use App\Repositories\Contracts\ContractRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContractService
{
    protected $contractRepository;

    public function __construct(ContractRepositoryInterface $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function searchContract($keyword = '', $status = '', $tenantId = '', $planId = '', $perPage = null)
    {
        return $this->contractRepository->search($keyword, $status, $tenantId, $planId, $perPage);
    }


    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $contractDeleted = $this->contractRepository->delete($id);
            if (!$contractDeleted) {
                DB::rollBack();
                Log::channel('system_user')->error('Hợp đồng này không thể xoá!', [
                    'ip' => request()->ip(),
                    'route' => '/contract.destroy',
                    'data' => $id
                ]);
                return false;
            }
            DB::commit();
            Log::channel('system_user')->info('Xóa hợp đồng thành công', [
                'ip' => request()->ip(),
                'route' => '/contract.destroy',
                'data' => $id
            ]);
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('system_user')->error('Xóa hợp đồng thất bại' . $e->getMessage(), [
                'ip' => request()->ip(),
                'route' => '/contract.destroy',
                'data' => $id
            ]);
        }
    }


    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $contract = $this->contractRepository->create([
                'tenant_id' => $data['tenant_id'],
                'plan_id' => $data['plan_id'],
                'tax_amount' => $data['vat_price'],
                'amount_before_tax' => $data['plan_price'],
                'tax_id' => $data['tax_id'],
                'payment_mode' => $data['payment_mode'],
                'amount_after_tax' => $data['amount_after_tax'],
                'start_at' => $data['start_at'],
                'end_at' => $data['end_at'],
                'due_date' => $data['due_date'],
                'status' => 3, // Mặc định là còn nợ
            ]);
            DB::commit();
            Log::channel('system_user')->info('Tạo hợp đồng thành công', [
                'ip' => request()->ip(),
                'route' => '/contracts.create',
                'data' => json_encode($data)
            ]);
            return $contract;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('system_user')->error('Tạo hợp đồng thất bại' . $e->getMessage(), [
                'ip' => request()->ip(),
                'route' => '/contracts.create',
                'data' => json_encode($data)
            ]);
            throw $e;
        }
    }

    public function show($id)
    {
        return $this->contractRepository->findOrFail($id);
    }
}
