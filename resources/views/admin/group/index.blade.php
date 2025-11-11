@extends('admin.master')
@section('title', __('manage_item', ['item' => strtolower(__('group_tenant'))]))
@section('content')
    <x-central.page-header :breadcrumbs="[
        __('manage_item', ['item' => strtolower(__('group_tenant'))]) => 'javascript:void(0)',
    ]" />

    <div class="card shadow-sm">
        <div class="d-flex align-items-center justify-content-start ms-4 pt-2 pb-2 mt-3 mb-4">

            <p class="fw-bold text-break mb-0 text-center text-md-start text-sm-start header-title">
                {{ __('list_item', ['item' => strtolower(__('group_tenant'))]) }}</p>

        </div>
        <hr class="border-dashed">
        <div class="d-flex justify-content-start align-items-center mt-3 mb-3 ms-4">
            <button type="button" class="btn btn-success" id="createGroup">
                <i class="fa fa-plus"></i>
                <span class="d-none d-lg-inline ms-1">{{ __('create') }}</span>
            </button>
        </div>

        <div class="card-body">
            <div class="card-sub bg-primary p-0">
                <form id="searchGroupForm" class="d-flex align-items-center justify-content-between p-3 gap-3"
                    action="{{ route('group.index') }}" method="GET">
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
            <div class="table-responsive mt-3" id="groupTableWrapper">
                @include('admin.group.partials.group-table', [
                    'groups' => $groups,
                    'keyword' => $keyword,
                    'selectedPaginate' => $selectedPaginate,
                ])
            </div>
            <div id="paginationWrapper">
                @include('admin.group.partials.pagination', [
                    'groups' => $groups,
                    'selectedPaginate' => $selectedPaginate,
                ])
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/modal.css') }}">
@endsection

@section('js')
    <script>
        const deleteBtnLabel = @json(__('delete'));
        const cancelBtnLabel = @json(__('cancel'));
        const confirmDeleteBtnLabel = @json(__('delete_confirm'));
        $('#createGroup').on('click', function() {
            window.location.href = "{{ route('group.create') }}";
        });
    </script>
    <script src="{{ asset('assets/custom/js/group-tenant/delete-group.js') }}"></script>
    <script src="{{ asset('assets/custom/js/group-tenant/search-group.js') }}"></script>
@endsection
