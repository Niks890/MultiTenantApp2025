<?php

namespace App\Repositories\Eloquent;

use App\Models\Domain;
use App\Models\Tenant;
use App\Repositories\Contracts\TenantRepositoryInterface;


class TenantRepository implements TenantRepositoryInterface
{
    public function create(array $data): Tenant
    {
        return Tenant::create($data);
    }

    public function createDomain(Tenant $tenant, array $data)
    {
        return $tenant->domains()->create($data);
    }

    public function search(
        string $keyword = '',
        string $status = '',
        string $groupId = '',
        string $adminTenantId = '',
        $perPage = null
    ) {
        $perPage = request('paginate') ?? ($perPage ?? 10);
        $query = Tenant::with(['group', 'adminTenant', 'domains']);
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhereHas('domains', function ($domainQuery) use ($keyword) {
                        $domainQuery->where('domain', 'like', "%{$keyword}%");
                    });
            });
        }
        if ($status === 'active') {
            $query->where('is_active', 1)->where('delete_flg', 0);
        } elseif ($status === 'inactive') {
            $query->where('is_active', 0)->where('delete_flg', 0);
        }elseif ($status === 'deleted') {
            $query->where('delete_flg', 1);
        }
        if (!empty($groupId)) {
            $query->where('group_id', $groupId);
        }
        if (!empty($adminTenantId)) {
            $query->where('admin_tenant_id', $adminTenantId);
        }
        $appends = [
            'paginate' => $perPage,
            'keyword' => $keyword,
            'status' => $status,
            'groupId' => $groupId,
            'adminTenantId' => $adminTenantId,
        ];
        if ($perPage === 'all') {
            $total = $query->count();
            $tenants = $query->orderByDesc('id')
                ->paginate($total)
                ->appends($appends);
            $tenants->per_page = $total;
            $selectedPaginate = 'all';
        } else {
            $tenants = $query->orderByDesc('id')
                ->paginate((int) $perPage)
                ->appends($appends);
            $selectedPaginate = $perPage;
        }

        return [
            'tenants' => $tenants,
            'selectedPaginate' => $selectedPaginate,
        ];
    }


    public function getTenantByAdmin($adminTenantId)
    {
        $tenants = Tenant::where('admin_tenant_id', $adminTenantId)
            ->select('id', 'name', 'site_name', 'address', 'facebook_url', 'tiktok_url', 'instagram_url', 'logo')
            ->get();
        return $tenants;
    }

    public function getTenantDetails($tenantId)
    {
        $tenant = Tenant::select('id', 'name', 'site_name', 'address', 'facebook_url', 'tiktok_url', 'instagram_url', 'logo')
            ->find($tenantId);
        if ($tenant && $tenant->address === null) {
            $tenant->address = '';
        }
        return $tenant;
    }

    public function getTenantLogo($tenantId)
    {
        $tenantLogo = Tenant::select('logo')
            ->find($tenantId);
        return $tenantLogo;
    }

    public function edit($id)
    {
        return Tenant::with('domains', 'group', 'adminTenant')->findOrFail($id);
    }

    public function update($id, array $data)
    {
        return Tenant::where('id', $id)->update($data);
    }

    public function find($id)
    {
        return Tenant::find($id);
    }

    public function findDomain($id)
    {
        return Domain::find($id);
    }

    public function delete($id)
    {
        $tenant = $this->find($id);
        $tenant->delete_flg = 1;
        $tenant->is_active = 0;
        return $tenant->save();
    }

    public function deleteDomain($id)
    {
        $domain = $this->findDomain($id);
        $domain->is_active = 0;
        $domain->delete_flg = 1;
        // $domain->domain = 'deleted'.$domain->id.'.'.$domain->domain;
        return $domain->save();
    }

    public function updateStatus($id, bool $isActive = true)
    {
        $tenant = $this->find($id);
        if (!$tenant) {
            throw new \Exception('Tenant không tồn tại.');
        }
        $tenant->is_active = $isActive;
        $tenant->save();
        return $tenant;
    }


}
