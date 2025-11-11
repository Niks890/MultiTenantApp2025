@props(['breadcrumbs' => []])

<div class="page-title">
    <div class="row align-items-center">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header mb-4 d-flex justify-content-end">
                <ol class="breadcrumb mb-0">
                    @foreach ($breadcrumbs as $label => $url)
                        @if ($loop->last)
                            <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
                        @else
                            <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>
</div>
