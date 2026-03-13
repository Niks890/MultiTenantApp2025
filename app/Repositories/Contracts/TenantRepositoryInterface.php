<?php

namespace App\Repositories\Contracts;

use App\Models\Tenant;

interface TenantRepositoryInterface
{
    public function search(
        string $keyword,
        string $status = '',
        string $groupId = '',
        string $adminTenantId = '',
        $perPage = null
    );
    public function all();
    public function create(array $data);
    public function createDomain(Tenant $tenant, array $data);
    public function edit($id);
    public function update($id, array $data);
    public function delete($id);
    public function deleteDomain($id);
    public function getTenantByAdmin($adminTenantId);
    public function getTenantDetails($tenantId);
    public function find($id);
    public function findDomain($id);
    public function getTenantLogo($tenantId);
    public function updateStatus($id, bool $isActive = true);
    public function getTenantWithAdmin();
}
