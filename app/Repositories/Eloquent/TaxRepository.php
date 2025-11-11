<?php

namespace App\Repositories\Eloquent;

use App\Models\Tax;
use App\Repositories\Contracts\TaxRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TaxRepository implements TaxRepositoryInterface
{
    protected $model;

    public function __construct(Tax $model)
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

    public function find(string $id): ?Tax
    {
        return $this->model->notDeleted()->find($id);
    }

    public function findOrFail(string $id): Tax
    {
        return $this->model->notDeleted()->findOrFail($id);
    }

    public function create(array $data): Tax
    {
        return $this->model->create($data);
    }

    public function update(Tax $tax, array $data): Tax
    {
        $tax->update($data);
        return $tax->fresh();
    }

    public function delete(Tax $tax): bool
    {
        return $tax->markAsDeleted();
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
                    $query->where('rate', 'like', '%' . $value . '%');
                    break;
                case 'status':
                    $query->where('is_active', filter_var($value, FILTER_VALIDATE_BOOLEAN));
                    break;
            }
        }
        return $query;
    }
}
