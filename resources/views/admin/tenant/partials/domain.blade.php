<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{tenant('name')}} :: {{ config('app.name') }}</title>
    <link rel="stylesheet" href="../assets/compiled/css/app.css">
    <link rel="stylesheet" href="../assets/custom/css/tenant/domain.css">
    <link rel="shortcut icon" href="{{ tenant('logo') ? ('../storage/' . tenant('logo')) : '../assets/images/logos/logo.png' }}" type="image/x-icon">
</head>
<body>
    <div class="container dashboard-container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="welcome-card">
                    <div class="card-body-custom">
                        <div class="info-box">
                            <h3>
                                <span>Chào mừng bạn đến với trang cửa hiệu!</span>
                            </h3>
                            <span class="mb-2 fs-5">Đây là cửa hiệu:</span>
                            <h2><strong>{{ tenant('name') }}</strong></h2>
                        </div>

                        <div class="info-box">
                            <h3>
                                <span>Thông tin domain</span>
                            </h3>
                            <span class="mb-2 fs-5">Tên miền hiện tại:</span>
                            <h2><strong>{{ request()->getHost() }}</strong></h2>
                        </div>

                        <div class="info-box">
                            <h3>
                                <span>Danh sách người dùng của cửa hiệu:</span>
                            </h3>
                            <ul class="user-list">
                                @foreach ($users as $user)
                                    <li>
                                        <strong>{{ $user->name }}</strong>
                                        <span class="text-muted">({{ $user->email }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./assets/compiled/js/app.js"></script>
</body>
</html>
