<?php

namespace App\Services\Contracts;

use App\Models\AdminTenant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

interface AdminTenantServiceInterface
{
    public function all(): Collection;

    public function count(): int;

    public function countWithFilters(array $filters): int;

    public function find(string $id): ?AdminTenant;

    public function findOrFail(string $id): AdminTenant;

    public function store(array $data): AdminTenant;

    public function update(string $id, array $data): AdminTenant;

    public function destroy(string $id): bool;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function paginateWithFilters(array $filters, int $perPage = 10): LengthAwarePaginator;

    public function getTenantsList(): SupportCollection;

    public function getTenantsForAdmin(string $id): array;

    public function create(): array;

    public function show(string $id): array;

    public function edit(string $id): array;
}
