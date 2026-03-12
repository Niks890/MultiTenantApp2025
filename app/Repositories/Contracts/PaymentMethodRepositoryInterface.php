<?php
namespace App\Repositories\Contracts;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Collection;


interface PaymentMethodRepositoryInterface
{
    public function all();
    public function find(string $id);
    public function findOrFail(string $id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function search(string $keyword, $perPage = null);
}
