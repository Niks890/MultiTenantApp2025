<?php

namespace App\Services;

use App\Exceptions\CreateException;
use App\Exceptions\DeleteException;
use App\Exceptions\FetchException;
use App\Exceptions\UpdateException;
use App\Models\Plan;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Services\Contracts\PlanServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class PlanService implements PlanServiceInterface
{
    protected string $resourceName = 'Gói dịch vụ'; // name resource for log exception messages
    protected $planRepository;

    public function __construct(PlanRepositoryInterface $planRepository)
    {
        $this->planRepository = $planRepository;
    }

    public function all(): Collection
    {
        try {
            return $this->planRepository->all();
        } catch (\Exception $e) {
            throw new FetchException($this->resourceName);
        }
    }

    public function count(): int
    {
        return $this->planRepository->count();
    }

    public function countWithFilters(array $filters): int
    {
        return $this->planRepository->countWithFilters($filters);
    }

    public function find(string $id): ?Plan
    {
        return $this->planRepository->find($id);
    }

    public function findOrFail(string $id): Plan
    {
        return $this->planRepository->findOrFail($id);
    }

    public function create(array $data): Plan
    {
        try {
            $plan = $this->planRepository->create($data);
            return $plan;
        } catch (\Exception $e) {
            throw new CreateException($this->resourceName);
        }
    }

    public function update(string $id, array $data): Plan
    {
        try {
            $plan = $this->findOrFail($id);
            return $this->planRepository->update($plan, $data);
        } catch (\Exception $e) {
            throw new UpdateException($this->resourceName);
        }
    }

    public function delete(string $id): bool
    {
        try {
            $plan = $this->findOrFail($id);
            if (! Gate::allows('delete-plan', $plan)) {
                abort(403);
            }
            return $this->planRepository->delete($plan);
        } catch (\Exception $e) {
            throw new DeleteException($this->resourceName);
        }
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->planRepository->paginate($perPage);
    }

    public function paginateWithFilters(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->planRepository->paginateWithFilters($filters, $perPage);
    }

    public function updateStatus(string $id, bool $isActive = true): Plan
    {
        $plan = $this->findOrFail($id);
        return $this->planRepository->update($plan, ['is_active' => $isActive]);
    }
}
