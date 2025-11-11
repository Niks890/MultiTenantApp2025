<?php

namespace App\Repositories\Eloquent;

use App\Models\Plan;
use App\Repositories\Contracts\PlanRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PlanRepository implements PlanRepositoryInterface
{
    protected $model;

    public function __construct(Plan $model)
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

    public function find(string $id): ?Plan
    {
        return $this->model->notDeleted()->find($id);
    }

    public function findOrFail(string $id): Plan
    {
        return $this->model->notDeleted()->findOrFail($id);
    }

    public function create(array $data): Plan
    {
        return $this->model->create($data);
    }

    public function update(Plan $plan, array $data): Plan
    {
        $plan->update($data);
        return $plan->fresh();
    }

    public function delete(Plan $plan): bool
    {
        return $plan->markAsDeleted();
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

    protected function applyFiltersToQuery($query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if ($value === '' || $value === null) {
                continue;
            }

            switch ($key) {
                case 'search':
                    $query->where(function ($q) use ($value) {
                        $q->where('name', 'like', '%' . $value . '%')
                            ->orWhere('price', 'like', '%' . $value . '%')
                            ->orWhere('description', 'like', '%' . $value . '%');
                    });
                    break;
                case 'status':
                    $query->where('is_active', filter_var($value, FILTER_VALIDATE_BOOLEAN));
                    break;
                case 'cycle':
                    $query->where('cycle', $value);
                    break;
            }
        }
        return $query;
    }
}
