@extends('admin.master')
@section('title', __('edit_item', ['item' => strtolower(__('payment_method'))]))
@section('content')
    <x-central.page-header :breadcrumbs="[
        __('manage_item', ['item' => strtolower(__('payment_method'))]) => route('payment-methods.index'),
        __('create') => '',
    ]" />
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('edit_item', ['item' => strtolower(__('payment_method'))]) }}</h4>
            </div>
            <hr class="border-dashed-form">
            <div class="card-body">
                 <form action="{{ route('payment-methods.update', $paymentMethod->id) }}" method="POST" id="editPaymentMethodForm" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="payment_method_name" class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                   Sửa phương thức: <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="payment_method_name" id="edit_payment_method_name"
                                        placeholder="{{ __('enter', ['item' => strtolower(__('payment_method_name'))]) }}"
                                        class="form-control" value="{{ $paymentMethod->name }}" " required>
                                    <div class="invalid-feedback" id="edit_payment_method_name_error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-10 offset-md-2">
                            <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary-custom lh-lg">
                                <i class="fas fa-arrow-left me-2"></i>
                                <span>{{ __('back') }}</span>
                            </a>
                            <button type="submit" class="btn btn-primary lh-lg" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
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
    <link rel="stylesheet" href="{{ asset('assets/custom/css/group-responsive.css') }}">
@endsection
@section('js')
    <script>
        const saveBtnLabel = @json(__('save'));
        const paymentMethodIndexUrl = "{{ route('payment-methods.index') }}";
    </script>
    <script src="{{ asset('assets/custom/js/payment-method/validate.js') }}"></script>
    <script src="{{ asset('assets/custom/js/payment-method/edit.js') }}"></script>
@endsection
