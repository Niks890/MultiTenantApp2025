<?php

namespace App\Repositories\Eloquent;

use App\Models\Ward;
use App\Repositories\Contracts\WardRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WardRepository implements WardRepositoryInterface
{
    protected $model;

    public function __construct(Ward $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->get();
    }

    public function find(string $id): ?Ward
    {
        return $this->model->find($id);
    }

    public function findOrFail(string $id): Ward
    {
        return $this->model->findOrFail($id);
    }

    public function getNameByCode(string $code): ?string
    {
        return $this->model->where('ward_code', $code)->value('name');
    }
}
