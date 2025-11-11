<?php

use App\Http\Controllers\Api\Validate\ValidationAdminTenantController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'validation-admin-tenant'], function () {
        Route::post('/check-username', [ValidationAdminTenantController::class, 'checkUsername']);
        Route::post('/check-email', [ValidationAdminTenantController::class, 'checkEmail']);
        Route::post('/check-phone', [ValidationAdminTenantController::class, 'checkPhone']);
    });
});
