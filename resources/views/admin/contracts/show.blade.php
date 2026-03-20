@extends('admin.master')
@section('title', 'Chi tiết hợp đồng')
@section('content')
    <x-central.page-header title="" :breadcrumbs="[
        'Quản lý hợp đồng' => route('contracts.index'),
        'Chi tiết hợp đồng' => 'javascript:void(0)',
    ]" />

    <div class="card shadow-sm">
        <div class="d-flex align-items-start justify-content-start flex-column flex-md-row pt-2 pb-4 row mt-3 ms-3">
            <div class="col-12 col-md-7">
                <p class="fw-bold text-break mb-0 text-center text-md-start header-title">
                    Xem chi tiết hợp đồng của cửa hiệu {{ $contract->tenant?->name ?? '—' }}
                </p>
            </div>
        </div>
        <hr class="border-dashed">

        <div class="card-body">
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="info-block">
                        <div class="info-row-item">
                            <span class="info-label">{{ __('Ngày tạo hợp đồng') }}</span>
                            <span class="info-value">{{ $contract->created_at?->format('d/m/Y') ?? '—' }}</span>
                        </div>
                        <div class="info-row-item">
                            <span class="info-label">{{ __('Tên cửa hiệu') }}</span>
                            <span class="info-value">{{ $contract->tenant?->name ?? '—' }}</span>
                        </div>
                        <div class="info-row-item">
                            <span class="info-label">{{ __('Chủ cửa hiệu') }}</span>
                            <span class="info-value">{{ $contract->tenant?->adminTenant?->display_name ?? '—' }}</span>
                        </div>
                        <div class="info-row-item">
                            <span class="info-label">{{ __('Gói đăng ký') }}</span>
                            <span class="info-value">{{ $contract->plan?->name ?? '—' }}</span>
                        </div>
                        <div class="info-row-item">
                            <span class="info-label">{{ __('Hình thức trả') }}</span>
                            <span class="info-value">{{ $contract->payment_mode ?? '—' }}</span>
                        </div>
                        <div class="info-row-item">
                            <span class="info-label">{{ __('Ngày bắt đầu') }}</span>
                            <span class="info-value">{{ $contract->start_at?->format('d/m/Y') ?? '—' }}</span>
                        </div>
                        <div class="info-row-item">
                            <span class="info-label">{{ __('Ngày kết thúc') }}</span>
                            <span class="info-value">{{ $contract->end_at?->format('d/m/Y') ?? '—' }}</span>
                        </div>
                        <div class="info-row-item">
                            <span class="info-label">{{ __('Chu kỳ') }}</span>
                            <span class="info-value">{{ $contract->plan?->cycle ?? '—' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="info-block">
                        <div class="info-row-item">
                            <span class="info-label">{{ __('Địa chỉ người thuê') }}</span>
                            <span class="info-value">{{ $contract->tenant?->address ?? '—' }}</span>
                        </div>
                        <div class="info-row-item">
                            <span class="info-label">{{ __('Số điện thoại người thuê') }}</span>
                            <span class="info-value">{{ $contract->tenant?->adminTenant?->phone_number ?? '—' }}</span>
                        </div>
                        <div class="info-row-item">
                            <span class="info-label">{{ __('Email người thuê') }}</span>
                            <span class="info-value">{{ $contract->tenant?->adminTenant?->email ?? '—' }}</span>
                        </div>
                        <div class="info-row-item">
                            <span class="info-label">{{ __('Trạng thái hợp đồng') }}</span>
                            <span class="info-value">
                                @php
                                    $statusMap = [
                                        1 => ['label' => __('paid'), 'class' => 'bg-success'],
                                        2 => ['label' => __('overdue'), 'class' => 'bg-warning'],
                                        3 => ['label' => __('unpaid'), 'class' => 'bg-danger'],
                                        4 => ['label' => __('expired'), 'class' => 'bg-secondary'],
                                        5 => ['label' => __('tenant_deleted'), 'class' => 'bg-dark'],
                                    ];
                                    $statusInfo = $statusMap[(int) $contract->status] ?? [
                                        'label' => '—',
                                        'class' => 'badge-secondary',
                                    ];
                                @endphp
                                <span class="badge {{ $statusInfo['class'] }}">
                                    {{ $statusInfo['label'] }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="summary-card">
                                <div class="summary-label">{{ __('Tổng tiền trước thuế') }}</div>
                                <div class="summary-value">
                                    {{ number_format($contract->amount_before_tax, 0, ',', '.') }} ₫
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="summary-card">
                                <div class="summary-label">{{ __('Thuế VAT') }} ({{ $contract->tax->rate }}%)</div>
                                <div class="summary-value">
                                    {{ number_format($contract->tax_amount, 0, ',', '.') }} ₫
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="summary-card">
                                <div class="summary-label">{{ __('Tổng cộng các khoản phải đóng') }}</div>
                                <div class="summary-value">
                                    {{ number_format($contract->amount_after_tax, 0, ',', '.') }} ₫
                                </div>
                            </div>
                        </div>
                        <div id="summaryPaymentWrapper">
                            <div class="row gap-2">
                                <div class="col-12">
                                    <div class="summary-card">
                                        <div class="summary-label">{{ __('Tổng cộng đã đóng') }}</div>
                                        <div class="summary-value">
                                            {{ number_format($contract->total_paid, 0, ',', '.') }} ₫
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-1">
                                    <div class="summary-card summary-card--danger">
                                        <div class="summary-label">{{ __('Số tiền còn nợ') }}</div>
                                        <div class="summary-value text-danger">
                                            {{ number_format($contract->amount_after_tax - $contract->total_paid, 0, ',', '.') }}
                                            ₫
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-dashed my-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <p class="fw-bold mb-0">{{ __('Danh sách các lần thanh toán') }}</p>
                @if ($contract->total_paid < $contract->amount_after_tax)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modalAddTransaction">
                        <i class="fas fa-plus"></i> {{ __('Thêm giao dịch') }}
                    </button>
                @else
                    <button type="button" class="btn btn-success" disabled>
                        <i class="fas fa-check-circle"></i> {{ __('Đã thanh toán đủ') }}
                    </button>
                @endif
            </div>

            <div class="table-responsive" id="paymentTableWrapper">
                @include('admin.contracts.partials.payment-table', [
                    'transactions' => $transactions,
                ])
            </div>
            <div id="paymentPaginationWrapper">
                @include('admin.contracts.partials.payment-pagination', [
                    'transactions' => $transactions,
                ])
            </div>
        </div>
    </div>

    @include('admin.contracts.partials.modal-add-transaction')
    @include('admin.contracts.partials.modal-edit-transaction')
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/table.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <style>
        .info-block {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .info-row-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .info-label {
            font-size: 12px;
            color: #6c757d;
        }

        .info-value {
            font-size: 13px;
            font-weight: 500;
            color: var(--bs-body-color);
        }

        .summary-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px 14px;
            border: 0.5px solid #e9ecef;
        }

        .summary-card--danger {
            border-color: #f5c2c7;
            background: #fff5f5;
        }

        .summary-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 2px;
        }

        .summary-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--bs-body-color);
        }

        .upload-area {
            border: 1.5px dashed #ced4da;
            border-radius: 8px;
            cursor: pointer;
            min-height: 140px;
            background: #f8f9fa;
            transition: border-color 0.2s, background 0.2s;
        }

        .upload-area:hover {
            border-color: #0d6efd;
            background: #f0f5ff;
        }

        .choices.is-invalid .choices__inner {
            border-color: #dc3545;
        }


        .invalid-feedback.d-block {
            display: block !important;
        }


        .flatpickr-calendar {
            z-index: 9999 !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            padding: 2px !important;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        const confirmDeleteBtnLabel = @json(__('delete_confirm'));
        const deleteBtnLabel = @json(__('delete'));
        const cancelBtnLabel = @json(__('cancel'));
        const storeTransactionUrl = "{{ route('transaction.store') }}";
        const updateTransactionUrl = "{{ route('transaction.update', ':id') }}";
        const TRANSLATIONS = {
            saving: "{{ __('Đang lưu...') }}",
            save: "{{ __('Lưu') }}",
            success_add: "{{ __('Thêm giao dịch thành công.') }}",
            success_update: "{{ __('Cập nhật giao dịch thành công.') }}",
            error_general: "{{ __('Đã có lỗi xảy ra. Vui lòng thử lại.') }}",
            error_file_select: "{{ __('Vui lòng giữ ảnh cũ hoặc tải lên ảnh mới.') }}",
            error_invoice: "{{ __('Vui lòng tải lên ảnh hóa đơn.') }}",
            error_format: "{{ __('Chỉ chấp nhận ảnh định dạng JPG, PNG, WEBP.') }}",
            error_size: "{{ __('Ảnh tải lên có kích thước tối đa không quá :size MB.') }}"
        };
    </script>
    <script src="{{ asset('assets/custom/js/transaction/validate.js') }}"></script>
    <script src="{{ asset('assets/custom/js/transaction/delete.js') }}"></script>
    <script>
        $(document).on('transaction:deleted', function() {
            reloadContractPartials();
        });
    </script>
@endsection
