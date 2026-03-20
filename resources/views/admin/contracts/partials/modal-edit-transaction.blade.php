<div class="modal fade" id="modalEditTransaction" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-edit me-2 text-primary"></i>{{ __('Sửa lần thanh toán') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditTransaction" novalidate>
                @csrf
                <input type="hidden" name="transaction_id" id="edit_transaction_id">
                <input type="hidden" name="amount" id="edit_amount_raw">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                {{ __('Số tiền đã thanh toán:') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="edit_amount_display"
                                    inputmode="numeric" required>
                                <span class="input-group-text">₫</span>
                                <div class="invalid-feedback">{{ __('Vui lòng nhập số tiền.') }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                {{ __('Ngày thanh toán:') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="edit_paid_at_display"
                                placeholder="dd/mm/yyyy" readonly style="background:#fff; cursor:pointer;">
                            <input type="hidden" name="payment_date" id="edit_paid_at_raw">
                            <div id="edit_payment_date_error" class="invalid-feedback">
                                {{ __('Vui lòng chọn ngày thanh toán.') }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                {{ __('Phương thức thanh toán:') }}: <span class="text-danger">*</span>
                            </label>
                            <select id="edit_payment_method" name="payment_method" class="form-select" required>
                                <option value="">-- {{ __('Chọn phương thức') }} --</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                @endforeach
                            </select>
                            <div id="edit_payment_method_error" class="invalid-feedback" style="margin-top: -20px;">
                                {{ __('Vui lòng chọn phương thức thanh toán.') }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">{{ __('Ảnh hoá đơn giao dịch:') }} <span class="text-danger">*</span></label>
                            <div id="editCurrentImageWrapper" class="mb-2 d-none">
                                <p class="text-muted small mb-1">{{ __('Ảnh hiện tại:') }}</p>
                                <div class="position-relative d-inline-block">
                                    <img id="editCurrentImage" src="" alt="current"
                                        class="img-thumbnail" style="max-height:120px;">
                                    <button type="button" id="btnRemoveCurrentImage"
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                        style="transform:translate(40%,-40%);border-radius:50%;width:22px;height:22px;padding:0;font-size:11px;line-height:1;">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="keep_file" id="edit_keep_file" value="1">
                            </div>
                            <div id="editUploadArea"
                                class="upload-area d-flex flex-column align-items-center justify-content-center p-3 d-none"
                                onclick="$('#edit_file_path').click()">
                                <i class="fa fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                <span class="text-muted small">{{ __('Nhấn để chọn ảnh mới') }}</span>
                            </div>
                            <div id="editImagePreviewWrapper" class="d-none">
                                <div class="position-relative d-inline-block">
                                    <img id="editImagePreview" src="" alt="preview"
                                        class="img-thumbnail" style="max-height:120px;">
                                    <button type="button" id="btnRemoveEditImage"
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                        style="transform:translate(40%,-40%);border-radius:50%;width:22px;height:22px;padding:0;font-size:11px;line-height:1;">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="file" id="edit_file_path" name="file_path" accept="image/*" class="d-none">
                            <div id="edit_file_path_error" class="invalid-feedback"></div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Đóng') }}
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSaveEditTransaction">
                        <i class="fa fa-save me-1"></i>{{ __('save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
