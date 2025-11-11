@props(['icon', 'title', 'value', 'color'])

{{-- 
    $icon: Tên icon (vd: iconly-boldHome)
    $title: Tên thống kê (vd: Cửa hiệu)
    $value: Giá trị thống kê (vd: 125.400)
    $color: Màu nền của icon (vd: purple, orange)
--}}

<div class="col-6 col-lg-3 col-md-6">
    <div {{ $attributes->merge(['class' => 'card']) }}>
        <div class="card-body px-4 py-4-5">
            <div class="row">
                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                    <div class="stats-icon {{ $color }} mb-2">
                        <i class="{{ $icon }}"></i>
                    </div>
                </div>
                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                    <h6 class="text-muted">{{ $title }}</h6>
                    <h6 class="font-extrabold mb-0">{{ $value }}</h6>
                </div>
            </div>
        </div>
    </div>
</div>
