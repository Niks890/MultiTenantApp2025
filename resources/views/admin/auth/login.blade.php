<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logos/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/toast-notify.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="login-container">
        <h3 class="text-center mb-3 text-primary">{{ __('login') }}</h3>
        <form method="POST" action="{{ route('admin.post_login') }}" id="loginForm">
            @csrf
            @error('login_error')
                <small class="text-danger mb-2">{{ $message }}</small>
            @enderror
            <div class="mb-4">
                <label for="usernameInput" class="form-label">{{ __('account_name') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input name="username" type="text" class="form-control" id="usernameInput"
                        placeholder="{{ __('enter', ['item' => strtolower(__('account_name'))]) }}"
                        value="{{ old('username') }}" autocomplete="off">
                </div>
                <small class="text-danger" id="loginError"></small>
            </div>
            <div class="mb-4">
                <label for="passwordInput" class="form-label">{{ __('password') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input name="password" type="password" class="form-control" id="passwordInput"
                        placeholder="{{ __('enter', ['item' => strtolower(__('password'))]) }}" autocomplete="off">
                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
                <small class="text-danger" id="passwordError"></small>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                    <label class="form-check-label" for="remember">
                        {{ __('rememberMe') }}
                    </label>
                </div>
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                    <span class="loading-spinner" id="loadingSpinner"></span>
                    <span id="btnText">{{ __('login') }}</span>
                </button>
            </div>
        </form>
    </div>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/custom/js/login.js') }}"></script>
    @include('admin.components._toast')
    <script src="{{ asset('assets/custom/js/toast.js') }}"></script>
</body>

</html>
