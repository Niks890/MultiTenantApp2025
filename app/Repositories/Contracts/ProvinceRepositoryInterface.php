<?php

namespace App\Repositories\Contracts;

use App\Models\Province;
use Illuminate\Database\Eloquent\Collection;

interface ProvinceRepositoryInterface
{
    public function all(): Collection;

    public function find(string $id): ?Province;

    public function findOrFail(string $id): Province;

    public function getNameByCode(string $code): ?string;

    public function getProvinceListWithWards(): array;
}
