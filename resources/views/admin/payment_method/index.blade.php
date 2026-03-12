@extends('admin.master')
@section('title', __('manage_item', ['item' => strtolower(__('payment_method'))]))

@section('content')
    <x-central.page-header title="" :breadcrumbs="[
        __('manage_item', ['item' => strtolower(__('payment_method'))]) => 'javascript:void(0)',
    ]" />

    {{-- Main content --}}
    <div class="card shadow-sm">
        <div class="d-flex align-items-center justify-content-start ms-4 pt-2 pb-2 mt-3 mb-4">
            <h5 class="fw-bold text-break mb-0 fs-6">
                {{ __('list_item', ['item' => strtolower(__('payment_method'))]) }}
            </h5>
        </div>

        <hr class="border-dashed">

        <div class="d-flex justify-content-start align-items-center mt-3 mb-3 ms-4">
            <button id="createPaymentMethod" type="button" class="btn btn-success"
                data-create-url="{{ route('payment-methods.create') }}">
                <i class="fa fa-plus"></i>
                <span class="d-inline ms-1">{{ __('create') }}</span>
            </button>
        </div>

        <div class="card-body">
            <div class="card-sub bg-primary">
                <form id="searchForm" class="d-flex align-items-center justify-content-between p-3 gap-3"
                    action="{{ route('payment-methods.index') }}" method="GET">
                    <div class="col-md-9">
                        <div class="row g-2">
                            <div class="col-md-12">
                                <div class="search-box">
                                    <i class="fa fa-search search-icon"></i>
                                    <input name="keyword" type="text" placeholder="{{ __('search') }}"
                                        class="form-control search-input" value="{{ $keyword ?? '' }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive" id="paymentMethodTableWrapper">
                @include('admin.payment_method.partials.table', [
                    'paymentMethods' => $paymentMethods,
                    'keyword' => $keyword,
                    'selectedPaginate' => $selectedPaginate,
                ])
            </div>
            <div id="paginationWrapper">
                @include('admin.payment_method.partials.pagination', [
                    'paymentMethods' => $paymentMethods,
                    'selectedPaginate' => $selectedPaginate,
                ])
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/input.css') }}">
@endsection

@section('js')
    <script>
        const deleteBtnLabel = @json(__('delete'));
        const cancelBtnLabel = @json(__('cancel'));
        const confirmDeleteBtnLabel = @json(__('delete_confirm'));
        $('#createPaymentMethod').on('click', function() {
            window.location.href = "{{ route('payment-methods.create') }}";
        });
    </script>
    <script src="{{ asset('assets/custom/js/payment-method/delete.js') }}"></script>
    <script src="{{ asset('assets/custom/js/payment-method/payment-method.js') }}"></script>
@endsection
