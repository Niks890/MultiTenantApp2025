<?php

namespace App\Repositories\Eloquent;

use App\Models\AdminTenant;
use App\Repositories\Contracts\AdminTenantRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminTenantRepository implements AdminTenantRepositoryInterface
{
    protected $model;

    public function __construct(AdminTenant $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->notDeleted()->get();
    }

    public function count(): int
    {
        return $this->model->notDeleted()->count();
    }

    public function countWithFilters(array $filters): int
    {
        $query = $this->model->newQuery()->notDeleted();
        $query = $this->applyFiltersToQuery($query, $filters);
        return $query->count();
    }

    public function find(string $id): ?AdminTenant
    {
        return $this->model->notDeleted()->find($id);
    }

    public function findOrFail(string $id): AdminTenant
    {
        return $this->model->notDeleted()->findOrFail($id);
    }

    public function create(array $data): AdminTenant
    {
        return $this->model->create($data);
    }

    public function update(AdminTenant $adminTenant, array $data): AdminTenant
    {
        $adminTenant->update($data);
        return $adminTenant->fresh();
    }

    public function delete(AdminTenant $adminTenant): bool
    {
        return $adminTenant->markAsDeleted();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->notDeleted()->latest()->paginate($perPage);
    }

    public function paginateWithFilters(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->notDeleted();
        $query = $this->applyFiltersToQuery($query, $filters);
        return $query->latest()->paginate($perPage);
    }

    public function getTenantsForAdmin(AdminTenant $adminTenant): Collection
    {
        $tenants = $adminTenant->tenants()
            ->notDeleted()
            ->with([
                'group:id,name',
                'domains:id,domain,tenant_id'
            ])
            // ->select('id', 'name', 'logo', 'access_key', 'hash_code', 'db_name', 'db_host', 'db_port', 'created_at', 'group_id', 'is_active')
            ->select('id', 'name', 'logo', 'access_key', 'hash_code', 'tenancy_db_name', 'tenancy_db_host', 'tenancy_db_port', 'created_at', 'group_id', 'is_active')
            ->get();

        return $tenants;
    }

    protected function applyFiltersToQuery($query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if ($value === '' || $value === null) {
                continue;
            }

            switch ($key) {
                case 'search':
                    $query->search($value);
                    break;
                case 'tenant_id':
                    $query->byTenant($value);
                    break;
            }
        }
        return $query;
    }
}
