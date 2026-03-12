<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentMethodRequest;
use App\Models\PaymentMethod;
use App\Services\PaymentMethodService;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{

    protected $paymentMethodService;
    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $keyword = $request->keyword ?? '';
            $paginate = $request->paginate ?? 10;
            $result = $this->paymentMethodService->searchPaymentMethod($keyword, $paginate);
            $paymentMethods = $result['paymentMethods'];
            $selectedPaginate = $result['selectedPaginate'];
            if ($request->ajax()) {
                $table = view(
                    'admin.payment_method.partials.table',
                    compact('paymentMethods', 'keyword', 'selectedPaginate')
                )->render();
                $pagination = view('admin.payment_method.partials.pagination', compact('paymentMethods', 'selectedPaginate'))->render();

                return response()->json([
                    'table' => $table,
                    'pagination' => $pagination,
                    'selected_paginate' => $selectedPaginate
                ]);
            }
            return view('admin.payment_method.index', compact('paymentMethods', 'keyword', 'selectedPaginate'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Lấy danh sách thất bại: ' . $e->getMessage()], 500);
            }
        }
        return back()->withErrors(['error' => 'Lấy danh sách thất bại: ' . $e->getMessage()]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payment_method.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentMethodRequest $request)
    {
        if ($this->paymentMethodService->store($request->all())) {
            session()->flash('success', 'Thêm phương thức thanh toán thành công.');
            return response()->json(['success' => true, 'message' => 'Thêm phương thức thanh toán thành công.', 'data' => $request->all()]);
        }
        session()->flash('error', 'Thêm phương thức thanh toán thất bại.');
        return response()->json(['success' => false, 'message' => 'Thêm phương thức thanh toán thất bại.'], 422);
    }



    public function edit(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->delete_flg == 1) {
            abort(404);
        }
        $paymentMethod = $this->paymentMethodService->edit($paymentMethod->id);
        return view('admin.payment_method.edit', compact('paymentMethod'));
    }


    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        if ($this->paymentMethodService->update($request->all(), $paymentMethod->id)) {
            session()->flash('success', 'Cập nhật thông tin phương thức thanh toán thành công.');
            return response()->json(['success' => true, 'message' => 'Cập nhật thông tin phương thức thanh toán thành công.', 'data' => $request->all()]);
        }
        session()->flash('error', 'Cập nhật thông tin phương thức thanh toán thất bại.');
        return response()->json(['success' => false, 'message' => 'Cập nhật thông tin phương thức thanh toán thất bại.'], 422);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($this->paymentMethodService->destroy($paymentMethod->id)) {
            session()->flash('success', 'Xóa phương thức thanh toán thành công.');
            return response()->json(['success' => true, 'message' => 'Xóa phương thức thanh toán thành công.']);
        }
        session()->flash('error', 'Xóa phương thức thanh toán thất bại, do đang có cửa hiệu hoạt động.');
        return response()->json(['success' => false, 'message' => 'Xóa phương thức thanh toán thất bại.'], 422);
    }
}
