@extends('admin.master')
@section('title', __('create_item', ['item' => strtolower(__('admin_tenant'))]))

@section('content')

    <div class="page-heading">
        <x-central.page-header title="" :breadcrumbs="[
            __('manage_item', ['item' => strtolower(__('admin_tenant'))]) => route('admin-tenants.index'),
            __('create') => '',
        ]" />
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('create_item', ['item' => strtolower(__('admin_tenant'))]) }}</h4>
                </div>

                <hr class="border-dashed-form">

                <div class="card-body">
                    <form id="admin-tenant-form" action="{{ route('admin-tenants.store') }}" method="POST"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="row">

                            <!-- Username -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="username" class="align-middle">{{ __('account_name') }}: <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" id="username" class="form-control" name="username"
                                            placeholder="{{ __('enter', ['item' => strtolower(__('account_name'))]) }}"
                                            value="{{ old('username') }}">
                                    </div>

                                </div>
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="password">{{ __('password') }}: <span
                                                class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-10">

                                        <div class="input-group flex-nowrap">
                                            <div class="input-container position-relative flex-grow-1">

                                                <input type="text" id="password"
                                                    class="form-control input-with-icon-inside input-with-button-right"
                                                    name="password"
                                                    placeholder="{{ __('enter', ['item' => strtolower(__('password'))]) }}">

                                                <button class="generate-pass-btn border-0 p-0" type="button"
                                                    id="generate-password-btn" title="Tự sinh mật khẩu">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>

                                            </div>

                                            <button class="btn btn-outline-secondary show-pass-btn" type="button">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Name -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="name">{{ __('full_name') }}: <span
                                                class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-10">
                                        <input type="text" id="name" class="form-control" name="name"
                                            placeholder="{{ __('enter', ['item' => strtolower(__('full_name'))]) }}"
                                            value="{{ old('name') }}">
                                    </div>

                                </div>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="email">{{ __('email') }}:</label>
                                    </div>

                                    <div class="col-md-10">
                                        <input type="email" id="email" class="form-control" name="email"
                                            placeholder="{{ __('enter', ['item' => strtolower(__('email'))]) }}"
                                            value="{{ old('email') }}">

                                    </div>

                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="phone">{{ __('phone') }}: <span
                                                class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-10">
                                        <input type="text" id="phone" class="form-control" name="phone"
                                            placeholder="{{ __('enter', ['item' => strtolower(__('phone'))]) }}"
                                            value="{{ old('phone') }}">
                                    </div>

                                </div>
                            </div>

                            <!-- Date of birth -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="birthday">{{ __('birthday') }}: </label>
                                    </div>

                                    <div class="col-md-10">
                                        <div class="input-group">

                                            <input type="text" id="birthday-display" class="form-control bg-white"
                                                placeholder="{{ __('ddd_mm_yyyy') }}" name="birthday_display"
                                                data-toggle="flatpickr">

                                            <div class="input-group-append ms-0">
                                                <button id="birthday-btn"
                                                    class="btn btn-outline-secondary calendar-icon-btn" type="button"
                                                    data-toggle="flatpickr">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </button>
                                            </div>

                                        </div>
                                        <input type="hidden" id="birthday" name="birthday"
                                            value="{{ old('birthday') }}">
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="province">{{ __('province') }}: </label>
                                    </div>

                                    <div class="col-md-10">
                                        <select id="province" name="province" class="choices form-select">
                                            <option value="">{{ __('select_item', ['item' => __('province')]) }}
                                            </option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province['province_code'] }}">
                                                    {{ $province['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <label for="ward">{{ __('ward') }}: </label>
                                    </div>

                                    <div class="col-md-10">
                                        <select id="ward" name="ward" class="choices-select form-select">
                                            {{-- Will be populated dynamically based on selected province --}}
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <!-- Street address -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="street">{{ __('street') }}: </label>
                                    </div>

                                    <div class="col-md-10">
                                        <input type="text" id="street" class="form-control" name="street"
                                            placeholder="{{ __('enter', ['item' => strtolower(__('street'))]) }}"
                                            value="{{ old('street') }}">

                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="form-group mt-4 row">
                            <div class="col-md-10 offset-md-2">
                                <a href="{{ route('admin-tenants.index') }}" class="btn btn-secondary-custom lh-lg"><i
                                        class="fas fa-arrow-left me-2"></i> <span>{{ __('back') }}</span></a>
                                <button type="submit" class="btn btn-primary lh-lg"> <i class="fas fa-save me-2"></i>
                                    <span>{{ __('save') }}</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>


@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/themes/material_blue.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/extensions/choices.js@11.1.0/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/choices.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/input.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/admin-tenants.css') }}">
@endsection

@section('js')
    <script src="{{ asset('assets/extensions/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/extensions/flatpickr/l10n/vn.js') }}"></script>
    <script src="{{ asset('assets/extensions/choices.js@11.1.0/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('assets/custom/js/helpers/random-password.js') }}"></script>
    <script src="{{ asset('assets/custom/js/helpers/show-password.js') }}"></script>
    <script type="module" src="{{ asset('assets/custom/js/admin-tenants/admin-tenant-form.js') }}"></script>

    <script type="module">
        import AdminTenantForm from '{{ asset('assets/custom/js/admin-tenants/admin-tenant-form.js') }}';

        $(function() {
            initializePasswordGeneration('#password', 8);
            setupGeneratePasswordButton('#generate-password-btn', '#password', 8);

            new AdminTenantForm('admin-tenant-form', '{{ route('admin-tenants.index') }}',
                @json($provinces), 'create');
        });
    </script>
@endsection
