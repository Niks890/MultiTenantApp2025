<?php

namespace App\Services\Contracts;

use App\Models\Tax;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TaxServiceInterface
{
    public function all(): Collection;

    public function count(): int;

    public function countWithFilters(array $filters): int;

    public function find(string $id): ?Tax;

    public function findOrFail(string $id): Tax;

    public function create(array $data): Tax;

    public function update(string $id, array $data): Tax;

    public function delete(string $id): bool;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function paginateWithFilters(array $filters, int $perPage = 10): LengthAwarePaginator;

    public function updateStatus(string $id, bool $isActive = true): Tax;
}
