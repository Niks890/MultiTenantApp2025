<?php

namespace App\Repositories\Contracts;

use App\Models\AdminTenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AdminTenantRepositoryInterface
{
    public function all(): Collection;

    public function count(): int;

    public function countWithFilters(array $filters): int;

    public function find(string $id): ?AdminTenant;

    public function findOrFail(string $id): AdminTenant;

    public function create(array $data): AdminTenant;

    public function update(AdminTenant $adminTenant, array $data): AdminTenant;

    public function delete(AdminTenant $adminTenant): bool;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function paginateWithFilters(array $filters, int $perPage = 10): LengthAwarePaginator;

    public function getTenantsForAdmin(AdminTenant $adminTenant): Collection;
}
