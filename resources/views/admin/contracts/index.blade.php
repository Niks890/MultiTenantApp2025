@extends('admin.master')
@section('title', 'Danh sách hợp đồng')
@section('content')
    <x-central.page-header title="" :breadcrumbs="[
        'Danh sách hợp đồng' => 'javascript:void(0)',
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
                                    <option value="">Lọc theo tên cửa hiệu</option>
                                    {{-- @foreach ($groups as $group)
                                        <option value="{{ $group->id }}" {{ $groupId == $group->id ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach --}}
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select name="status" class="choices-select form-select status-select">
                                    <option value="">Lọc theo trạng thái hợp đồng</option>

                                    {{-- <option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Hoạt
                                        động</option>
                                    <option value="inactive" {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>Bảo trì
                                    </option>
                                    <option value="deleted" {{ ($status ?? '') === 'deleted' ? 'selected' : '' }}>Đã bị xoá
                                    </option> --}}

                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="plan_id" class="choices-select form-select plan-select" style="height: 45.45px;">
                                    <option value="">Lọc theo gói đăng ký</option>
                                    {{-- @foreach ($adminTenants as $admin)
                                        <option value="{{ $admin->id }}"
                                            {{ $adminTenantId == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->display_name }}
                                        </option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive mt-3" id="ContractTableWrapper">
                {{-- @include('admin.contracts.partials.table', [
                    'tenants' => $tenants,
                    'keyword' => $keyword,
                    'status' => $status,
                    'selectedPaginate' => $selectedPaginate,
                ]) --}}

                @include('admin.contracts.partials.table', [
                    'contracts' => $contracts,
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
@endsection
