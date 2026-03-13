<?php

namespace App\Repositories\Contracts;

use App\Models\Tax;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TaxRepositoryInterface
{
    public function all(): Collection;

    public function count(): int;

    public function countWithFilters(array $filters): int;

    public function find(string $id): ?Tax;

    public function findOrFail(string $id): Tax;

    public function create(array $data): Tax;

    public function update(Tax $tax, array $data): Tax;

    public function delete(Tax $tax): bool;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function paginateWithFilters(array $filters, int $perPage = 10): LengthAwarePaginator;

    public function getCurrentTax();
}
