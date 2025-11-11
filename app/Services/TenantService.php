<?php

namespace App\Services;

use App\Models\AdminTenant;
use App\Models\Province;
use App\Models\Tenant;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Contracts\AdminTenantRepositoryInterface;
use App\Repositories\Contracts\ProvinceRepositoryInterface;
use App\Repositories\Contracts\WardRepositoryInterface;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TenantService
{

    protected $tenantRepository;
    protected $adminTenantRepository;
    protected $wardRepository;
    protected $provinceRepository;

    public function __construct(TenantRepositoryInterface $tenantRepository, AdminTenantRepositoryInterface $adminTenantRepository, WardRepositoryInterface $wardRepository, ProvinceRepositoryInterface $provinceRepository)
    {
        $this->tenantRepository = $tenantRepository;
        $this->adminTenantRepository = $adminTenantRepository;
        $this->wardRepository = $wardRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function searchTenant($keyword = '', $status = '', $groupId = '', $adminTenantId = '', $perPage = null)
    {
        return $this->tenantRepository->search($keyword, $status, $groupId, $adminTenantId, $perPage);
    }

    public function getTenantByAdmin($adminTenantId)
    {
        return $this->tenantRepository->getTenantByAdmin($adminTenantId);
    }

    public function getTenantDetails($tenantId)
    {
        $tenant = $this->tenantRepository->getTenantDetails($tenantId);

        if ($tenant) {
            $address = $tenant->address ?? '';
            $addressParts = $this->parseAddress($address);
            $tenant->province = $addressParts['province'];
            $tenant->ward = $addressParts['ward'];
            $tenant->street = $addressParts['street'];
        }

        return $tenant;
    }

    public function getTenantLogo($tenantId)
    {
        return $this->tenantRepository->getTenantLogo($tenantId);
    }

    public function store(array $data)
    {
        $logoPath = null;
        $tenant = null;
        $databaseCreated = false;
        $domainCreated = false;
        $plainPassword = $data['tenancy_db_password'];
        $fullAddress = $this->combineAddress(
            $data['province'] ?? null,
            $data['ward'] ?? null,
            $data['tenancy_address'] ?? ''
        );
        try {
            if (!empty($data['cropped_logo'])) {
                $logoPath = $this->saveTenantBase64($data['cropped_logo']);
            } elseif (isset($data['tenancy_logo']) && $data['tenancy_logo'] instanceof \Illuminate\Http\UploadedFile) {
                $logoPath = $this->storeTenantLogo($data['tenancy_logo']);
            } elseif (!empty($data['copy_from_tenant_id'])) {
                $sourceTenant = $this->tenantRepository->getTenantLogo($data['copy_from_tenant_id']);
                if ($sourceTenant && $sourceTenant->logo && Storage::disk('public')->exists($sourceTenant->logo)) {
                    $logoPath = $this->copyTenantLogo($sourceTenant->logo);
                }
            }
            $tenant = Tenant::create([
                'name' => $data['tenancy_name'],
                'site_name' => isset($data['tenancy_domain']) ? explode('.', $data['tenancy_domain'])[0] : null,
                'group_id' => $data['tenancy_group'] ?? null,
                'admin_tenant_id' => $data['tenancy_admin'] ?? null,
                'facebook_url' => $data['tenancy_fb_url'] ?? null,
                'tiktok_url' => $data['tenancy_tiktok_url'] ?? null,
                'instagram_url' => $data['tenancy_ig_url'] ?? null,
                'address' => $fullAddress ?? null,
                'logo' => $logoPath ?? null,
                'tenancy_db_name' => $data['tenancy_db_name'],
                'tenancy_db_host' => $data['tenancy_db_host'],
                'tenancy_db_connection' => $data['tenancy_db_connection'],
                'tenancy_db_port' => $data['tenancy_db_port'],
                'tenancy_db_username' => $data['tenancy_db_username'],
                'tenancy_db_password' => $data['tenancy_db_password'],
                'access_key' => $data['tenancy_access_key'],
                'hash_code' => $data['tenancy_hash_code'],
            ]);

            $tenant->domains()->create([
                'domain' => $data['tenancy_domain'],
                'tenant_id' => $tenant->id
            ]);
            $domainCreated = true;
            if (isset($data['tenancy_admin'])) {
                $adminTenantInfo = $this->adminTenantRepository->findOrFail($data['tenancy_admin']);
            }
            $this->provisionTenant($tenant, $plainPassword, $adminTenantInfo ?? null);
            $databaseCreated = true;
            Log::channel('system_user')->info('Tạo cửa hiệu thành công', [
                'ip' => request()->ip(),
                'route' => '/tenant.store',
                'tenant_id' => $tenant->id
            ]);

            $logger = app(\App\Logging\TenantLogger::class)([
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
            ]);
            $logger->info("Tạo cửa hiệu thành công {$tenant->id}");

            return $tenant;
        } catch (\Exception $e) {
            // rollback
            if ($databaseCreated && $tenant) {
                try {
                    $this->dropTenantDatabase($tenant->tenancy_db_name, $tenant->tenancy_db_username);
                } catch (\Exception $dbEx) {
                    Log::error("Không thể xóa database khi rollback: " . $dbEx->getMessage());
                }
            }

            if ($domainCreated && $tenant) {
                try {
                    $tenant->domains()->delete();
                } catch (\Exception $domEx) {
                    Log::error("Không thể xóa domain khi rollback: " . $domEx->getMessage());
                }
            }

            if ($tenant) {
                try {
                    $tenant->delete();
                } catch (\Exception $tenEx) {
                    Log::error("Không thể xóa tenant khi rollback: " . $tenEx->getMessage());
                }
            }

            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                try {
                    Storage::disk('public')->delete($logoPath);
                } catch (\Exception $fileEx) {
                    Log::error("Không thể xóa logo khi rollback: " . $fileEx->getMessage());
                }
            }

            Log::channel('system_user')->error('Tạo cửa hiệu thất bại', [
                'ip' => request()->ip(),
                'route' => '/tenant.store',
                'data' => $e->getMessage(),
            ]);

            throw $e;
        }
    }


    private function provisionTenant(Tenant $tenant, $plainPassword, $adminTenantInfo = null)
    {
        $this->createTenantDatabase($tenant, $plainPassword);
        $tenant->setAttribute('tenancy_db_password', $plainPassword);
        tenancy()->initialize($tenant);
        Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->id],
            '--force' => true,
        ]);
        if (isset($adminTenantInfo)) {
            DB::table('mise_accounts')->insert([
                'username' => $adminTenantInfo->username,
                'date_of_birth' => $adminTenantInfo->date_of_birth ?? null,
                'display_name' => $adminTenantInfo->display_name,
                'address' => $adminTenantInfo->address ?? null,
                'email' => $adminTenantInfo->email ?? null,
                'phone_number' => $adminTenantInfo->phone_number ?? null,
                'password' => $adminTenantInfo->password,
                'created_at' => now(),
                'updated_at' => now(),
                'delete_flg' => $adminTenantInfo->delete_flg,
            ]);
        }
        tenancy()->end();
    }


    private function createTenantDatabase(Tenant $tenant, $plainPassword)
    {
        $connectionConfig = config("database.connections.{$tenant->tenancy_db_connection}");
        $pdo = new \PDO(
            "mysql:host={$connectionConfig['host']};port={$connectionConfig['port']}",
            $connectionConfig['username'],
            $connectionConfig['password']
        );
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $safeDb = '`' . str_replace('`', '``', $tenant->tenancy_db_name) . '`';
        $pdo->exec("CREATE DATABASE IF NOT EXISTS {$safeDb} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $username = $tenant->tenancy_db_username;
        $password = $plainPassword;
        $hosts = ['%', 'localhost'];

        foreach ($hosts as $host) {
            $stmt = $pdo->query("SELECT COUNT(*) FROM mysql.user WHERE user = '{$username}' AND host = '{$host}'");
            $userExists = $stmt->fetchColumn() > 0;
            if (!$userExists) {
                $pdo->exec("CREATE USER `{$username}`@`{$host}` IDENTIFIED BY '{$password}'");
                Log::info("Đã tạo user mới: {$username}@{$host}");
            } else {
                Log::info("User đã tồn tại, cập nhật password: {$username}@{$host}");
                $pdo->exec("ALTER USER `{$username}`@`{$host}` IDENTIFIED BY '{$password}'");
            }
            $pdo->exec("GRANT ALL PRIVILEGES ON {$safeDb}.* TO `{$username}`@`{$host}`");
        }
        $pdo->exec("FLUSH PRIVILEGES");
        Log::info("Đã tạo/cập nhật database {$tenant->tenancy_db_name} và user {$username}");
    }

    private function dropTenantDatabase(string $databaseName, string $username)
    {
        try {
            $mainConfig = config('database.connections.mysql');
            $pdo = new \PDO(
                "mysql:host={$mainConfig['host']};port={$mainConfig['port']}",
                $mainConfig['username'],
                $mainConfig['password']
            );
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $safeDatabaseName = '`' . str_replace('`', '``', $databaseName) . '`';
            $pdo->exec("DROP DATABASE IF EXISTS {$safeDatabaseName}");
            if ($username) {
                $escapedUsername = $pdo->quote($username);
                $pdo->exec("DROP USER IF EXISTS {$escapedUsername}@'localhost'");
                $pdo->exec("DROP USER IF EXISTS {$escapedUsername}@'%'");
            }
            Log::info("Đã xoá database: {$databaseName}");
        } catch (\Exception $e) {
            Log::error("Lỗi xoá database: " . $e->getMessage());
        }
    }

    public function edit($tenantId)
    {
        $tenant = $this->tenantRepository->edit($tenantId);
        $addressParts = $this->parseAddress($tenant->address);
        $tenant->province = $addressParts['province'];
        $tenant->ward = $addressParts['ward'];
        $tenant->street = $addressParts['street'];
        return $tenant;
    }

    public function update($tenantId, array $data)
    {
        DB::beginTransaction();
        $oldLogoPath = null;
        $newLogoPath = null;
        try {
            $tenant = Tenant::with('domains')->findOrFail($tenantId);
            $oldLogoPath = $tenant->logo;
            $oldAdminId = $tenant->admin_tenant_id;
            $data = collect($data)->toArray();
            $map = [
                'tenancy_name' => 'name',
                'tenancy_group' => 'group_id',
                'tenancy_admin' => 'admin_tenant_id',
                'tenancy_fb_url' => 'facebook_url',
                'tenancy_tiktok_url' => 'tiktok_url',
                'tenancy_ig_url' => 'instagram_url',
                'tenancy_address' => 'address' ?? null,
                'tenancy_db_name' => 'tenancy_db_name',
                'tenancy_db_host' => 'tenancy_db_host',
                'tenancy_db_port' => 'tenancy_db_port',
                'tenancy_db_connection' => 'tenancy_db_connection',
                'tenancy_db_username' => 'tenancy_db_username',
                'tenancy_is_active' => 'is_active',
            ];

            $fullAddress = $this->combineAddress(
                $data['province'] ?? null,
                $data['ward'] ?? null,
                $data['tenancy_address'] ?? ''
            );
            $updateData = [];
            foreach ($map as $input => $column) {
                if (array_key_exists($input, $data)) {
                    $updateData[$column] = $data[$input] !== '' ? $data[$input] : null;
                }
            }

            $updateData['address'] = $fullAddress;

            $plainPassword = $data['tenancy_db_password'] ?? null;

            if (isset($data['tenancy_db_password'])) {
                $updateData['tenancy_db_password'] = $data['tenancy_db_password'];
            }

            if (isset($data['tenancy_access_key'])) {
                $updateData['access_key'] = $data['tenancy_access_key'];
            }

            if (isset($data['tenancy_hash_code'])) {
                $updateData['hash_code'] = $data['tenancy_hash_code'];
            }

            if (!empty($data['cropped_logo'])) {
                $newLogoPath = $this->saveTenantBase64($data['cropped_logo']);
                $updateData['logo'] = $newLogoPath;
            } elseif (isset($data['tenancy_logo']) && $data['tenancy_logo'] instanceof \Illuminate\Http\UploadedFile) {
                $newLogoPath = $this->storeTenantLogo($data['tenancy_logo']);
                $updateData['logo'] = $newLogoPath;
            } elseif (!empty($data['copy_from_tenant_id'])) {
                $sourceTenant = $this->tenantRepository->getTenantLogo($data['copy_from_tenant_id']);
                if ($sourceTenant && $sourceTenant->logo && Storage::disk('public')->exists($sourceTenant->logo)) {
                    $newLogoPath = $this->copyTenantLogo($sourceTenant->logo);
                    $updateData['logo'] = $newLogoPath;
                }
            }
            if (isset($data['tenancy_domain'])) {
                $tenant->domains()->updateOrCreate(
                    ['tenant_id' => $tenant->id],
                    ['domain' => $data['tenancy_domain']],
                );
                $updateData['site_name'] = explode('.', $data['tenancy_domain'])[0];
            }
            if (isset($data['tenancy_is_active'])) {
                $isActive = $data['tenancy_is_active'];
                if ($isActive == false || $isActive === '0') {
                    $this->handleMaintenanceMode($tenant);
                } else {
                    $this->handleRestoreMode($tenant);
                }
                $updateData['is_active'] = (bool)$isActive;
            }


            if (!empty($updateData)) {
                try {
                    $mainConfig = config("database.connections.{$tenant->tenancy_db_connection}");
                    $pdo = new \PDO(
                        "mysql:host={$mainConfig['host']};port={$mainConfig['port']}",
                        $mainConfig['username'],
                        $mainConfig['password']
                    );
                    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                    $oldUsername = $tenant->getOriginal('tenancy_db_username');
                    $oldDbName = $tenant->getOriginal('tenancy_db_name');

                    $newUsername = $data['tenancy_db_username'] ?? $oldUsername;
                    $newDbName = $data['tenancy_db_name'] ?? $oldDbName;

                    if ($newDbName !== $oldDbName) {
                        $safeOldDb = '`' . str_replace('`', '``', $oldDbName) . '`';
                        $safeNewDb = '`' . str_replace('`', '``', $newDbName) . '`';

                        $pdo->exec("CREATE DATABASE IF NOT EXISTS {$safeNewDb} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

                        $tables = $pdo->query("SHOW TABLES FROM {$safeOldDb}")->fetchAll(\PDO::FETCH_COLUMN);

                        foreach ($tables as $table) {
                            $safeTable = '`' . str_replace('`', '``', $table) . '`';
                            $pdo->exec("RENAME TABLE {$safeOldDb}.{$safeTable} TO {$safeNewDb}.{$safeTable}");
                        }

                        $pdo->exec("DROP DATABASE IF EXISTS {$safeOldDb}");

                        Log::info("Đã rename database từ {$oldDbName} sang {$newDbName}");
                    }
                    $hosts = ['%', 'localhost'];
                    foreach ($hosts as $host) {
                        $stmtOld = $pdo->query("SELECT COUNT(*) FROM mysql.user WHERE user = '{$oldUsername}' AND host = '{$host}'");
                        $oldUserExists = $stmtOld->fetchColumn() > 0;
                        $stmtNew = $pdo->query("SELECT COUNT(*) FROM mysql.user WHERE user = '{$newUsername}' AND host = '{$host}'");
                        $newUserExists = $stmtNew->fetchColumn() > 0;

                        if ($newUsername !== $oldUsername) {
                            if ($newUserExists) {
                                if ($oldUserExists) {
                                    $pdo->exec("DROP USER IF EXISTS `{$oldUsername}`@'{$host}'");
                                    Log::info("Đã xóa user cũ {$oldUsername}@{$host}");
                                }
                            } else {
                                if ($oldUserExists) {
                                    $pdo->exec("RENAME USER `{$oldUsername}`@'{$host}' TO `{$newUsername}`@'{$host}'");
                                    Log::info("Đã rename user từ {$oldUsername}@{$host} sang {$newUsername}@{$host}");
                                } else {
                                    if ($plainPassword) {
                                        $pdo->exec("CREATE USER `{$newUsername}`@'{$host}' IDENTIFIED BY '{$plainPassword}'");
                                        Log::info("Đã tạo user mới {$newUsername}@{$host}");
                                    } else {
                                        if (!$newUserExists) {
                                            throw new \Exception("Không thể tạo user {$newUsername}@{$host} vì thiếu password");
                                        }
                                    }
                                }
                            }
                        } else {
                            if (!$oldUserExists) {
                                if ($plainPassword) {
                                    $pdo->exec("CREATE USER `{$newUsername}`@'{$host}' IDENTIFIED BY '{$plainPassword}'");
                                    Log::info("Đã tạo user mới {$newUsername}@{$host}");
                                } else {
                                    throw new \Exception("Không thể tạo user {$newUsername}@{$host} vì thiếu password");
                                }
                            }
                        }
                        if ($plainPassword) {
                            $stmtCheck = $pdo->query("SELECT COUNT(*) FROM mysql.user WHERE user = '{$newUsername}' AND host = '{$host}'");
                            $userExistsNow = $stmtCheck->fetchColumn() > 0;

                            if ($userExistsNow) {
                                $pdo->exec("ALTER USER `{$newUsername}`@'{$host}' IDENTIFIED BY '{$plainPassword}'");
                                Log::info("Đã cập nhật password cho user {$newUsername}@{$host}");
                            }
                        }

                        $safeDb = '`' . str_replace('`', '``', $newDbName) . '`';
                        $stmt = $pdo->query("SELECT COUNT(*) FROM mysql.user WHERE user = '{$newUsername}' AND host = '{$host}'");
                        $userExists = $stmt->fetchColumn() > 0;

                        if (!$userExists) {
                            throw new \Exception("User {$newUsername}@{$host} không tồn tại để grant quyền");
                        }
                        if ($newDbName !== $oldDbName) {
                            $safeOldDb = '`' . str_replace('`', '``', $oldDbName) . '`';
                            try {
                                $pdo->exec("REVOKE ALL PRIVILEGES ON {$safeOldDb}.* FROM `{$newUsername}`@'{$host}'");
                            } catch (\Exception $e) {
                                Log::warning("Không thể revoke privileges từ database cũ: " . $e->getMessage());
                            }
                        }
                        $pdo->exec("GRANT ALL PRIVILEGES ON {$safeDb}.* TO `{$newUsername}`@'{$host}'");
                    }
                    $pdo->exec("FLUSH PRIVILEGES");
                    Log::info("Đã cập nhật user DB và quyền cho tenant ID {$tenant->id}");
                } catch (\Exception $dbEx) {
                    DB::rollBack();
                    Log::error("Lỗi khi cập nhật database thực tế: " . $dbEx->getMessage());
                    throw new \Exception("Lỗi cập nhật database: " . $dbEx->getMessage());
                }
                $tenant->update($updateData);
            }
            if (!empty($data['tenancy_admin']) && $data['tenancy_admin'] != $oldAdminId) {
                $newAdmin = $this->adminTenantRepository->findOrFail($data['tenancy_admin']);
                tenancy()->initialize($tenant);
                DB::table('mise_accounts')->truncate();
                DB::table('mise_accounts')->insert([
                    'username' => $newAdmin->username,
                    'display_name' => $newAdmin->display_name,
                    'email' => $newAdmin->email ?? null,
                    'phone_number' => $newAdmin->phone_number ?? null,
                    'date_of_birth' => $newAdmin->date_of_birth ?? null,
                    'address' => $newAdmin->address ?? null,
                    'password' => $newAdmin->password,
                    'delete_flg' => $newAdmin->delete_flg ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                tenancy()->end();
                Log::info("Đã cập nhật chủ cửa hiệu mới cho tenant {$tenant->id}");
            }
            DB::commit();
            if ($newLogoPath && $oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
                Storage::disk('public')->delete($oldLogoPath);
            }
            Log::channel('system_user')->info('Cập nhật cửa hiệu thành công', [
                'ip' => request()->ip(),
                'route' => '/tenant.update',
                'tenant_id' => $tenant->id
            ]);
            $logger = app(\App\Logging\TenantLogger::class)([
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
            ]);

            $logger->info("Cập nhật cửa hiệu thành công {$tenant->id}");
            return $tenant;
        } catch (\Exception $e) {
            DB::rollBack();
            if ($newLogoPath && Storage::disk('public')->exists($newLogoPath)) {
                Storage::disk('public')->delete($newLogoPath);
            }
            Log::channel('system_user')->error('Cập nhật cửa hiệu thất bại', [
                'ip' => request()->ip(),
                'route' => '/tenant.update',
                'data' => $e->getMessage(),
            ]);
            $logger = app(\App\Logging\TenantLogger::class)([
                'tenant_id' => $tenantId,
                'tenant_name' => $tenant->name ?? 'N/A',
            ]);
            $logger->info("Cập nhật cửa hiệu thất bại {$tenantId}" . $e->getMessage());
            throw $e;
        }
    }

    public function showDetails(string $id): array
    {
        $tenant = $this->tenantRepository->edit($id);
        return [
            'id' => $tenant->id,
            'name' => $tenant->name,
            'site_name' => $tenant->site_name,
            'address' => $tenant->address,
            'facebook_url' => $tenant->facebook_url,
            'tiktok_url' => $tenant->tiktok_url,
            'instagram_url' => $tenant->instagram_url,
            'logo' => $tenant->logo,
            'is_active' => $tenant->is_active,
            'created_at' => $tenant->created_at,
            'tenancy_db_name' => $tenant->tenancy_db_name,
            'tenancy_db_host' => $tenant->tenancy_db_host,
            'tenancy_db_port' => $tenant->tenancy_db_port,
            'tenancy_db_connection' => $tenant->tenancy_db_connection,
            'tenancy_db_username' => $tenant->tenancy_db_username,
            'tenancy_db_password' => $tenant->tenancy_db_password,
            'tenancy_access_key' => $tenant->access_key,
            'tenancy_hash_code' => $tenant->hash_code,
            'tenancy_domain' => $tenant->domains->first()?->domain,
            'tenancy_admin_id' => $tenant->adminTenant->id ?? null,
            'tenancy_admin' => $tenant->adminTenant->display_name ?? null,
            'tenancy_admin_username' => $tenant->adminTenant->username ?? null,
            'tenancy_group' => $tenant->group->name ?? null,
            'delete_flg' => $tenant->delete_flg
        ];
    }

    public function destroy(Tenant $tenant)
    {
        try {
            DB::beginTransaction();

            $domain = $tenant->domains()->first();
            if (!$domain) {
                throw new \Exception("Không tìm thấy domain của tenant ID {$tenant->id}");
            }
            $domainDeleted = $this->tenantRepository->deleteDomain($domain->id);
            $tenantDeleted = $this->tenantRepository->delete($tenant->id);
            if ($tenantDeleted && $domainDeleted) {
                DB::commit();
                Log::channel('system_user')->info('Xóa cửa hiệu thành công', [
                    'ip' => request()->ip(),
                    'route' => '/tenant.destroy',
                    'data' => $tenant->id
                ]);
                return true;
            }
            DB::rollBack();
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('system_user')->error('Xóa cửa hiệu thất bại', [
                'ip' => request()->ip(),
                'route' => '/tenant.destroy',
                'data' => $e->getMessage(),
            ]);
        }
    }

    public function updateTenantStatus(int $tenantId, bool $isActive)
    {
        DB::beginTransaction();
        try {
            $tenant = $this->tenantRepository->updateStatus($tenantId, $isActive);
            if ($isActive === false) {
                $this->handleMaintenanceMode($tenant);
            } else {
                $this->handleRestoreMode($tenant);
            }
            Log::channel('system_user')->info('Cập nhật trạng thái cửa hiệu thành công', [
                'ip' => request()->ip(),
                'route' => '/tenant.update_status',
                'data' => $tenantId
            ]);
            DB::commit();
            return $tenant;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::channel('system_error')->error('Cập nhật trạng thái cửa hiệu thất bại', [
                'ip' => request()->ip(),
                'route' => '/tenant.update_status',
                'data' => $e->getMessage(),
            ]);
        }
    }



    protected function handleMaintenanceMode($tenant): void
    {
        $tenant->putDownForMaintenance([
            'message' => 'Cửa hiệu đang bảo trì',
            'retry' => null,
        ]);
        $tenant->domains()->update(['is_active' => false]);
    }

    protected function handleRestoreMode($tenant): void
    {
        $tenant->update(['maintenance_mode' => null]);
        $tenant->domains()->update(['is_active' => true]);
    }
    private function storeTenantLogo($file)
    {
        return $file->store('/logo_uploads', 'public');
    }

    private function copyTenantLogo($sourcePath)
    {
        $ext = pathinfo($sourcePath, PATHINFO_EXTENSION);
        $newPath = 'logo_uploads/' . uniqid('logo_') . '.' . $ext;
        Storage::disk('public')->copy($sourcePath, $newPath);
        return $newPath;
    }


    private function saveTenantBase64($base64)
    {
        $image = str_replace('data:image/png;base64,', '', $base64);
        $image = base64_decode($image);
        $path = 'logo_uploads/' . uniqid('logo_') . '.png';
        Storage::disk('public')->put($path, $image);
        return $path;
    }

    private function parseAddress(?string $fullAddress): array
    {
        $result = [
            'province' => null,
            'ward' => null,
            'street' => $fullAddress ?? ''
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

    private function combineAddress(?string $provinceCode, ?string $wardCode, string $street): string
    {
        $wardName = $wardCode ? $this->wardRepository->getNameByCode($wardCode) : null;
        $provinceName = $provinceCode ? $this->provinceRepository->getNameByCode($provinceCode) : null;

        $parts = array_filter([$street, $wardName, $provinceName]);

        return implode(', ', $parts);
    }
}
