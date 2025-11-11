<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaxRequest;
use App\Services\Contracts\TaxServiceInterface;
use App\Traits\FormatsAjaxResponses;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    use FormatsAjaxResponses;
    protected $taxService;

    public function __construct(TaxServiceInterface $taxService)
    {
        $this->taxService = $taxService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status']);
        $totalCollection = $this->taxService->countWithFilters($filters);
        $perPage = $this->calculatePerPage($request, $totalCollection);
        $data = $this->taxService->paginateWithFilters($filters, $perPage);
        if ($request->ajax()) {
            return $this->ajaxResponse('admin.taxes.partials.table', ['taxes' => $data], $data);
        }
        return view('admin.taxes.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.taxes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaxRequest $request)
    {
        $this->taxService->create($request->validated());
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
        $tax = $this->taxService->findOrFail($id);
        return view('admin.taxes.show', compact('tax'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tax = $this->taxService->findOrFail($id);
        return view('admin.taxes.edit', compact('tax'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaxRequest $request, string $id)
    {
        $this->taxService->update($id, $request->validated());
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
        $this->taxService->delete($id);
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
        $tax = $this->taxService->updateStatus($id, $validatedData['status']);
        return response()->json([
            'status' => true,
            'message' => __('successMessage'),
            'refreshTable' => true
        ], 200);
    }
}
