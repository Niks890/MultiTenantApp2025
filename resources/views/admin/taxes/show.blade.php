@extends('admin.master')
@section('title', __('item_detail', ['item' => strtolower(__('tax'))]))

@section('content')

    <div class="page-heading">
        <x-central.page-header title="" :breadcrumbs="[
            __('manage_item', ['item' => strtolower(__('tax'))]) => route('taxes.index'),
            __('detail') => '',
        ]" />

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ __('item_detail', ['item' => strtolower(__('tax'))]) }}
                    </h4>
                </div>

                <hr class="border-dashed-form">

                <div class="card-body rounded p-3">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr class="border-bottom">
                                    <th scope="row"
                                        class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal align-middle">
                                        {{ __('tax_rate') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                        {{ number_format($tax['rate'], 0, '.', ',') }} %
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row"
                                        class="py-3 px-4 w-25 border-end text-end text-nowrap fw-normal align-middle">
                                        {{ __('status') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                        {{ $tax['is_active'] ? __('active') : __('inactive') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 mb-3">
                            <button id="back-button" class="btn btn-secondary-custom lh-lg"
                                data-route="{{ route('taxes.index') }}">
                                <i class="fas fa-arrow-left me-2"></i> {{ __('back') }}
                            </button>

                            <button id="edit-button" class="btn btn-success-custom lh-lg"
                                data-route="{{ route('taxes.edit', $tax['id'] ?? '#') }}">
                                <i class="fas fa-pen me-2"></i> {{ __('edit') }}
                            </button>

                            <form id="delete-form" action="{{ route('taxes.destroy', $tax['id'] ?? '#') }}" method="POST"
                                class="d-inline-block" data-route="{{ route('taxes.index') }}">
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
@endsection

@section('js')
    <script src="{{ asset('assets/custom/js/taxes/taxesShow.js') }}"></script>
@endsection
