            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mt-3">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="text-muted">Hiển thị</span>
                    <select name="paginate" id="paginate" class="form-select form-select-sm w-auto">
                        <option value="10" {{ $selectedPaginate == 10 ? 'selected' : '' }}>10 mẫu tin</option>
                        <option value="50" {{ $selectedPaginate == 50 ? 'selected' : '' }}>50 mẫu tin</option>
                        <option value="100" {{ $selectedPaginate == 100 ? 'selected' : '' }}>100 mẫu tin</option>
                        <option class="all-option" value="all" {{ $selectedPaginate == 'all' ? 'selected' : '' }}>Tất
                            cả
                        </option>
                    </select>
                </div>

                <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                    <span class="text-muted">
                        Hiển thị
                        từ <strong>{{ $systemUsers->firstItem() ?? 0 }}</strong>
                        đến <strong>{{ $systemUsers->lastItem() ?? 0 }}</strong>
                        trong tổng số <strong>{{ $systemUsers->total() }}</strong> mẫu tin
                    </span>
                    <div>
                        {{ $systemUsers->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
