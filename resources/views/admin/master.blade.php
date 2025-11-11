<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="/">
    <title>@yield('title')::{{ config('app.name') }}</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logos/logo.png') }}" type="image/x-icon">

    {{-- Font aswesome --}}
    <link rel="stylesheet" href="{{ asset('assets/extensions/fontawesome-free-7.1.0-web/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('assets/custom/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/toast-notify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/loading-overlay.css') }}">

    @yield('css')
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="app">
        <div id="sidebar">
            @include('admin.components._sidebar')
        </div>
        <div id="main" class='layout-navbar navbar-fixed'>

            @include('admin.components._header')

            <div id="main-content">
                @yield('content')
            </div>
            @include('admin.components._toast')
        </div>
        @include('admin.components._footer')
    </div>

    @include('admin.components._loading-overlay')

    {{-- Scripts --}}
    @vite(['resources/js/app.js'])

    <script src="{{ asset('assets/extensions/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/custom/js/toast.js') }}"></script>
    <script src="{{ asset('assets/custom/js/toast-custom.js') }}"></script>
    <script src="{{ asset('assets/custom/js/helpers/loading-overlay.js') }}"></script>

    {{-- xu ly logout, ko luu tru bfcache (back/forward cache) --}}
    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        window.translationsVi = @json(json_decode(File::get(lang_path('vi.json')), true));

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajaxSetup({
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },

            xhrFields: {
                withCredentials: true
            },
        });
    </script>


    {{-- custom JS --}}
    @yield('js')

</body>

</html>
