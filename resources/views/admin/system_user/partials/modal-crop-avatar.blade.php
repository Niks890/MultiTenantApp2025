<div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">Cắt ảnh đại diện</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body text-center">
                <div id="cropperArea"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-custom lh-lg" data-bs-dismiss="modal">
                    <i class="fas fa-window-close me-2"></i>
                    {{ __('cancel') }}
                </button>

                <button type="button" id="cropImageBtn" class="btn btn-primary lh-lg">
                    <i class="fas fa-save"></i>
                    Cắt & Lưu
                </button>
            </div>
        </div>
    </div>
</div>
