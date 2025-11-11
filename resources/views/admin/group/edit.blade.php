@extends('admin.master')
@section('title', __('edit_item', ['item' => strtolower(__('group_tenant'))]))
@section('content')
    <x-central.page-header :breadcrumbs="[
        __('manage_item', ['item' => strtolower(__('group_tenant'))]) => route('group.index'),
        __('edit') => '',
    ]" />
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('edit_item', ['item' => strtolower(__('group_tenant'))]) }}</h4>
            </div>
            <hr class="border-dashed-form">
            <div class="card-body">
                <form action="{{ route('group.update', $group->id) }}" method="POST" id="editGroupForm" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="group_name" class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('group_tenant_name') }}: <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="group_name"
                                        placeholder="{{ __('enter', ['item' => strtolower(__('group_tenant_name'))]) }}"
                                        class="form-control" id="edit_group_name" value="{{ $group->name }}" required>
                                    <div class="invalid-feedback" id="edit_group_name_error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="group_description"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('description') }}:</label>
                                <div class="col-12 col-lg-10">
                                    <textarea name="group_description" id="edit_group_description" cols="5" rows="5"
                                        placeholder="{{ __('enter', ['item' => strtolower(__('description'))]) }}" class="form-control">{{ $group->description }}</textarea>
                                    <div class="invalid-feedback" id="edit_group_description_error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-10 offset-md-2">
                            <a href="{{ route('group.index') }}" class="btn btn-secondary-custom lh-lg">
                                <i class="fas fa-arrow-left me-2"></i>
                                <span>{{ __('back') }}</span>
                            </a>
                            <button type="submit" class="btn btn-primary lh-lg" id="editSubmitBtn">
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
        const groupIndexUrl = "{{ route('group.index') }}";
    </script>
    <script src="{{ asset('assets/custom/js/group-tenant/group-validate.js') }}"></script>
    <script src="{{ asset('assets/custom/js/group-tenant/edit.js') }}"></script>
@endsection
