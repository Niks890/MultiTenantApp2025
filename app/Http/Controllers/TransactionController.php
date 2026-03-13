<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:t_contracts,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|exists:m_payment_methods,id',
            'file_path' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path');
        }
        try {
            $this->transactionService->store($data);
            return response()->json(['message' => 'Tạo giao dịch thành công']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Tạo giao dịch thất bại: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
