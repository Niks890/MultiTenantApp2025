<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminTenantRequest;
use App\Services\Contracts\AdminTenantServiceInterface;
use App\Traits\FormatsAjaxResponses;
use Illuminate\Http\Request;

class AdminTenantController extends Controller
{
    use FormatsAjaxResponses;
    protected $adminTenantService;

    public function __construct(AdminTenantServiceInterface $adminTenantService)
    {
        $this->adminTenantService = $adminTenantService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'tenant_id']);
        $totalCollection = $this->adminTenantService->countWithFilters($filters);
        $perPage = $this->calculatePerPage($request, $totalCollection);
        $adminTenants = $this->adminTenantService->paginateWithFilters($filters, $perPage);
        $tenants = $this->adminTenantService->getTenantsList();
        $viewData = [
            'adminTenants' => $adminTenants,
            'tenants'      => $tenants,
        ];

        if ($request->ajax()) {
            return $this->ajaxResponse('admin.admin_tenants.partials.table', $viewData, $adminTenants);
        }

        return view('admin.admin_tenants.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = $this->adminTenantService->create();
        return view('admin.admin_tenants.create', $provinces);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminTenantRequest $request)
    {
        $this->adminTenantService->store($request->validated());
        session()->flash('success', __('successMessage'));
        return response()->json([
            'status' => true,
            'message' => __('successMessage')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->adminTenantService->show($id);
        return view('admin.admin_tenants.show', ['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = $this->adminTenantService->edit($id);
        return view('admin.admin_tenants.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminTenantRequest $request, string $id)
    {
        $this->adminTenantService->update($id, $request->validated());
        session()->flash('success', __('successMessage'));
        return response()->json([
            'status' => true,
            'message' => __('successMessage')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->adminTenantService->destroy($id);
        session()->flash('success', __('deleteMessage'));
        return response()->json([
            'status' => true,
            'message' => __('deleteMessage')
        ], 200);
    }

    public function getTenantsForAdmin(string $id)
    {
        $data = $this->adminTenantService->getTenantsForAdmin($id);
        return view('admin.admin_tenants.tenant_details', $data);
    }
}
