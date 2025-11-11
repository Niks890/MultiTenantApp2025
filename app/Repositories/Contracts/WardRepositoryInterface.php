<?php

namespace App\Repositories\Contracts;

use App\Models\Ward;
use Illuminate\Database\Eloquent\Collection;

interface WardRepositoryInterface
{
    public function all(): Collection;

    public function find(string $id): ?Ward;

    public function findOrFail(string $id): Ward;

    public function getNameByCode(string $code): ?string;
}
