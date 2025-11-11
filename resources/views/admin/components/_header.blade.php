<header>
    <nav class="navbar navbar-expand navbar-light navbar-top bg-white">
        <div class="container-fluid">
            <a href="javascript:void(0);" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="dropdown ms-auto">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex">
                            <div class="user-name text-end me-3">
                                <h6 class="mb-0 text-gray-600">{{ auth()->user()->username }}</h6>
                                <p class="mb-0 text-sm text-gray-600">{{ auth()->user()->display_name }}</p>
                            </div>
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img class="rounded-circle"
                                        src="{{ auth()->user()->avatar_url ? asset('storage/' . auth()->user()->avatar_url) : asset('assets/images/avatars/default_avatar.png') }}"
                                        alt="avatar" style="width: 50px; height: 50px;">
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                        style="min-width: 11rem;">
                        <li>
                            <h6 class="dropdown-header">
                                {{ __('greeting', ['name' => auth()->user()->display_name ?? '']) }}
                            </h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="icon-mid bi bi-person me-2"></i>{{ __('profile') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="icon-mid bi bi-gear me-2"></i>{{ __('settings') }}
                            </a>
                        </li>
                        <hr class="dropdown-divider">
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <li>
                                <button type="submit" class="dropdown-item">
                                    <i class="icon-mid bi bi-box-arrow-left me-2"></i>{{ __('logout') }}
                                </button>
                            </li>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
