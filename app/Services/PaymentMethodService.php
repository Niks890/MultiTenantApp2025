<?php

namespace App\Services;

use App\Repositories\Contracts\PaymentMethodRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentMethodService
{
    protected $paymentMethodRepository;
    public function __construct(PaymentMethodRepositoryInterface $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }
    public function all()
    {
        return $this->paymentMethodRepository->all();
    }


    public function edit($id)
    {
        return $this->paymentMethodRepository->find($id);
    }

    public function update(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $paymentMethod = $this->paymentMethodRepository->update($id, [
                'name' => $data['payment_method_name'],
            ]);
            DB::commit();
            Log::channel('system_user')->info('Sửa thông tin phương thức thanh toán thành công', [
                'ip' => request()->ip(),
                'route' => '/payment-methods.update',
                'data' => json_encode($data)
            ]);
            return $paymentMethod;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('system_user')->error('Sửa thông tin phương thức thanh toán thất bại' . $e->getMessage(), [
                'ip' => request()->ip(),
                'route' => '/payment-methods.update',
                'data' => json_encode($data)
            ]);
            throw $e;
        }
    }



    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $paymentMethod = $this->paymentMethodRepository->create([
                'name' => $data['payment_method_name'],
            ]);
            DB::commit();
            Log::channel('system_user')->info('Thêm mới phương thức thanh toán thành công', [
                'ip' => request()->ip(),
                'route' => '/payment-methods.store',
                'data' => json_encode($paymentMethod->toArray())
            ]);
            return $paymentMethod;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('system_user')->error('Tạo phương thức thanh toán thất bại' . $e->getMessage(), [
                'ip' => request()->ip(),
                'route' => '/payment-methods.store',
                'data' => json_encode($data)
            ]);
            throw $e;
        }
    }

    public function searchPaymentMethod(string $keyword, $paginate = null)
    {
        $perPage = $paginate ?? request('paginate') ?? 10;
        return $this->paymentMethodRepository->search($keyword, $perPage);
    }


    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $paymentMethodDeleted = $this->paymentMethodRepository->delete($id);
            if (!$paymentMethodDeleted) {
                DB::rollBack();
                Log::channel('system_user')->error('Phương thức thanh toán đang hoạt động, không thể xoá!', [
                    'ip' => request()->ip(),
                    'route' => '/payment-methods.destroy',
                    'data' => $id
                ]);
                return false;
            }
            DB::commit();
            Log::channel('system_user')->info('Xóa phương thức thanh toán thành công', [
                'ip' => request()->ip(),
                'route' => '/payment-methods.destroy',
                'data' => $id
            ]);
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('system_user')->error('Xóa phương thức thanh toán thất bại' . $e->getMessage(), [
                'ip' => request()->ip(),
                'route' => '/payment-methods.destroy',
                'data' => $id
            ]);
        }
    }
}
