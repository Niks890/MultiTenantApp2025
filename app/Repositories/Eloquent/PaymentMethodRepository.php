<?php

namespace App\Repositories\Eloquent;

use App\Models\PaymentMethod;
use App\Repositories\Contracts\PaymentMethodRepositoryInterface;

class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{

    public function all()
    {
        return PaymentMethod::select('id', 'name')->where('delete_flg', 0)->get();
    }

    public function find($id)
    {
        return PaymentMethod::find($id);
    }



    public function findOrFail($id)
    {
        return PaymentMethod::findOrFail($id);
    }

    public function create(array $data)
    {
        return PaymentMethod::create($data);
    }

    public function update($id, array $data)
    {
        return PaymentMethod::where('id', $id)->update($data);
    }


    public function delete($id)
    {
        $paymentMethod = $this->find($id);
        $paymentMethod->delete_flg = 1;
        return $paymentMethod->save();
    }

    public function search(string $keyword, $perPage = null)
    {
        $perPage = request('paginate') ?? 10;
        $query = PaymentMethod::where('delete_flg', 0);

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            });
        }
        $appends = [
            'paginate' => $perPage,
            'keyword' => $keyword,
        ];
        if ($perPage === 'all') {
            $total = $query->count();
            $paymentMethods = $query->orderByDesc('id')
                ->paginate($total)
                ->appends($appends);
            $paymentMethods->per_page = $total;
            $selectedPaginate = 'all';
        } else {
            $paymentMethods = $query->orderByDesc('id')
                ->paginate((int)$perPage)
                ->appends($appends);
            $selectedPaginate = $perPage;
        }
        return [
            'paymentMethods' => $paymentMethods,
            'selectedPaginate' => $selectedPaginate,
        ];
    }
}
