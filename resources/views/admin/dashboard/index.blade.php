@extends('admin.master')
@section('title', 'Dashboard')
@section('content')
    <div class="page-heading">
        <div class="page-title mb-4">
            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <h3 class="mb-2">
                        <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard
                    </h3>
                    <p class="text-muted mb-0">
                        <i class="bi bi-calendar-event me-2"></i>
                        <span id="currentDate"></span>
                        <span class="mx-2">|</span>
                        <i class="bi bi-clock me-2"></i>
                        <span id="currentTime"></span>
                    </p>
                </div>
                <div class="col-12 col-md-4">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb mb-0 bg-light-primary px-3 py-2 rounded-3">
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="bi bi-house-door me-1"></i>{{ __('Tổng quan') }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card stats-card shadow-sm">
                    <div class="card-body px-4 py-4">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="text-muted font-semibold mb-2">Tổng Tenant</h6>
                                <h4 class="font-extrabold mb-0">{{ $totalTenants ?? 0 }}</h4>
                            </div>
                            <div class="col-4 text-end">
                                <div class="stats-icon-modern bg-gradient-primary">
                                    <i class="bi bi-building text-white"></i>
                                </div>
                            </div>
                        </div>
                        <small class="text-success mt-2 d-block">
                            <i class="bi bi-arrow-up"></i> +{{ $newTenantsThisMonth ?? 0 }} tháng này
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card stats-card shadow-sm">
                    <div class="card-body px-4 py-4">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="text-muted font-semibold mb-2">Người dùng</h6>
                                <h4 class="font-extrabold mb-0">{{ $totalUsers ?? 0 }}</h4>
                            </div>
                            <div class="col-4 text-end">
                                <div class="stats-icon-modern bg-gradient-success">
                                    <i class="bi bi-people text-white"></i>
                                </div>
                            </div>
                        </div>
                        <small class="text-success mt-2 d-block">
                            <i class="bi bi-arrow-up"></i> {{ $activeUsers ?? 0 }} đang hoạt động
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card stats-card shadow-sm">
                    <div class="card-body px-4 py-4">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="text-muted font-semibold mb-2">Doanh thu</h6>
                                <h4 class="font-extrabold mb-0">{{ number_format($revenue ?? 0) }}đ</h4>
                            </div>
                            <div class="col-4 text-end">
                                <div class="stats-icon-modern bg-gradient-warning">
                                    <i class="bi bi-currency-dollar text-white"></i>
                                </div>
                            </div>
                        </div>
                        <small class="text-success mt-2 d-block">
                            <i class="bi bi-arrow-up"></i> +{{ $revenueGrowth ?? 0 }}% so với tháng trước
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3 col-md-6">
                <div class="card stats-card shadow-sm">
                    <div class="card-body px-4 py-4">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="text-muted font-semibold mb-2">Lưu trữ</h6>
                                <h4 class="font-extrabold mb-0">{{ $storageUsed ?? 0 }}GB</h4>
                            </div>
                            <div class="col-4 text-end">
                                <div class="stats-icon-modern bg-gradient-danger">
                                    <i class="bi bi-hdd text-white"></i>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="bi bi-info-circle"></i> {{ $storageLimit ?? 100 }}GB giới hạn
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>Biểu đồ doanh thu
                        </h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary active">7 ngày</button>
                            <button type="button" class="btn btn-outline-primary">30 ngày</button>
                            <button type="button" class="btn btn-outline-primary">12 tháng</button>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- <canvas></canvas> --}}
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-pie-chart me-2"></i>Trạng thái Tenant
                        </h5>
                    </div>
                    <div class="card-body">
                        <div style="height: 250px; position: relative;">
                            {{-- <canvas></canvas> --}}
                        </div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">
                                    <i class="bi bi-circle-fill text-success me-2"></i>Hoạt động
                                </span>
                                <strong>{{ $activeTenants ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">
                                    <i class="bi bi-circle-fill text-warning me-2"></i>Bảo trì
                                </span>
                                <strong>{{ $suspendedTenants ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="bi bi-circle-fill text-danger me-2"></i>Đã bị xoá
                                </span>
                                <strong>{{ $expiredTenants ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>Hoạt động gần đây
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @forelse($recentActivities ?? [] as $activity)
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-md me-3">
                                            <span class="avatar-content bg-light-{{ $activity['type'] ?? 'primary' }}">
                                                <i
                                                    class="bi bi-{{ $activity['icon'] ?? 'activity' }} text-{{ $activity['type'] ?? 'primary' }}"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $activity['title'] ?? 'Hoạt động' }}</h6>
                                            <p class="text-muted mb-0 small">{{ $activity['description'] ?? '' }}</p>
                                        </div>
                                        <small class="text-muted">{{ $activity['time'] ?? '' }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item px-0 border-0">
                                    <div class="text-center py-4">
                                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2 mb-0">Chưa có hoạt động nào</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-star me-2"></i>Tenant hàng đầu
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Tenant</th>
                                        <th>Người dùng</th>
                                        <th>Doanh thu</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topTenants ?? [] as $tenant)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <span class="avatar-content bg-light-primary">
                                                            {{ substr($tenant['name'] ?? 'T', 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <span class="fw-bold">{{ $tenant['name'] ?? 'Tenant' }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $tenant['users'] ?? 0 }}</td>
                                            <td>{{ number_format($tenant['revenue'] ?? 0) }}đ</td>
                                            <td>
                                                <span class="badge bg-light-success">
                                                    <i class="bi bi-check-circle me-1"></i>Hoạt động
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                                <p class="text-muted mt-2 mb-0">Chưa có dữ liệu</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-heart-pulse me-2"></i>Sức khỏe hệ thống
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="text-center">
                                    <h6 class="text-muted mb-2">CPU Usage</h6>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $cpuUsage ?? 45 }}%"></div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">{{ $cpuUsage ?? 45 }}%</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="text-center">
                                    <h6 class="text-muted mb-2">Memory</h6>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                            style="width: {{ $memoryUsage ?? 62 }}%"></div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">{{ $memoryUsage ?? 62 }}%</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="text-center">
                                    <h6 class="text-muted mb-2">Disk Space</h6>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: {{ $diskUsage ?? 38 }}%"></div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">{{ $diskUsage ?? 38 }}%</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="text-center">
                                    <h6 class="text-muted mb-2">Database</h6>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $dbHealth ?? 95 }}%"></div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">{{ $dbHealth ?? 95 }}% Health</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/dashboard.css') }}">
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('assets/custom/js/dashboard/dashboard.js') }}"></script>
@endsection
