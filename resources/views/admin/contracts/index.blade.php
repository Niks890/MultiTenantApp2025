@extends('admin.master')
@section('title', 'Quản lý hợp đồng')
@section('content')
    <x-central.page-header title="" :breadcrumbs="[
        'Quản lý hợp đồng' => 'javascript:void(0)',
    ]" />
    <div class="card shadow-sm">
        <div class="d-flex align-items-start justify-content-start flex-column flex-md-row pt-2 pb-4 row mt-3 ms-3">
            <div class="col-12 col-md-7">
                <p class="fw-bold text-break mb-0 text-center text-md-start text-sm-start header-title">Danh sách hợp đồng
                </p>
            </div>
        </div>
        <hr class="border-dashed">
        <div class="d-flex justify-content-start align-items-center mt-3 mb-3 ms-4">
            <button type="button" class="btn btn-success" id="createContract">
                <i class="fa fa-plus"></i>
                <span class="d-none d-lg-inline ms-1">{{ __('create') }}</span>
            </button>
        </div>
        <div class="card-body">
            <div class="card-sub bg-primary">
                <form id="searchContractForm" class="d-flex align-items-center justify-content-between gap-3"
                    action="{{ route('contracts.index') }}" method="GET">
                    <div class="col-md-12">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-3">
                                <div class="search-box">
                                    <i class="fa fa-search search-icon"></i>
                                    <input name="keyword" type="text" placeholder="Tìm kiếm hợp đồng..."
                                        class="form-control search-input" value="{{ $keyword ?? '' }}"
                                        style="height: 45.45px;" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="tenant_id" class="choices-select form-select tenant-select">
                                    <option value="">{{__('filter by', ['item' => __('tenant_name')])}}</option>
                                    @foreach ($tenants as $tenant)
                                        <option value="{{ $tenant->id }}" {{ $tenantId == $tenant->id ? 'selected' : '' }}>
                                            {{ $tenant->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select name="status" class="choices-select form-select status-select">
                                    <option value="">{{__('filter by', ['item' =>strtoLower(__('contract_status'))])}}</option>
                                    <option value="1" {{ ($status ?? '') === '1' ? 'selected' : '' }}>{{__('paid')}}</option>
                                    <option value="2" {{ ($status ?? '') === '2' ? 'selected' : '' }}>{{__('overdue')}}</option>
                                    <option value="3" {{ ($status ?? '') === '3' ? 'selected' : '' }}>{{__('unpaid')}}</option>
                                    <option value="4" {{ ($status ?? '') === '4' ? 'selected' : '' }}>{{__('expired')}}</option>
                                    <option value="5" {{ ($status ?? '') === '5' ? 'selected' : '' }}>{{__('tenant_deleted')}}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="plan_id" class="choices-select form-select plan-select"
                                    style="height: 45.45px;">
                                    <option value="">{{__('filter by', ['item' => __('plan')])}}</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}"
                                            {{ $planId == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive mt-3" id="contractTableWrapper">
                @include('admin.contracts.partials.table', [
                    'contracts' => $contracts,
                    'keyword' => $keyword,
                    'status' => $status,
                    'selectedPaginate' => $selectedPaginate,
                ])
            </div>
            <div id="paginationWrapper">
                @include('admin.contracts.partials.pagination', [
                    'contracts' => $contracts,
                    'selectedPaginate' => $selectedPaginate,
                ])
            </div>
        </div>
    </div>
@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js@11.1.0/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/choices.css') }}">
@endsection
@section('js')
    <script>
        const deleteBtnLabel = @json(__('delete'));
        const cancelBtnLabel = @json(__('cancel'));
        const confirmDeleteBtnLabel = @json(__('delete_confirm'));
        $('#createContract').on('click', function() {
            window.location.href = "{{ route('contracts.create') }}";
        });
    </script>
    <script src="{{ asset('assets/custom/js/contracts/search.js') }}"></script>
    <script src="{{ asset('assets/extensions/choices.js@11.1.0/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/form-element-select.js') }}"></script>
    <script src="{{ asset('assets/custom/js/contracts/delete.js') }}"></script>
@endsection
