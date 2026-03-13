<div class="modal fade" id="modalAddTransaction" tabindex="-1" aria-labelledby="modalAddTransactionLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalAddTransactionLabel">
                    {{ __('Thêm mới lần thanh toán') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formAddTransaction" novalidate enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="contract_id" value="{{ $contract->id }}">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label">
                                    {{ __('Số tiền đã thanh toán') }}:
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="amount" name="amount" min="1"
                                    class="form-control" placeholder="VD: 500000" required>
                                <div class="invalid-feedback">{{ __('Vui lòng nhập số tiền.') }}</div>
                            </div>

                            <div class="mb-3">
                                <label for="payment_date" class="form-label">
                                    {{ __('Ngày thanh toán') }}:
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" id="payment_date" name="payment_date"
                                    class="form-control" required>
                                <div class="invalid-feedback">{{ __('Vui lòng chọn ngày thanh toán.') }}</div>
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label">
                                    {{ __('Phương thức thanh toán') }}:
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="payment_method" name="payment_method" class="form-select" required>
                                    <option value="">-- {{ __('Chọn phương thức') }} --</option>
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">{{ __('Vui lòng chọn phương thức thanh toán.') }}</div>
                            </div>

                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('Ảnh hoá đơn giao dịch') }}:</label>
                            <div id="uploadArea"
                                class="upload-area d-flex flex-column align-items-center justify-content-center text-center p-4"
                                onclick="document.getElementById('file_path').click()">
                                <i class="fa fa-cloud-upload fa-2x text-muted mb-2"></i>
                                <span class="text-muted small">{{ __('Nhấn để tải ảnh lên') }}</span>
                                <span class="text-muted" style="font-size:11px;">JPG, PNG, WEBP — tối đa 2MB</span>
                            </div>
                            <input type="file" id="file_path" name="file_path"
                                accept="image/jpeg,image/png,image/webp" class="d-none">
                            <div id="imagePreviewWrapper" class="mt-2 d-none">
                                <img id="imagePreview" src="" alt="preview"
                                    class="img-thumbnail" style="max-height: 160px; object-fit: cover; width: 100%;">
                                <button type="button" id="btnRemoveImage"
                                    class="btn btn-sm btn-outline-danger mt-1 w-100">
                                    <i class="fa fa-trash me-1"></i>{{ __('Xoá ảnh') }}
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-arrow-left me-1"></i>{{ __('Bỏ qua') }}
                    </button>
                    <button type="submit" class="btn btn-success" id="btnSaveTransaction">
                        <i class="fa fa-save me-1"></i>{{ __('Lưu') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
