<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignAdminRequest;
use App\Http\Requests\TenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Models\AdminTenant;
use App\Models\Tenant;
use App\Services\AdminTenantService;
use App\Services\GroupService;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TenantController extends Controller
{
    protected $tenantService, $groupService, $adminTenantService;
    public function __construct(TenantService $tenantService, GroupService $groupService, AdminTenantService $adminTenantService)
    {
        $this->tenantService = $tenantService;
        $this->groupService = $groupService;
        $this->adminTenantService = $adminTenantService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $keyword = $request->keyword ?? '';
            $status = $request->status ?? '';
            $groupId = $request->group_id ?? '';
            $adminTenantId = $request->admin_tenant_id ?? '';
            $paginate = $request->paginate ?? 10;
            $groups = $this->groupService->getAll();
            $adminTenants = AdminTenant::select('id', 'display_name', 'username')->where('delete_flg', 0)->get();

            $result = $this->tenantService->searchTenant($keyword, $status, $groupId, $adminTenantId, $paginate);
            $tenants = $result['tenants'];
            $selectedPaginate = $result['selectedPaginate'];

            if ($request->ajax()) {
                $table = view('admin.tenant.partials.tenant_table', compact('tenants', 'keyword', 'status', 'groupId', 'adminTenantId', 'selectedPaginate'))->render();
                $pagination = view('admin.tenant.partials.pagination', compact('tenants', 'selectedPaginate'))->render();
                return response()->json([
                    'table' => $table,
                    'pagination' => $pagination,
                    'selected_paginate' => $selectedPaginate
                ]);
            }
            return view('admin.tenant.index', compact('tenants', 'groups', 'adminTenants', 'keyword', 'status', 'groupId', 'adminTenantId', 'selectedPaginate'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Lấy danh sách thất bại: ' . $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => 'Lấy danh sách thất bại: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = $this->adminTenantService->create();
        $groups = $this->groupService->getAll();
        $adminTenants = AdminTenant::select('id', 'display_name', 'username')->where('delete_flg', 0)->get();
        return view('admin.tenant.create', compact('groups', 'adminTenants'), $provinces);
    }

    public function getTenantsByAdmin($adminId)
    {
        $tenants = $this->tenantService->getTenantByAdmin($adminId);
        if ($tenants->isEmpty()) {
            return response()->json(['success' => false]);
        }
        return response()->json([
            'success' => true,
            'data' => $tenants
        ]);
    }

    public function getTenantDetail($tenantId)
    {
        $tenant = $this->tenantService->getTenantDetails($tenantId);
        if (!$tenant) {
            return response()->json(['success' => false]);
        }
        $tenant->logo = $tenant->logo ? asset('storage/' . $tenant->logo) : null;
        return response()->json([
            'success' => true,
            'data' => $tenant
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TenantRequest $tenantRequest)
    {
        try {
            if ($this->tenantService->store($tenantRequest->all())) {
                session()->flash('success', __('successMessage'));
                return response()->json(['status' => true, 'message' => __('successMessage')]);
            }
            session()->flash('success', __('successErrorMessage'));
            return response()->json(['status' => false, 'message' => __('successErrorMessage')], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Đã có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tenant = $this->tenantService->showDetails($id);
        return view('admin.tenant.partials.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        if ($tenant->delete_flg == 1) {
            abort(404);
        }
        $tenant = $this->tenantService->edit($tenant->id);
        $groups = $this->groupService->getAll();
        $provinces = $this->adminTenantService->create();
        $adminTenants = AdminTenant::select('id', 'display_name', 'username')->where('delete_flg', 0)->get();
        return view('admin.tenant.edit', compact('tenant', 'groups', 'adminTenants'), $provinces);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTenantRequest $updateTenantRequest, Tenant $tenant)
    {
        try {
            if ($this->tenantService->update($tenant->id, $updateTenantRequest->all())) {
                session()->flash('success', __('successMessage'));
                return response()->json(['status' => true, 'message' => __('successMessage')]);
            }
            session()->flash('success', __('successErrorMessage'));
            return response()->json(['status' => false, 'message' => __('successErrorMessage')], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Đã có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        try {
            if ($this->tenantService->destroy($tenant)) {
                session()->flash('success', __('deleteMessage'));
                return response()->json([
                    'status' => true,
                    'message' => __('deleteMessage')
                ], 200);
            }
            session()->flash('error', __('deleteErrorMessage'));
            return response()->json(['status' => false, 'message' => __('deleteErrorMessage')], 422);
        } catch (\Exception $e) {
            Log::error('Lỗi xoá tenant: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateTenantStatus(Request $request, $tenant)
    {
        try {
            $validatedData = $request->validate([
                'tenant_status' => 'required|boolean',
            ]);
            $this->tenantService->updateTenantStatus($tenant, $validatedData['tenant_status']);
            return response()->json([
                'status' => true,
                'message' => __('successMessage'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('successErrorMessage'),
            ], 500);
        }
    }

    public function assignAdmin(AssignAdminRequest $request, $id)
    {
        try {
            $tenant = Tenant::findOrFail($id);
            if (!$tenant) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cửa hiệu không tồn tại',
                ], 404);
            }
            $admin = AdminTenant::find($request['admin_id']);
            if (!$admin) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tài khoản admin không tồn tại',
                ], 404);
            }
            $this->tenantService->assignAdminToTenant($tenant, $request['admin_id']);
            return response()->json([
                'status' => true,
                'message' => __('successMessage'),
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi assign admin: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => __('successErrorMessage'),
                'error' => config('app.debug') ? $e->getMessage() : 'Lỗi hệ thống'
            ], 500);
        }
    }
}
