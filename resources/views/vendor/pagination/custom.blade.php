<nav aria-label="Page navigation"
    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">

    <!-- Phần chọn số mẫu tin trên mỗi trang -->
    <div class="d-flex align-items-center gap-2">
        <label for="perPage" class="text-muted small mb-0" style="white-space: nowrap;">{{ __('display') }}</label>
        <select id="perPage" class="form-select form-select-sm" style="width: auto;">
            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 {{ __('items') }}</option>
            <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 {{ __('items') }}</option>
            <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100 {{ __('items') }}
            </option>
            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>{{ __('all') }}</option>
        </select>
    </div>

    <!-- Phần hiển thị thông tin kết quả -->
    <div class="text-end flex-grow-1">
        <p class="small text-muted mb-0">
            @if ($paginator->firstItem())
                {{ __('showing') }}
                <span class="fw-semibold text-muted">{{ $paginator->firstItem() }}</span>
                {{ __('to') }}
                <span class="fw-semibold text-muted">{{ $paginator->lastItem() }}</span>
                {{ __('of') }}
                <span class="fw-semibold text-muted">{{ $paginator->total() }}</span>
                {{ __('items') }}
            @else
                {{ __('display') }}
                {{ $paginator->count() }}
                {{ __('of') }}
                <span class="fw-semibold text-muted">{{ $paginator->total() }}</span>
                {{ __('items') }}
            @endif
        </p>
    </div>

    @if ($paginator->hasPages())
        <!-- Phần phân trang -->
        <ul class="pagination mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" tabindex="-1" aria-disabled="true">
                        &laquo;
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        &laquo;
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $start = max($currentPage - 2, 1);
                $end = min($currentPage + 2, $lastPage);
            @endphp

            {{-- First Page --}}
            @if ($start > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>
                @if ($start > 2)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
            @endif

            {{-- Page Numbers --}}
            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $currentPage)
                    <li class="page-item active">
                        <span class="page-link">{{ $i }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endif
            @endfor

            {{-- Last Page --}}
            @if ($end < $lastPage)
                @if ($end < $lastPage - 1)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        &raquo;
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link" aria-disabled="true">
                        &raquo;
                    </span>
                </li>
            @endif
        </ul>
    @endif
</nav>
