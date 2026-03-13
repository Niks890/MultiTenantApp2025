<?php

namespace App\Repositories\Eloquent;

use App\Models\Contract;
use App\Repositories\Contracts\ContractRepositoryInterface;

class ContractRepository implements ContractRepositoryInterface
{
    public function all()
    {
        return Contract::with(['tenant', 'plan', 'tax', 'transactions'])
            ->where('delete_flg', 0)
            // ->get();
            ->paginate(10);
    }

    public function find($id)
    {
        return Contract::with(['tenant', 'plan', 'tax', 'transactions'])->find($id);
    }

    public function findOrFail($id)
    {
        return Contract::with(['tenant', 'plan', 'tax', 'transactions'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Contract::create($data);
    }

    public function update($id, array $data)
    {
        return Contract::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return Contract::where('id', $id)->update(['delete_flg' => 1]);
    }

    public function search(
        string $keyword = '',
        string $status = '',
        string $tenantId = '',
        string $planId = '',
        $perPage = null
    ) {
        $perPage = request('paginate') ?? ($perPage ?? 10);
        $query = Contract::with(['tenant', 'plan', 'tax', 'transactions']);

        if ($status === '5') {
            $query->where('delete_flg', 1);
        } else {
            if (!empty($status)) {
                $query->where('status', $status);
            }
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('start_at', 'like', "%$keyword%")
                    ->orWhere('end_at', 'like', "%$keyword%")
                    ->orWhere('due_date', 'like', "%$keyword%");
            });
        }
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        if ($planId) {
            $query->where('plan_id', $planId);
        }
        $appends = [
            'paginate' => $perPage,
            'keyword' => $keyword,
            'status' => $status,
            'tenantId' => $tenantId,
            'planId' => $planId,
        ];
        if ($perPage === 'all') {
            $total = $query->count();
            $contracts = $query->orderByDesc('id')
                ->paginate($total)
                ->appends($appends);
            $contracts->per_page = $total;
            $selectedPaginate = 'all';
        } else {
            $contracts = $query->orderByDesc('id')
                ->paginate((int) $perPage)
                ->appends($appends);
            $selectedPaginate = $perPage;
        }

        return [
            'contracts' => $contracts,
            'selectedPaginate' => $selectedPaginate,
        ];
    }
}
