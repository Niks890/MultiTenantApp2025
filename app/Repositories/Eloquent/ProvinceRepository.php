<?php

namespace App\Repositories\Eloquent;

use App\Models\Province;
use App\Repositories\Contracts\ProvinceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProvinceRepository implements ProvinceRepositoryInterface
{
    protected $model;

    public function __construct(Province $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->get();
    }

    public function find(string $id): ?Province
    {
        return $this->model->find($id);
    }

    public function findOrFail(string $id): Province
    {
        return $this->model->findOrFail($id);
    }

    public function getNameByCode(string $code): ?string
    {
        return $this->model->where('province_code', $code)->value('name');
    }

    public function getProvinceListWithWards(): array
    {
        return $this->model->select('id', 'province_code', 'name')
            ->with('wards:id,ward_code,name,province_id')
            ->orderBy('name')
            ->get()
            ->map(fn($province) => [
                'province_code' => $province->province_code,
                'name' => $province->name,
                'wards' => $province->wards->map(fn($ward) => [
                    'ward_code' => $ward->ward_code,
                    'name' => $ward->name
                ])->toArray()
            ])->toArray();
    }
}
