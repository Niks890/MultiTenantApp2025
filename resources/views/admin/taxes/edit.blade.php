@extends('admin.master')
@section('title', __('edit_item', ['item' => strtolower(__('tax'))]))

@section('content')

    <div class="page-heading">
        <x-central.page-header title="" :breadcrumbs="[
            __('manage_item', ['item' => strtolower(__('tax'))]) => route('taxes.index'),
            __('edit') => '',
        ]" />
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('edit_item', ['item' => strtolower(__('tax'))]) }}</h4>
                </div>

                <hr class="border-dashed-form">

                <div class="card-body">
                    <form id="taxes-form" action="{{ route('taxes.update', $tax->id) }}" method="POST"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row">

                            <!-- rate -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="rate">{{ __('tax_rate') }}: <span
                                                class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-10">
                                        <div class="input-group flex-nowrap">
                                            <input type="text" id="rate"
                                                class="form-control input-with-button-right" name="rate"
                                                placeholder="{{ __('enter', ['item' => strtolower(__('tax_rate'))]) }}"
                                                value="{{ old('rate', $tax->rate) }}">
                                            <button class="btn btn-outline-secondary currency-icon-btn"
                                                id="rate-currency-btn" type="button">
                                                <i class="fas fa-percent"></i>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="form-group mt-4 row">
                            <div class="col-md-10 offset-md-2">
                                <a href="{{ route('taxes.index') }}" class="btn btn-secondary-custom lh-lg"><i
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
    <link rel="stylesheet" href="{{ asset('assets/custom/css/input.css') }}">
@endsection

@section('js')
    <script src="{{ asset('assets/extensions/autonumeric-4.10.9/dist/autoNumeric.min.js') }}"></script>

    <script>
        // translations
        const notEnteredText = "{{ __('not_entered') }}";
    </script>

    <script type="module" src="{{ asset('assets/custom/js/taxes/taxesForm.js') }}"></script>

    <script type="module">
        import TaxesForm from '{{ asset('assets/custom/js/taxes/taxesForm.js') }}';

        $(function() {
            new TaxesForm('taxes-form', '{{ route('taxes.index') }}', 'edit');
        });
    </script>
@endsection
