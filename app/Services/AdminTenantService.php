<?php

namespace App\Services;

use App\Exceptions\CreateException;
use App\Exceptions\DeleteException;
use App\Exceptions\FetchException;
use App\Exceptions\UpdateException;
use App\Models\AdminTenant;
use App\Models\Province;
use App\Repositories\Contracts\ProvinceRepositoryInterface;
use App\Repositories\Contracts\WardRepositoryInterface;
use App\Repositories\Contracts\AdminTenantRepositoryInterface;
use App\Services\Contracts\AdminTenantServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Hash;

class AdminTenantService implements AdminTenantServiceInterface
{
    protected string $resourceName = 'Chủ cửa hiệu'; // name resource for log exception messages
    protected $adminTenantRepository;
    protected $wardRepository;
    protected $provinceRepository;

    public function __construct(AdminTenantRepositoryInterface $adminTenantRepository, WardRepositoryInterface $wardRepository, ProvinceRepositoryInterface $provinceRepository)
    {
        $this->adminTenantRepository = $adminTenantRepository;
        $this->wardRepository = $wardRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function all(): Collection
    {
        try {
            return $this->adminTenantRepository->all();
        } catch (\Exception $e) {
            throw new FetchException($this->resourceName, $e->getMessage());
        }
    }

    public function count(): int
    {
        return $this->adminTenantRepository->count();
    }

    public function countWithFilters(array $filters): int
    {
        return $this->adminTenantRepository->countWithFilters($filters);
    }

    public function find(string $id): ?AdminTenant
    {
        return $this->adminTenantRepository->find($id);
    }

    public function findOrFail(string $id): AdminTenant
    {
        return $this->adminTenantRepository->findOrFail($id);
    }

    public function store(array $data): AdminTenant
    {
        try {
            $modelData = $this->prepareModelData($data);
            return $this->adminTenantRepository->create($modelData);
        } catch (\Exception $e) {
            throw new CreateException($this->resourceName, $e->getMessage());
        }
    }

    public function update(string $id, array $data): AdminTenant
    {
        try {
            $adminTenant = $this->adminTenantRepository->findOrFail($id);
            $modelData = $this->prepareModelData($data, $adminTenant->password);
            return $this->adminTenantRepository->update($adminTenant, $modelData);
        } catch (\Exception $e) {
            throw new UpdateException($this->resourceName, $e->getMessage());
        }
    }

    public function destroy(string $id): bool
    {
        try {
            $adminTenant = $this->adminTenantRepository->findOrFail($id);
            return $this->adminTenantRepository->delete($adminTenant);
        } catch (\Exception $e) {
            throw new DeleteException($this->resourceName, $e->getMessage());
        }
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        try {
            return $this->adminTenantRepository->paginate($perPage);
        } catch (\Exception $e) {
            throw new FetchException($this->resourceName, $e->getMessage());
        }
    }

    public function paginateWithFilters(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        try {
            return $this->adminTenantRepository->paginateWithFilters($filters, $perPage);
        } catch (\Exception $e) {
            throw new FetchException($this->resourceName, $e->getMessage());
        }
    }

    public function getTenantsList(): SupportCollection
    {
        return $this->getCachedTenants();
    }

    public function getTenantsForAdmin(string $id): array
    {
        $adminTenant = $this->adminTenantRepository->findOrFail($id);
        $tenants = $this->adminTenantRepository->getTenantsForAdmin($adminTenant);
        $appUrl = config('app.url');
        $displayDomain = '.' . parse_url($appUrl, PHP_URL_HOST);

        return [
            'adminTenantName' => $adminTenant->display_name,
            'tenants' => $tenants,
            'displayDomain' => $displayDomain
        ];
    }

    public function create(): array
    {
        $provinces = cache()->remember(
            'provinces_with_wards',
            3600,
            function () {
                return $this->provinceRepository->getProvinceListWithWards();
            }
        );

        return [
            'provinces' => $provinces
        ];
    }

    public function edit(string $id): array
    {
        $adminTenant = $this->adminTenantRepository->findOrFail($id);
        $addressParts = $this->parseAddress($adminTenant['address'] ?? '');
        $dataForView = [
            'id'            => $adminTenant->id,
            'username'      => $adminTenant->username,
            'name'          => $adminTenant->display_name,
            'birthday'      => $adminTenant->date_of_birth?->format('Y-m-d'),
            'phone'         => $adminTenant->phone_number,
            'email'         => $adminTenant->email,
        ];

        $provinces = cache()->remember(
            'provinces_with_wards',
            3600,
            function () {
                return $this->provinceRepository->getProvinceListWithWards();
            }
        );

        return array_merge($dataForView, $addressParts, ['provinces' => $provinces]);
    }

    public function show(string $id): array
    {
        $adminTenant = $this->adminTenantRepository->findOrFail($id);

        return [
            'id' => $adminTenant->id,
            'username' => $adminTenant->username,
            'name' => $adminTenant->display_name,
            'birthday' => $adminTenant->date_of_birth?->format('d-m-Y'),
            'address' => $adminTenant->address,
            'email' => $adminTenant->email,
            'phone' => $adminTenant->phone_number,
            'tenants' => $adminTenant->tenants->pluck('name'),
        ];
    }

    // ===== PRIVATE HELPER METHODS =====
    private function getCachedTenants(): SupportCollection
    {
        return cache()->remember('tenants_list', 3600, function () {
            return \App\Models\Tenant::notDeleted()->pluck('name', 'id');
        });
    }

    private function prepareModelData(array $data, ?string $existingPassword = null): array
    {
        $fullAddress = $this->combineAddress(
            $data['province'] ?? null,
            $data['ward'] ?? null,
            $data['street'] ?? ''
        );

        $password = $existingPassword;
        if (!empty($data['password'])) {
            $password = Hash::make($data['password']);
        }

        return [
            'username' => $data['username'],
            'password' => $password,
            'display_name' => $data['name'],
            'date_of_birth' => $data['birthday'] ?? null,
            'address' => $fullAddress,
            'email' => $data['email'],
            'phone_number' => $data['phone'],
        ];
    }

    private function combineAddress(?string $provinceCode, ?string $wardCode, string $street): string
    {
        $wardName = $wardCode ? $this->wardRepository->getNameByCode($wardCode) : null;
        $provinceName = $provinceCode ? $this->provinceRepository->getNameByCode($provinceCode) : null;

        $parts = array_filter([$street, $wardName, $provinceName]);

        return implode(', ', $parts);
    }

    private function parseAddress(string $fullAddress): array
    {
        $result = [
            'province' => null,
            'ward' => null,
            'street' => $fullAddress
        ];

        if (empty($fullAddress)) {
            return $result;
        }

        $provinces = Province::with('wards:id,ward_code,name,province_id')->get();

        foreach ($provinces as $province) {
            if (stripos($fullAddress, $province->name) !== false) {
                $result['province'] = $province->province_code;

                $remainingAddress = $fullAddress;
                if (str_ends_with($remainingAddress, $province->name)) {
                    // Xóa tên tỉnh và dấu phẩy/khoảng trắng trước nó
                    $remainingAddress = trim(preg_replace('/,?\s*' . preg_quote($province->name, '/') . '$/i', '', $remainingAddress));
                }

                foreach ($province->wards as $ward) {
                    if (stripos($fullAddress, $ward->name) !== false) {
                        $result['ward'] = $ward->ward_code;

                        if (str_ends_with($remainingAddress, $ward->name)) {
                            $remainingAddress = trim(preg_replace('/,?\s*' . preg_quote($ward->name, '/') . '$/i', '', $remainingAddress));
                        }

                        $result['street'] = $remainingAddress;
                        break 2;
                    }
                }
                $result['street'] = $remainingAddress;
                break;
            }
        }

        return $result;
    }
}
