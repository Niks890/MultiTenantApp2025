@extends('admin.master')
@section('title', __('create_item', ['item' => strtolower(__('plan'))]))

@section('content')

    <div class="page-heading">
        <x-central.page-header title="" :breadcrumbs="[
            __('manage_item', ['item' => strtolower(__('plan'))]) => route('plans.index'),
            __('create') => '',
        ]" />
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('create_item', ['item' => strtolower(__('plan'))]) }}</h4>
                </div>

                <hr class="border-dashed-form">

                <div class="card-body">
                    <form id="plans-form" action="{{ route('plans.store') }}" method="POST" enctype="multipart/form-data"
                        novalidate>
                        @csrf
                        <div class="row">

                            <!-- name -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="name" class="align-middle">{{ __('plan_name') }}: <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" id="name" class="form-control" name="name"
                                            placeholder="{{ __('enter', ['item' => strtolower(__('plan_name'))]) }}"
                                            value="{{ old('name') }}">
                                    </div>

                                </div>
                            </div>

                            <!-- price -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="price">{{ __('plan_price') }}: <span
                                                class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-10">
                                        <div class="input-group flex-nowrap">
                                            <input type="text" id="price"
                                                class="form-control input-with-button-right" name="price-display"
                                                placeholder="{{ __('enter', ['item' => strtolower(__('plan_price'))]) }}"
                                                value="{{ old('price') }}">
                                            <button class="btn btn-outline-secondary currency-icon-btn"
                                                id="price-currency-btn" type="button">
                                                <i class="fa-solid fa-dong-sign"></i>
                                            </button>
                                        </div>
                                        <input type="hidden" id="price-value" name="price" value="{{ old('price') }}">
                                    </div>

                                    <div class="col-md-10 offset-md-2 d-none">
                                        <small id="price-in-words" class="form-text">
                                            {{ __('not_entered') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- cycle -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="cycle">{{ __('cycle') }}: <span
                                                class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-10">
                                        <select id="cycle" name="cycle" class="form-select">
                                            @foreach (['weekly' => __('week'), 'monthly' => __('month'), 'yearly' => __('year')] as $value => $label)
                                                <option value="{{ $value }}"
                                                    {{ old('cycle') == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div>
                            </div>

                            <!-- description -->
                            <div class="form-group">
                                <div class="row align-items-center">

                                    <div class="col-md-2">
                                        <label for="description">{{ __('description') }}:</label>
                                    </div>

                                    <div class="col-md-10">
                                        <textarea id="description" name="description" class="form-control" rows="5"
                                            placeholder="{{ __('enter', ['item' => strtolower(__('description'))]) }}">{{ old('description') }}</textarea>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="form-group mt-4 row">
                            <div class="col-md-10 offset-md-2">
                                <a href="{{ route('plans.index') }}" class="btn btn-secondary-custom lh-lg"><i
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

    <script type="module" src="{{ asset('assets/custom/js/plans/plansForm.js') }}"></script>

    <script type="module">
        import PlansForm from '{{ asset('assets/custom/js/plans/plansForm.js') }}';

        $(function() {
            new PlansForm('plans-form', '{{ route('plans.index') }}', 'create');
        });
    </script>
@endsection
