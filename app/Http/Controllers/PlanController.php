<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanRequest;
use App\Services\Contracts\PlanServiceInterface;
use App\Traits\FormatsAjaxResponses;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    use FormatsAjaxResponses;
    protected $planService;

    public function __construct(PlanServiceInterface $planService)
    {
        $this->planService = $planService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'cycle']);
        $totalCollection = $this->planService->countWithFilters($filters);
        $perPage = $this->calculatePerPage($request, $totalCollection);
        $data = $this->planService->paginateWithFilters($filters, $perPage);
        if ($request->ajax()) {
            return $this->ajaxResponse('admin.plans.partials.table', ['plans' => $data], $data);
        }
        return view('admin.plans.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlanRequest $request)
    {
        $this->planService->create($request->validated());
        session()->flash('success', __('successMessage'));
        return response()->json([
            'status' => true,
            'message' => __('successMessage')
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $plan = $this->planService->findOrFail($id);
        return view('admin.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $plan = $this->planService->findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlanRequest $request, string $id)
    {
        $this->planService->update($id, $request->validated());
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
        $this->planService->delete($id);
        session()->flash('success', __('deleteMessage'));
        return response()->json([
            'status' => true,
            'message' => __('deleteMessage'),
        ], 200);
    }

    public function updateStatus(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|boolean',
        ]);
        $plan = $this->planService->updateStatus($id, $validatedData['status']);
        $newStatusHtml = view('admin.plans.partials.status-badge', compact('plan'))->render();
        return response()->json([
            'status' => true,
            'message' => __('successMessage'),
            'newStatusHtml' => $newStatusHtml,
        ], 200);
    }
}
