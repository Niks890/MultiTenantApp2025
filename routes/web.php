<?php

use App\Http\Controllers\AdminTenantController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\GroupTenantController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SystemUserController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;




foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        // Xử lý đăng nhập
        Route::get('/', [LoginController::class, 'login'])->name('admin.login');
        Route::post('/post-login', [LoginController::class, 'postLogin'])->name('admin.post_login');
        // Nhóm admin
        Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'checkActiveUser']], function () {
            // Trang dashboard
            Route::get('/', [DashboardAdminController::class, 'index'])->name('admin.index');

            // Xử lý đăng xuất
            Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

            Route::resource('admin-tenants', AdminTenantController::class)->parameters(['admin-tenants' => 'id']);
            Route::get('admin-tenants/{id}/tenants', [AdminTenantController::class, 'getTenantsForAdmin'])->name('admin-tenants.getTenants');
            // Controller Resources (CRUD)
            Route::resources([
                'system-user' => SystemUserController::class,
                'group' => GroupTenantController::class,
                'tenant' => TenantController::class,
                'plans' => PlanController::class,
                'taxes' => TaxController::class,
                'payment-methods' => PaymentMethodController::class,
                'contracts' => ContractController::class,
                'transaction' => TransactionController::class
            ]);
            Route::get('/tenant/by-admin/{adminId}', [TenantController::class, 'getTenantsByAdmin'])->name('tenant.by-admin');
            Route::patch('/plans/{plan}/status', [PlanController::class, 'updateStatus'])->name('plans.update_status');
            Route::patch('/tenant/{tenant}/status', [TenantController::class, 'updateTenantStatus'])->name('tenant.update_status');
            Route::patch('/taxes/{tax}/status', [TaxController::class, 'updateStatus'])->name('taxes.update_status');
            Route::post('/tenants/{id}/assign-admin', [TenantController::class, 'assignAdmin'])->name('tenant.assign-admin');


            Route::prefix('contracts')->group(function () {
                Route::get('/get-current-tax', [ContractController::class, 'getCurrentTax']);
            });
            Route::get('/tenant/detail/{tenantId}', [TenantController::class, 'getTenantDetail'])->name('tenant.detail');
        });
    });
}
