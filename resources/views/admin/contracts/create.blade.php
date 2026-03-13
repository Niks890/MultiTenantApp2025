@extends('admin.master')
@section('title', 'Thêm mới hợp đồng')
@section('content')
    <x-central.page-header :breadcrumbs="[
        'Quản lý hợp đồng' => route('contracts.index'),
        __('create') => route('contracts.create'),
    ]" />
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Thêm mới hợp đồng</h4>
            </div>
            <hr class="border-dashed-form">
            <div class="card-body">
                <form action="{{ route('contracts.store') }}" method="POST" id="createContractForm" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenant_id" class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('select_item', ['item' => strtoLower(__('tenant'))]) }}: <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <select name="tenant_id" id="tenant_id" class="form-control form-select"
                                        placeholder="Chọn cửa hiệu">
                                        <option value="">Chọn cửa hiệu</option>
                                        @foreach ($tenants as $tenant)
                                            <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenant_name" class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('tenant_name') }}: <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="tenant_name" id="tenant_name" placeholder="Tên cửa hiệu"
                                        class="form-control bg-light" value="{{ old('tenant_name') }}" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="admin_tenant_name"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    Tên người thuê: <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="admin_tenant_name" id="admin_tenant_name"
                                        placeholder="Tên người thuê" class="form-control bg-light"
                                        value="{{ old('admin_tenant_name') }}" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="admin_tenant_date_of_birth"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    Ngày sinh: <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="admin_tenant_date_of_birth" id="admin_tenant_date_of_birth"
                                        placeholder="Ngày sinh" class="form-control bg-light"
                                        value="{{ old('admin_tenant_date_of_birth') }}" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="admin_tenant_address"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    Địa chỉ: <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="admin_tenant_address" id="admin_tenant_address"
                                        placeholder="Địa chỉ" class="form-control bg-light" value="{{ old('admin_tenant_address') }}"
                                        readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="admin_tenant_phone"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    Số điện thoại: <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="admin_tenant_phone" id="admin_tenant_phone"
                                        placeholder="Số điện thoại" class="form-control bg-light"
                                        value="{{ old('admin_tenant_phone') }}" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="admin_tenant_email"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    Email: <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="email" name="admin_tenant_email" id="admin_tenant_email"
                                        placeholder="Email" class="form-control bg-light" value="{{ old('admin_tenant_email') }}"
                                        readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="payment_mode" class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('select_item', ['item' => strtoLower(__('payment_mode'))]) }}: <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <select name="payment_mode" id="payment_mode" class="form-control form-select"
                                        placeholder="Chọn hình thức trả">
                                        <option value="">Chọn hình thức trả</option>
                                        <option value="1">Tiền mặt</option>
                                        <option value="2">Chuyển khoản</option>
                                        <option value="3">Thẻ tín dụng</option>
                                        <option value="4">Ví điện tử</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="plan_id" class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('select_item', ['item' => strtoLower(__('plan'))]) }}: <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <select name="plan_id" id="plan_id" class="form-control form-select">
                                        <option value="">Chọn gói dịch vụ</option>
                                        @foreach ($plans as $plan)
                                            <option value="{{ $plan->id }}"
                                                data-custom-properties='{{ json_encode([
                                                    'price' => $plan->price,
                                                    'cycle' => $plan->cycle,
                                                ]) }}'>
                                                {{ $plan->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="plan_price" class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('plan_price') }} (đ): <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="plan_price" id="plan_price"
                                        placeholder="Giá gói dịch vụ" class="form-control bg-light"
                                        value="{{ old('plan_price') }}" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="vat_price" class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    Thuế Vat (đ): <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="vat_price" id="vat_price" placeholder="Thuế vat"
                                        class="form-control bg-light" value="{{ old('vat_price') }}" readonly required>
                                        <input type="hidden" name="tax_id" id="tax_id" value="{{ $currentTax->id}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="amount_after_tax"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('amount_after_tax') }} (đ): <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="amount_after_tax" id="amount_after_tax"
                                        placeholder="Tổng tiền phải trả" class="form-control bg-light"
                                        value="{{ old('amount_after_tax') }}" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="cycle_plan" class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    Chu kỳ thuê gói : <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="cycle_plan" id="cycle_plan"
                                        placeholder="Tự động điền theo gói" class="form-control bg-light"
                                        value="{{ old('cycle_plan') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="start_at" class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    Nhập {{ strtoLower(__('start_at')) }}: <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="start_at" id="start_at" placeholder="Ngày bắt đầu thuê"
                                        class="form-control" value="{{ old('start_at') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="end_at" class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('end_at') }}: <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="end_at" id="end_at" class="form-control bg-light"
                                        placeholder="Ngày kết thúc thuê" value="{{ old('end_at') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="due_date" class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('due_date') }}: <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="due_date" id="due_date" placeholder="Ngày đến hạn"
                                        class="form-control bg-light" value="{{ old('due_date') }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-10 offset-lg-2 d-flex gap-2 custom-action-col">
                            <a href="{{ route('contracts.index') }}" class="btn btn-light">
                                <i class="bi bi-arrow-left me-1"></i>
                                <span>{{ __('back') }}</span>
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-1"></i>
                                <span>{{ __('save') }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/admin-tenants.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/themes/material_blue.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/group-responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/modal.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/extensions/choices.js@11.1.0/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/choices.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/tenant.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/input.css') }}">

@endsection
@section('js')
    <script>
        const contractsIndexUrl = "{{ route('contracts.index') }}";
        const GLOBAL_TAX_RATE = {{ $currentTax->rate ?? 10 }};
    </script>
    <script src="{{ asset('assets/extensions/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/extensions/flatpickr/l10n/vn.js') }}"></script>
    <script src="{{ asset('assets/extensions/choices.js@11.1.0/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/form-element-select.js') }}"></script>
    <script type="module" src="{{ asset('assets/custom/js/contracts/validate.js') }}"></script>
@endsection
