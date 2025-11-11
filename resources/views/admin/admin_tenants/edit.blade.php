@extends('admin.master')
@section('title', __('edit_item', ['item' => strtolower(__('admin_tenant'))]))

@section('content')

    <div class="page-heading">
        <x-central.page-header title="" :breadcrumbs="[
            __('manage_item', ['item' => strtolower(__('admin_tenant'))]) => route('admin-tenants.index'),
            __('edit') => '',
        ]" />
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('edit_item', ['item' => strtolower(__('admin_tenant'))]) }}</h4>
                </div>

                <hr class="border-dashed-form">

                <div class="card-body">
                    <form id="admin-tenant-form" action="{{ route('admin-tenants.update', $id) }}" method="POST"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row">

                            <!-- Username -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="username">{{ __('account_name') }}: <span
                                                class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-10">
                                        <input type="text" id="username" class="form-control" name="username"
                                            placeholder="{{ __('enter', ['item' => strtolower(__('account_name'))]) }}"
                                            value="{{ old('username', $username ?? '') }}">
                                    </div>

                                </div>
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="password">{{ __('password') }}: </label>
                                    </div>

                                    <div class="col-md-10">
                                        <div class="input-group flex-nowrap">
                                            <input type="password" id="password"
                                                class="form-control input-with-button-right" name="password">
                                            <button class="btn btn-outline-secondary show-pass-btn" type="button"
                                                data-target="password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-10 offset-md-2">
                                        <small class="form-text text-muted">Chỉ nhập nếu muốn thay đổi mật khẩu</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="password-confirmation">{{ __('password_confirmation') }}: </label>
                                    </div>

                                    <div class="col-md-10">
                                        <div class="input-group flex-nowrap">
                                            <input type="password" id="password-confirmation" class="form-control"
                                                name="password-confirmation"
                                                placeholder="{{ __('enter', ['item' => strtolower(__('password_confirmation'))]) }}"
                                                disabled>
                                            <button class="btn btn-outline-secondary show-pass-btn" type="button">
                                                <i class="fas fa-eye"></i>
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
                                            value="{{ old('name', $name ?? '') }}">
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
                                            value="{{ old('email', $email ?? '') }}">
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
                                            value="{{ old('phone', $phone ?? '') }}">
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
                                                data-toggle="flatpickr"
                                                value="{{ old('birthday_display', !empty($birthday) ? \Carbon\Carbon::parse($birthday)->format('d/m/Y') : '') }}">

                                            <div class="input-group-append" style="margin-left: 0">
                                                <button id="birthday-btn"
                                                    class="btn btn-outline-secondary calendar-icon-btn" type="button"
                                                    data-toggle="flatpickr">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <input type="hidden" id="birthday" name="birthday"
                                            value="{{ old('birthday', $birthday ?? '') }}">
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
                                            @foreach ($provinces as $_province)
                                                <option value="{{ $_province['province_code'] }}"
                                                    {{ old('province', $province ?? '') == $_province['province_code'] ? 'selected' : '' }}>
                                                    {{ $_province['name'] }}
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
                                            value="{{ old('street', $street ?? '') }}">
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="form-group mt-4 row">
                            <div class="col-md-10 offset-md-2">
                                <a href="{{ route('admin-tenants.index') }}" class="btn btn-secondary-custom lh-lg"><i
                                        class="fas fa-arrow-left me-2"></i> <span>{{ __('back') }}</span></a>
                                <button type="submit" class="btn btn-primary lh-lg"><i class="fas fa-save me-2"></i>
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
    <script src="{{ asset('assets/custom/js/helpers/show-password.js') }}"></script>
    <script type="module" src="{{ asset('assets/custom/js/admin-tenants/admin-tenant-form.js') }}"></script>

    <script type="module">
        import AdminTenantForm from '{{ asset('assets/custom/js/admin-tenants/admin-tenant-form.js') }}';

        $(function() {
            new AdminTenantForm(
                'admin-tenant-form',
                '{{ route('admin-tenants.index') }}',
                @json($provinces),
                'edit', {
                    province: "{{ old('province', $province ?? '') }}",
                    ward: "{{ old('ward', $ward ?? '') }}"
                }
            );
        });
    </script>
@endsection
