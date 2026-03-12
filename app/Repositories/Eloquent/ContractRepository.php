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
        return Contract::find($id);
    }

    public function findOrFail($id)
    {
        return Contract::findOrFail($id);
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
        return Contract::where('id', $id)->delete();
    }

    public function search($keyword, $perPage = null)
    {
        return Contract::where('name', 'like', '%' . $keyword . '%')->paginate($perPage);
    }
}
