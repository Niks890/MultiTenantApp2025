@extends('admin.master')
@section('title', 'Chỉnh sửa cửa hiệu')
@section('content')
    <x-central.page-header :breadcrumbs="[
        'Quản lý cửa hiệu' => route('tenant.index'),
        __('edit') => route('tenant.edit', $tenant->id),
    ]" />
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Chỉnh sửa cửa hiệu</h4>
            </div>
            <hr class="border-dashed-form">
            <div class="card-body">
                <form action="{{ route('tenant.update', $tenant->id) }}" method="POST" id="editTenantForm" novalidate
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_name" class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('tenant_name') }}: <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="tenancy_name" id="tenancy_name"
                                        placeholder="Nhập tên cửa hiệu" class="form-control"
                                        value="{{ old('tenancy_name', $tenant->name) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_db_name"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('database_name') }}: <span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="tenancy_db_name" id="tenancy_db_name"
                                        placeholder="Nhập tên cơ sở dữ liệu" class="form-control"
                                        value="{{ old('tenancy_db_name', $tenant->tenancy_db_name) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_db_connection"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    Kết nối cơ sở dữ liệu:<span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    @php
                                        $allConnections = config('database.connections');
                                        $mysqlConnections = [];
                                        foreach ($allConnections as $name => $details) {
                                            if (isset($details['driver']) && $details['driver'] === 'mysql') {
                                                $mysqlConnections[$name] = [
                                                    'host' => config("database.connections.{$name}.host"),
                                                    'port' => config("database.connections.{$name}.port"),
                                                ];
                                            }
                                        }
                                    @endphp
                                    <select name="tenancy_db_connection" id="tenancy_db_connection"
                                        class="form-control form-select" required>
                                        @foreach ($mysqlConnections as $name => $details)
                                            <option value="{{ $name }}"
                                                {{ old('tenancy_db_connection', $tenant->tenancy_db_connection) == $name ? 'selected' : '' }}
                                                data-host="{{ $details['host'] ?? '' }}"
                                                data-port="{{ $details['port'] ?? '' }}">
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_db_host"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('database_host') }}:<span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="tenancy_db_host" id="tenancy_db_host"
                                        placeholder="Nhập host cơ sở dữ liệu" class="form-control"
                                        value="{{ old('tenancy_db_host', $tenant->tenancy_db_host) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_db_port"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('port') }}:<span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="number" name="tenancy_db_port" id="tenancy_db_port"
                                        placeholder="Nhập cổng kết nối" class="form-control"
                                        value="{{ old('tenancy_db_port', $tenant->tenancy_db_port) }}" min="1"
                                        max="65535" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_db_username"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('username_database') }}:<span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="tenancy_db_username" id="tenancy_db_username"
                                        placeholder="Nhập tên tài khoản cơ sở dữ liệu" class="form-control"
                                        value="{{ old('tenancy_db_username', $tenant->tenancy_db_username) }} " required
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_db_password"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('password') }}:
                                </label>
                                <div class="col-12 col-lg-10">
                                    <div class="input-group flex-nowrap">
                                        <div class="input-container position-relative flex-grow-1">
                                            <input type="text" id="tenancy_db_password"
                                                class="form-control input-with-icon-inside input-with-button-right password-input"
                                                name="tenancy_db_password" placeholder="Nhập mật khẩu"
                                                value="{{ old('tenancy_db_password') ?? '' }}" required autocomplete="off">
                                        </div>

                                        <button class="btn btn-outline-secondary show-pass-btn" type="button"
                                            data-target="#tenancy_db_password">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">Chỉ nhập nếu muốn thay đổi mật khẩu</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_db_password_confirm"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('password_confirmation') }}:
                                </label>
                                <div class="col-12 col-lg-10">
                                    <div class="input-group flex-nowrap">
                                        <div class="input-container position-relative flex-grow-1">
                                            <input type="text" id="tenancy_db_password_confirm"
                                                class="form-control input-with-icon-inside input-with-button-right password-input"
                                                name="tenancy_db_password_confirm" placeholder="Nhập mật khẩu xác nhận"
                                                value="{{ old('tenancy_db_password_confirm') ?? '' }}" required
                                                autocomplete="off">
                                        </div>

                                        <button class="btn btn-outline-secondary show-pass-btn" type="button"
                                            data-target="#tenancy_db_password_confirm">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_access_key"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('access_key') }}:
                                </label>
                                <div class="col-12 col-lg-10">
                                    <div class="input-group flex-nowrap">
                                        <div class="input-container position-relative flex-grow-1">
                                            <input type="text" id="tenancy_access_key"
                                                class="form-control input-with-icon-inside input-with-button-right password-input"
                                                name="tenancy_access_key" placeholder="Nhập access key"
                                                value="{{ old('tenancy_access_key', $tenant->access_key) }}" required
                                                autocomplete="off">
                                        </div>

                                        <button class="btn btn-outline-secondary show-pass-btn" type="button"
                                            data-target="#tenancy_access_key">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_hash_code"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('hash_code') }}:
                                </label>
                                <div class="col-12 col-lg-10">
                                    <div class="input-group flex-nowrap">
                                        <div class="input-container position-relative flex-grow-1">
                                            <input type="text" id="tenancy_hash_code"
                                                class="form-control input-with-icon-inside input-with-button-right password-input"
                                                name="tenancy_hash_code" placeholder="Nhập hash code"
                                                value="{{ old('tenancy_hash_code', $tenant->hash_code) }}" required
                                                autocomplete="off">
                                        </div>

                                        <button class="btn btn-outline-secondary show-pass-btn" type="button"
                                            data-target="#tenancy_hash_code">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_is_active"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    Trạng thái cửa hiệu:<span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">
                                    <select name="tenancy_is_active" id="tenancy_is_active"
                                        class="form-control form-select">
                                        <option value="1"
                                            {{ old('tenancy_is_active', $tenant->is_active) == 1 ? 'selected' : '' }}>
                                            Hoạt động</option>
                                        <option value="0"
                                            {{ old('tenancy_is_active', $tenant->is_active) == 0 ? 'selected' : '' }}>Bảo
                                            trì</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @php
                            use Illuminate\Support\Str;
                            $domain = $tenant->domains->first()?->domain;
                            $shortDomain = '';
                            if ($domain) {
                                $appHost = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
                                $domain = trim($domain, '.');
                                while (Str::endsWith($domain, '.' . $appHost)) {
                                    $domain = Str::replaceLast('.' . $appHost, '', $domain);
                                }
                                $shortDomain = $domain;
                            }
                        @endphp
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_domain"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('domain') }}:<span class="text-danger">*</span>
                                </label>
                                <div class="col-12 col-lg-10">

                                    <input type="text" name="tenancy_domain" id="tenancy_domain"
                                        placeholder="Nhập tên miền" class="form-control"
                                        value="{{ old('tenancy_domain', $shortDomain) }}" required>
                                    <small id="domainPreview" class="form-text text-muted"></small>
                                </div>
                            </div>
                        </div>


                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_group"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('tenant_group') }}:
                                </label>
                                <div class="col-12 col-lg-10">
                                    <select name="tenancy_group" id="tenancy_group" class="form-control form-select">
                                        <option value="">Không phân nhóm</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}"
                                                {{ old('tenancy_group', $tenant->group?->id) == $group->id ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_admin"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('admin_tenant_username') }}:
                                </label>
                                <div class="col-12 col-lg-10">
                                    <select name="tenancy_admin" id="tenancy_admin" class="form-control form-select">
                                        <option value="">Không có tài khoản quản trị</option>
                                        @foreach ($adminTenants as $admin)
                                            <option value="{{ $admin->id }}"
                                                {{ old('tenancy_admin', $tenant->admin_tenant_id) == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->username }} ({{ $admin->display_name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group" id="tenant_group_box" style="display:none;">
                            <div class="row align-items-start">
                                <label class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    Cửa hiệu đã có:
                                </label>
                                <div class="col-12 col-lg-10">
                                    <div id="tenant_list" class="border rounded p-3 bg-light"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group" id="copy_from_box" style="display:none;">
                            <div class="row align-items-start">
                                <label class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    Sao chép cửa hiệu:
                                </label>
                                <div class="col-12 col-lg-10 d-flex flex-column gap-2">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="enable_copy_info">
                                        <label class="form-check-label" for="enable_copy_info">
                                            <p>Sao chép thông tin từ cửa hiệu khác</p>
                                        </label>
                                    </div>
                                    <select id="copy_tenant_select" class="form-control" disabled>
                                        <option value="">Chọn cửa hiệu để sao chép</option>
                                    </select>
                                    <small class="form-text text-muted">Danh sách các cửa hiệu hiện có của tài khoản quản
                                        trị cửa hiệu.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="edit_tenancy_logo"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('tenant_logo') }}:
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="file" accept="image/*" name="tenancy_logo" id="tenancy_logo"
                                        data-cropper-target="editTenantForm" data-preview-target="#logoPreview"
                                        data-hidden-input="cropped_logo" class="form-control"
                                        data-existing-logo="{{ $tenant->logo ? asset('storage/' . $tenant->logo) : '' }}"
                                        title="Tải lên logo cửa hiệu">
                                    <div class="logo-preview mt-2">
                                        <img id="logoPreview"
                                            style="max-width: 100px; max-height: 100px; border-radius: 8px; display: none;">
                                    </div>
                                    <div id="logo_preview" class="mt-2">
                                        @if ($tenant->logo)
                                            <div class="fade-in d-inline-block position-relative">
                                                <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo hiện tại"
                                                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 2px solid #007bff; padding: 3px; background: #fff;">
                                                <div class="mt-1 text-center">
                                                    <small class="text-muted"><i class="fas fa-image me-1"></i>Logo hiện
                                                        tại</small>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <input type="hidden" name="copy_from_tenant_id" id="copy_from_tenant_id">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_fb_url"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('fb_url') }}:
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="url" name="tenancy_fb_url" id="tenancy_fb_url"
                                        placeholder="Nhập liên kết facebook" class="form-control"
                                        value="{{ old('tenancy_fb_url', $tenant->facebook_url ?? '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_tiktok_url"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('tiktok_url') }}:
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="tenancy_tiktok_url" id="tenancy_tiktok_url"
                                        placeholder="Nhập liên kết tiktok" class="form-control"
                                        value="{{ old('tenancy_tiktok_url', $tenant->tiktok_url ?? '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_ig_url"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('ig_url') }}:
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="tenancy_ig_url" id="tenancy_ig_url"
                                        placeholder="Nhập liên kết instagram" class="form-control"
                                        value="{{ old('tenancy_ig_url', $tenant->instagram_url ?? '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-center">

                                <div class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    <label for="province">{{ __('province') }}: </label>
                                </div>

                                <div class="col-12 col-lg-10">
                                    <select id="province" name="province" class="choices-select form-select">
                                        <option value="">{{ __('select_item', ['item' => __('province')]) }}
                                        </option>
                                        @foreach ($provinces as $_province)
                                            <option value="{{ $_province['province_code'] }}"
                                                {{ old('province', $tenant->province ?? '') == $_province['province_code'] ? 'selected' : '' }}>
                                                {{ $_province['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-center">

                                <div class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    <label for="ward">{{ __('ward') }}: </label>
                                </div>

                                <div class="col-md-10">
                                    <select id="ward" name="ward" class="choices-select form-select">
                                        {{-- Will be populated dynamically based on selected province --}}
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 form-group custom-form-group">
                            <div class="row align-items-start">
                                <label for="tenancy_address"
                                    class="col-12 col-lg-2 col-form-label custom-label text-nowrap">
                                    {{ __('street') }}:
                                </label>
                                <div class="col-12 col-lg-10">
                                    <input type="text" name="tenancy_address" id="tenancy_address"
                                        placeholder="{{ __('enter', ['item' => strtolower(__('street'))]) }}"
                                        class="form-control"
                                        value="{{ old('tenancy_address', trim($tenant->street) ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-10 offset-lg-2 d-flex gap-2 custom-action-col">
                            <a href="{{ route('tenant.index') }}" class="btn btn-light">
                                <i class="bi bi-arrow-left me-1"></i>
                                <span>{{ __('back') }}</span>
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-1"></i>
                                <span>{{ __('save') }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    @include('admin.tenant.partials.modal-crop-logo')
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/group-responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/modal.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/extensions/choices.js@11.1.0/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/choices.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/tenant.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/croppie-2.6.4/croppie.css') }}">
@endsection
@section('js')
    <script>
        const tenantIndexUrl = "{{ route('tenant.index') }}";
        const displayDomain = @json('.' . parse_url(config('app.url') ?? '', PHP_URL_HOST));
        const locationDataFromPHP = @json($provinces);
        const originalData = {
            province: '{{ old('province', $tenant->province ?? '') }}',
            ward: '{{ old('ward', $tenant->ward ?? '') }}'
        };
        const translationsBetter = {
            noDataFoundMessage: "{{ __('noDataFoundMessage') }}",
            pressToSelect: "{{ __('pressToSelect') }}",
            please_select: "{{ __('please_select') }}",
            select_item: "{{ __('select_item') }}",
            province: "{{ __('province') }}",
            ward: "{{ __('ward') }}"
        };
    </script>
    <script src="{{ asset('assets/extensions/choices.js@11.1.0/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/croppie-2.6.4/croppie.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/form-element-select.js') }}"></script>
    <script src="{{ asset('assets/custom/js/helpers/locationSelector.js') }}"></script>
    <script type="module" src="{{ asset('assets/custom/js/tenant/tenant-validate.js') }}"></script>
    <script src="{{ asset('assets/custom/js/helpers/show-password.js') }}"></script>
    <script>
        const tenantStatusChoices = new Choices('select[name="tenancy_is_active"]', {
            itemSelectText: 'Nhấn để chọn',
            allowHTML: false,
            placeholderValue: 'Chọn trạng thái cửa hiệu',
            shouldSort: false,
        });
    </script>
@endsection
