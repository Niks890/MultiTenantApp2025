<?php

namespace App\Http\Controllers;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


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
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'contract_id'    => 'required|exists:t_contracts,id',
            'amount'         => 'required|numeric|min:1',
            'payment_date'   => 'required|date',
            'payment_method' => 'required|exists:m_payment_methods,id',
            'file_path'      => 'required|image|mimes:jpeg,png,webp|max:2048',
        ]);
        try {
            $data = $validated;
            if ($request->hasFile('file_path')) {
                $data['file_path'] = $request->file('file_path');
            }
            $this->transactionService->store($data);
            return response()->json(['message' => __('Tạo giao dịch thành công')]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'amount'         => ['required', 'numeric', 'min:1'],
            'payment_date'   => ['required', 'date'],
            'payment_method' => ['required', 'exists:m_payment_methods,id'],
            'file_path'      => ['nullable', 'image', 'mimes:jpeg,png,webp|max:2048'],
            'keep_file'      => ['nullable', 'in:0,1'],
        ]);

        try {
            $data = $validated;
            if ($request->hasFile('file_path')) {
                $data['file_path'] = $request->file('file_path');
            }
            $this->transactionService->update($id, $data);
            return response()->json([
                'message' => __('Cập nhật giao dịch thành công.'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->transactionService->destroy($id);

            return response()->json([
                'success' => true,
                'message' => __('Xóa giao dịch thành công.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Xóa thất bại: ') . $e->getMessage()
            ], 422);
        }
    }
}
