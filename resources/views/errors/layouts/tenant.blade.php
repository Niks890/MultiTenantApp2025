<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ tenant('logo') ? ('../storage/' . tenant('logo')) : '../assets/images/logos/logo.png' }}" type="image/x-icon">
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/error.css">
    <link rel="stylesheet" href="./assets/custom/css/style.css">
</head>

<body>
    <script src="./assets/static/js/initTheme.js"></script>
    <div id="error">
        <div class="error-page container">
            <div class="col-md-8 col-12 offset-md-2">
                <div class="text-center">
                    <img class="img-error" src="./assets/compiled/svg/error-500.svg" alt="Not Found">
                    <h1 class="error-title">LỖI @yield('code')</h1>
                    <p class='fs-5 text-gray-600'>@yield('description')</p>
                    <a href="javascript:void(0);" class="btn btn-lg btn-outline-primary mt-3">Quay về trang cửa hiệu</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
