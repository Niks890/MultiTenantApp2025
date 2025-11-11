@extends('admin.master')
@section('title', __('item_detail', ['item' => strtolower(__('admin_tenant'))]))

@section('content')

    <div class="page-heading">
        <x-central.page-header title="" :breadcrumbs="[
            __('manage_item', ['item' => strtolower(__('admin_tenant'))]) => route('admin-tenants.index'),
            __('item_detail', ['item' => strtolower(__('admin_tenant'))]) => '',
        ]" />

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ __('item_detail', ['item' => strtolower(__('admin_tenant'))]) }}
                    </h4>
                </div>

                <hr class="border-dashed-form">

                <div class="card-body rounded p-3">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr class="border-top border-bottom">
                                    <th scope="row" class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal">
                                        {{ __('account_name') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">{{ $data['username'] }}
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row" class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal">
                                        {{ __('full_name') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">{{ $data['name'] }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row" class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal">
                                        {{ __('email') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">{{ $data['email'] }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row" class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal">
                                        {{ __('phone') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">{{ $data['phone'] }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row" class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal">
                                        {{ __('birthday') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">{{ $data['birthday'] }}
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row" class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal">
                                        {{ __('address') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">{{ $data['address'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal">
                                        {{ __('tenant') }}:
                                    </th>
                                    <td class="py-3 px-4">
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($data['tenants'] as $tenant)
                                                <span class="badge bg-primary rounded-pill">{{ $tenant }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 mb-3">
                            <button id="back-button" class="btn btn-secondary-custom lh-lg"
                                data-route="{{ route('admin-tenants.index') }}">
                                <i class="fas fa-arrow-left me-2"></i> {{ __('back') }}
                            </button>

                            <button id="edit-button" class="btn btn-success-custom lh-lg"
                                data-route="{{ route('admin-tenants.edit', $data['id'] ?? '#') }}">
                                <i class="fas fa-pen me-2"></i> {{ __('edit') }}
                            </button>

                            <form id="delete-form" action="{{ route('admin-tenants.destroy', $data['id'] ?? '#') }}"
                                method="POST" class="d-inline-block" data-name="{{ $data['name'] }}"
                                data-route="{{ route('admin-tenants.index') }}">
                                @csrf
                                @method('DELETE')
                                <button id="delete-btn" type="submit" class="btn btn-danger-custom lh-lg">
                                    <i class="fas fa-trash me-2"></i> {{ __('delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/input.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/admin-tenants.css') }}">
@endsection

@section('js')
    <script src="{{ asset('assets/custom/js/admin-tenants/admin-tenant-show.js') }}"></script>
@endsection
