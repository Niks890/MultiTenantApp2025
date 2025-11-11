@extends('admin.master')
@section('title', __('manage_item', ['item' => strtolower(__('system_user'))]))
@section('content')

    <x-central.page-header :breadcrumbs="[
        __('manage_item', ['item' => strtolower(__('system_user'))]) => 'javascript:void(0)',
    ]" />

    <div class="card shadow-sm">
        <div class="d-flex align-items-start justify-content-start flex-column flex-md-row pt-2 pb-4 row mt-3 ms-3">
            <div class="col-12 col-md-7">
                <p class="fw-bold text-break mb-0 text-center text-md-start text-sm-start header-title">
                    {{ __('list_item', ['item' => strtolower(__('system_user'))]) }}</p>
            </div>
        </div>
        <hr class="border-dashed">
        <div class="d-flex justify-content-start align-items-center mt-3 mb-3 ms-4">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inlineForm">
                <i class="fa fa-plus"></i>
                <span class="d-none d-lg-inline ms-1">{{ __('create') }}</span>
            </button>
        </div>
        <div class="card-body">
            <div class="card-sub bg-primary">
                <form id="searchSystemUserForm" class="d-flex align-items-center justify-content-between gap-3 "
                    action="{{ route('system-user.index') }}" method="GET">
                    <div class="col-md-12">
                        <div class="row g-2">
                            <div class="col-md-7">
                                <div class="search-box">
                                    <i class="fa fa-search search-icon"></i>
                                    <input name="keyword" type="text" placeholder="{{ __('search') }}"
                                        class="form-control search-input" value="{{ $keyword ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-5">
                                <select name="status" class="form-select status-select">
                                    <option value="">
                                        {{ __('all_items', ['item' => strtolower(__('status'))]) }}
                                    </option>
                                    <option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>
                                        {{ __('active') }}
                                    </option>
                                    <option value="inactive" {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>
                                        {{ __('inactive') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive mt-3" id="userTableWrapper">
                @include('admin.system_user.partials.system-user_table', [
                    'systemUsers' => $systemUsers,
                    'keyword' => $keyword,
                    'status' => $status,
                    'selectedPaginate' => $selectedPaginate,
                ])
            </div>
            <div id="paginationWrapper">
                @include('admin.system_user.partials.pagination', [
                    'systemUsers' => $systemUsers,
                    'selectedPaginate' => $selectedPaginate,
                ])
            </div>

        </div>
    </div>
    @include('admin.system_user.partials.modal-create')
    @include('admin.system_user.partials.modal-update')
    @include('admin.system_user.partials.modal-crop-avatar')



@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/croppie-2.6.4/croppie.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/toggle-password.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js@11.1.0/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/choices.css') }}">
@endsection

@section('js')
    <script>
        const updateBtnLabel = @json(__('save'));
        const deleteBtnLabel = @json(__('delete'));
        const cancelBtnLabel = @json(__('cancel'));
        const confirmDeleteBtnLabel = @json(__('delete_confirm'));
        const currentUserId = @json(auth()->user()->id);
        const currentUserIsSuperAdmin = @json(auth()->user()->is_super_admin);
    </script>
    <script src="{{ asset('assets/extensions/croppie-2.6.4/croppie.js') }}"></script>
    <script src="{{ asset('assets/custom/js/system-user/system-user-validate.js') }}"></script>
    <script src="{{ asset('assets/custom/js/system-user/create-edit-user.js') }}"></script>
    <script src="{{ asset('assets/custom/js/system-user/system-user-search.js') }}"></script>
    <script src="{{ asset('assets/custom/js/system-user/delete-user.js') }}"></script>
    <script src="{{ asset('assets/custom/js/system-user/crop-avatar.js') }}"></script>
    <script src="{{ asset('assets/extensions/choices.js@11.1.0/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('assets/custom/js/system-user/choice-init.js') }}"></script>
@endsection
