<?php


namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function all()
    {
        return Transaction::all();
    }

    public function find($id)
    {
        return Transaction::find($id);
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function update($id, array $data)
    {
        return Transaction::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete_flg = 1;
        $transaction->save();
        return $transaction;
    }
}
