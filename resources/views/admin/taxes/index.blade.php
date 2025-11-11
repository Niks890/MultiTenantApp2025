@extends('admin.master')
@section('title', __('manage_item', ['item' => strtolower(__('tax'))]))

@section('content')
    <x-central.page-header title="" :breadcrumbs="[
        __('manage_item', ['item' => strtolower(__('tax'))]) => 'javascript:void(0)',
    ]" />

    {{-- Main content --}}
    <div class="card shadow-sm">
        <div class="d-flex align-items-center justify-content-start ms-4 pt-2 pb-2 mt-3 mb-4">
            <h5 class="fw-bold text-break mb-0 fs-6">
                {{ __('list_item', ['item' => strtolower(__('tax'))]) }}
            </h5>
        </div>

        <hr class="border-dashed">

        <div class="d-flex justify-content-start align-items-center mt-3 mb-3 ms-4">
            <button id="addNew" type="button" class="btn btn-success" data-create-url="{{ route('taxes.create') }}">
                <i class="fa fa-plus"></i>
                <span class="d-inline ms-1">{{ __('create') }}</span>
            </button>
        </div>

        <div class="card-body">
            <div class="card-sub bg-primary d-flex align-items-center">
                <form id="searchForm" class="d-flex align-items-center justify-content-between gap-3 w-100"
                    action="{{ route('taxes.index') }}">
                    <div class="col-12">
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-md-7">
                                {{-- Search box --}}
                                <div class="search-box">
                                    <i class="fa fa-search search-icon"></i>
                                    <input type="text" name="search" class="form-control search-input"
                                        placeholder="{{ __('search') }}" value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-5">
                                {{-- select search status --}}
                                <select name="status" class="choices-select form-select">
                                    <option value="">{{ __('all_items', ['item' => strtolower(__('status'))]) }}
                                    </option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                        {{ __('active') }}
                                    </option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                        {{ __('inactive') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <div id="table-responsive" class="table-responsive">
                <!-- Table will be loaded dynamically -->
            </div>
            <div id="pagination-container">
                <!-- Pagination will be loaded dynamically -->
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js@11.1.0/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/choices.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/toast-notification.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/pagination-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/input.css') }}">
@endsection

@section('js')
    <script src="{{ asset('assets/extensions/choices.js@11.1.0/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/form-element-select.js') }}"></script>

    <script>
        // Translations
        const no = @json(__('no'));
        const taxRate = @json(__('tax_rate'));
        const taxStatus = @json(__('status'));
        const action = @json(__('action'));

        // Messages
        const fetchErrorMessage = @json(__('fetchErrorMessage'));
        const loadingMessage = @json(__('loading'));
        const noDataFoundMessage = @json(__('noDataFoundMessage'));
        const pressToSelect = @json(__('pressToSelect'));
        const successMessage = @json(__('successMessage'));
        const errorMessage = @json(__('successErrorMessage'));
        const turnOff = @json(__('turnOff'));
        const turnOn = @json(__('turnOn'));
        const confirmActionMessage = @json(__('confirm'));
        const cancelMessage = @json(__('cancel'));
        const confirmMessage = @json(__('confirmMessage'));
        const deleteConfirmMessage = @json(__('delete_confirm'));
        const deleteMessage = @json(__('delete'));
    </script>

    <script type="module" src="{{ asset('assets/custom/js/taxes/taxes.js') }}"></script>
@endsection
