@extends('admin.master')
@section('title', __('item_detail', ['item' => strtolower(__('plan'))]))

@section('content')

    <div class="page-heading">
        <x-central.page-header title="" :breadcrumbs="[
            __('manage_item', ['item' => strtolower(__('plan'))]) => route('plans.index'),
            __('detail') => '',
        ]" />

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ __('item_detail', ['item' => strtolower(__('plan'))]) }}
                    </h4>
                </div>

                <hr class="border-dashed-form">

                <div class="card-body rounded p-3">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr class="border-top border-bottom">
                                    <th scope="row"
                                        class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal align-middle">
                                        {{ __('plan_name') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">{{ $plan['name'] }}
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row"
                                        class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal align-middle">
                                        {{ __('plan_price') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                        {{ number_format($plan['price'], 0, '.', ',') }} đ
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row"
                                        class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal align-middle">
                                        {{ __('cycle') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                        @if ($plan['cycle'] == 'weekly')
                                            {{ __('week') }}
                                        @elseif ($plan['cycle'] == 'monthly')
                                            {{ __('month') }}
                                        @elseif ($plan['cycle'] == 'yearly')
                                            {{ __('year') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row"
                                        class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal align-middle">
                                        {{ __('description') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                        <span class="pre-wrap-text">{{ $plan['description'] }}</span>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row"
                                        class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal align-middle">
                                        {{ __('status') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                        {{ $plan['is_active'] ? __('active') : __('inactive') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 mb-3">
                            <button id="back-button" class="btn btn-secondary-custom lh-lg"
                                data-route="{{ route('plans.index') }}">
                                <i class="fas fa-arrow-left me-2"></i> {{ __('back') }}
                            </button>

                            <button id="edit-button" class="btn btn-success-custom lh-lg"
                                data-route="{{ route('plans.edit', $plan['id'] ?? '#') }}">
                                <i class="fas fa-pen me-2"></i> {{ __('edit') }}
                            </button>

                            @can('delete-plan', $plan)
                                <form id="delete-form" action="{{ route('plans.destroy', $plan['id'] ?? '#') }}" method="POST"
                                    class="d-inline-block" data-name="{{ $plan['name'] }}"
                                    data-route="{{ route('plans.index') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button id="delete-btn" type="submit" class="btn btn-danger-custom lh-lg">
                                        <i class="fas fa-trash me-2"></i> {{ __('delete') }}
                                    </button>
                                </form>
                            @endcan

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/input.css') }}">
@endsection

@section('js')
    <script src="{{ asset('assets/custom/js/plans/plansShow.js') }}"></script>
@endsection
