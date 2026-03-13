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
                    Xem chi tiết hợp đồng của cửa hiệu
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
                        <div class="col-12">
                            <div class="summary-card">
                                <div class="summary-label">{{ __('Tổng cộng đã đóng') }}</div>
                                <div class="summary-value">
                                    {{ number_format($contract->total_paid, 0, ',', '.') }} ₫
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="summary-card summary-card--danger">
                                <div class="summary-label">{{ __('Số tiền còn nợ') }}</div>
                                <div class="summary-value text-danger">
                                    {{ number_format($contract->amount_after_tax - $contract->total_paid, 0, ',', '.') }} ₫
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-dashed my-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <p class="fw-bold mb-0">{{ __('Danh sách các lần thanh toán') }}</p>
                <button type="button" class="btn btn-success btn-sm" id="btnAddPayment">
                    <i class="fa fa-plus me-1"></i>{{ __('Thêm mới') }}
                </button>
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
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/table.css') }}">
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
    </style>
@endsection

@section('js')
    <script>
        const storeTransactionUrl = "{{ route('transaction.store') }}";

        // Mở modal
        $('#btnAddPayment').on('click', function() {
            const form = $('#formAddTransaction')[0];
            form.reset();
            $('#formAddTransaction').removeClass('was-validated');

            // Đã xóa reset choices

            $('#file_path').val('');
            $('#imagePreview').attr('src', '');
            $('#imagePreviewWrapper').addClass('d-none');
            $('#uploadArea').removeClass('d-none');
            $('#modalAddTransaction').modal('show');
        });

        // Reset khi đóng modal
        $('#modalAddTransaction').on('hidden.bs.modal', function() {
            const form = $('#formAddTransaction')[0];
            form.reset();
            $('#formAddTransaction').removeClass('was-validated');
            $('#file_path').val('');
            $('#imagePreview').attr('src', '');
            $('#imagePreviewWrapper').addClass('d-none');
            $('#uploadArea').removeClass('d-none');
        });

        // Preview ảnh (giữ nguyên)
        $('#file_path').on('change', function() {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#imagePreviewWrapper').removeClass('d-none');
                $('#uploadArea').addClass('d-none');
            };
            reader.readAsDataURL(file);
        });

        $('#btnRemoveImage').on('click', function() {
            $('#file_path').val('');
            $('#imagePreview').attr('src', '');
            $('#imagePreviewWrapper').addClass('d-none');
            $('#uploadArea').removeClass('d-none');
        });

        $('#formAddTransaction').on('submit', function(e) {
            e.preventDefault();
            if (!this.checkValidity()) {
                $(this).addClass('was-validated');
                return;
            }

            const formData = new FormData(this);
            const $btn = $('#btnSaveTransaction');

            $btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span>{{ __('Đang lưu...') }}'
            );

            $.ajax({
                url: storeTransactionUrl,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    $('#modalAddTransaction').modal('hide');
                    $.get(window.location.href, function(html) {
                        const $html = $(html);
                        $('#paymentTableWrapper').html($html.find('#paymentTableWrapper').html());
                        $('#paymentPaginationWrapper').html($html.find('#paymentPaginationWrapper').html());
                    });
                    showToast('success', res.message ?? '{{ __('Thêm giao dịch thành công.') }}');
                    window.location.reload();
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        Object.values(errors).flat().forEach(msg => toastr.error(msg));
                    } else {
                        showToast('error', '{{ __('Đã có lỗi xảy ra. Vui lòng thử lại.') }}');
                    }
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i>{{ __('Lưu') }}');
                }
            });
        });
    </script>
@endsection
