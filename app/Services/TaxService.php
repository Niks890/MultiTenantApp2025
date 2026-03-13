<?php

namespace App\Services;

use App\Exceptions\CreateException;
use App\Exceptions\DeleteException;
use App\Exceptions\FetchException;
use App\Exceptions\UpdateException;
use App\Models\Tax;
use App\Repositories\Contracts\TaxRepositoryInterface;
use App\Services\Contracts\TaxServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TaxService implements TaxServiceInterface
{
    protected string $resourceName = 'Thuế'; // name resource for log exception messages
    protected $taxRepository;

    public function __construct(TaxRepositoryInterface $taxRepository)
    {
        $this->taxRepository = $taxRepository;
    }

    public function all(): Collection
    {
        try {
            return $this->taxRepository->all();
        } catch (\Exception $e) {
            throw new FetchException($this->resourceName);
        }
    }

    public function getCurrentTax()
    {
        try {
            return $this->taxRepository->getCurrentTax();
        } catch (\Exception $e) {
            throw new FetchException($this->resourceName);
        }
    }

    public function count(): int
    {
        return $this->taxRepository->count();
    }

    public function countWithFilters(array $filters): int
    {
        return $this->taxRepository->countWithFilters($filters);
    }

    public function find(string $id): ?Tax
    {
        return $this->taxRepository->find($id);
    }

    public function findOrFail(string $id): Tax
    {
        return $this->taxRepository->findOrFail($id);
    }

    public function create(array $data): Tax
    {
        try {
            $plan = $this->taxRepository->create($data);
            return $plan;
        } catch (\Exception $e) {
            throw new CreateException($this->resourceName);
        }
    }

    public function update(string $id, array $data): Tax
    {
        try {
            $plan = $this->findOrFail($id);
            return $this->taxRepository->update($plan, $data);
        } catch (\Exception $e) {
            throw new UpdateException($this->resourceName);
        }
    }

    public function delete(string $id): bool
    {
        try {
            $plan = $this->findOrFail($id);
            return $this->taxRepository->delete($plan);
        } catch (\Exception $e) {
            throw new DeleteException($this->resourceName);
        }
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->taxRepository->paginate($perPage);
    }

    public function paginateWithFilters(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->taxRepository->paginateWithFilters($filters, $perPage);
    }

    public function updateStatus(string $id, bool $isActive = true): Tax
    {
        $tax = $this->findOrFail($id);
        return $this->taxRepository->update($tax, ['is_active' => $isActive]);
    }
}
