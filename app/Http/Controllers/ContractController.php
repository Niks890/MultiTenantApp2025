<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractRequest;
use App\Models\Contract;
use App\Models\PaymentMethod;
use App\Services\ContractService;
use App\Services\PlanService;
use App\Services\TaxService;
use App\Services\TenantService;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    protected $contractService;
    protected $tenantService;
    protected $planService;
    protected $taxService;

    public function __construct(ContractService $contractService, TenantService $tenantService, PlanService $planService, TaxService $taxService)
    {
        $this->contractService = $contractService;
        $this->tenantService = $tenantService;
        $this->planService = $planService;
        $this->taxService = $taxService;
    }

    public function index(Request $request)
    {
        try {
            $keyword = $request->keyword ?? '';
            $status = $request->status ?? '';
            $tenantId = $request->tenant_id ?? '';
            $planId = $request->plan_id ?? '';
            $paginate = $request->paginate ?? 10;
            $result = $this->contractService->searchContract($keyword, $status, $tenantId, $planId, $paginate);
            $contracts = $result['contracts'];
            $tenants = $this->tenantService->getAllTenants();
            $plans = $this->planService->all();
            $selectedPaginate = $result['selectedPaginate'];

            if ($request->ajax()) {
                $table = view('admin.contracts.partials.table', compact('contracts', 'keyword', 'status', 'tenantId', 'planId', 'selectedPaginate'))->render();
                $pagination = view('admin.contracts.partials.pagination', compact('contracts', 'selectedPaginate'))->render();
                return response()->json([
                    'table' => $table,
                    'pagination' => $pagination,
                    'selected_paginate' => $selectedPaginate
                ]);
            }
            return view('admin.contracts.index', compact('contracts', 'tenants', 'plans', 'keyword', 'status', 'tenantId', 'planId', 'selectedPaginate'));
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
        $tenants = $this->tenantService->getTenantWithAdmin();
        $plans = $this->planService->getPlanActive();
        $currentTax = $this->taxService->getCurrentTax();
        return view('admin.contracts.create', compact('tenants', 'plans', 'currentTax'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContractRequest $request)
    {
        $data = $request->validated();
        if ($this->contractService->store($data)) {
            session()->flash('success', 'Tạo hợp đồng thành công.');
            return response()->json(['status' => true, 'message' => 'Tạo hợp đồng thành công.']);
        }
        session()->flash('error', 'Tạo hợp đồng thất bại.');
        return response()->json(['status' => false, 'message' => 'Tạo hợp đồng thất bại.'], 422);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contract = Contract::with([
            'transactions',
            'tenant.adminTenant',
            'plan',
            'tax'
        ])->findOrFail($id);
        $transactions = $contract->transactions()->paginate(10);
        $paymentMethods = PaymentMethod::select('id', 'name')->where('delete_flg', 0)->get();
        return view('admin.contracts.show', [
            'contract' => $contract,
            'transactions' => $transactions,
            'paymentMethods' => $paymentMethods
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        if ($this->contractService->destroy($contract->id)) {
            session()->flash('success', 'Xóa hợp đồng thành công.');
            return response()->json(['success' => true, 'message' => 'Xóa hợp đồng thành công.']);
        }
        session()->flash('error', 'Xóa hợp đồng thất bại.');
        return response()->json(['success' => false, 'message' => 'Xóa hợp đồng thất bại.'], 422);
    }
}
