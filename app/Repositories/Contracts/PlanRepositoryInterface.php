<?php

namespace App\Repositories\Contracts;

use App\Models\Plan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PlanRepositoryInterface
{
    public function all(): Collection;

    public function count(): int;

    public function countWithFilters(array $filters): int;

    public function find(string $id): ?Plan;

    public function findOrFail(string $id): Plan;

    public function create(array $data): Plan;

    public function update(Plan $plan, array $data): Plan;

    public function delete(Plan $plan): bool;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function paginateWithFilters(array $filters, int $perPage = 10): LengthAwarePaginator;

    public function getPlanActive(): Collection;
}
