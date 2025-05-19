<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ferix</title>

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.2.0/dist/css/tabler.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet"
        href="{{ asset('css/ferixstyle.css') }}?v={{ filemtime(public_path('js/ferixstyle.js')) }}" />
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>

<body>
    <div class="page">
        <!-- Sidebar -->
        <aside class="navbar navbar-vertical navbar-expand-sm" data-bs-theme="dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark">
                    <a href="/" class="text-decoration-none">
                        <i class="fa fa-earth-asia"></i> <b>Ferix</b>
                    </a>
                </h1>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav pt-lg-3">
                        <li class="nav-item">
                            <a class="nav-link" href="./dashboard">
                                <i class="fa fa-house pe-3"></i>
                                <span class="nav-link-title"> Home </span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                data-bs-auto-close="false" role="button" aria-expanded="false">
                                <i class="fa fa-user pe-3"></i>
                                <span class="nav-link-title"> Feri Application </span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('transporter.applyferi') }}">
                                    Apply
                                </a>
                                <a class="dropdown-item" href="{{ route('transporter.showApps') }}">
                                    History
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
        <!-- <aside class="navbar navbar-vertical navbar-expand-sm" data-bs-theme="dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark p-5 fs-1">
                    <a href="/">
                        <i class="fa fa-train-subway me-1"></i> Ferix
                    </a>
                </h1>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav pt-lg-3">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="fa fa-house"></i>
                                </span>
                                <span class="nav-link-title"> Home </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside> -->

        <div class="row" id="navbarNav">
            <header class="navbar navbar-expand-sm navbar-light d-print-none p-2">
                <div class="container-fluid">
                    <div class="col">
                        <!-- // -->
                    </div>
                    <div class="pe-0 pe-md-3">
                        <a href="#" class="badge bg-azure-lt ms-2 px-3 py-2 fs-3 text-decoration-none" id="themeToggle">
                            <i class="fa fa-moon" id="themeIcon"></i>
                        </a>
                    </div>
                    <div class="navbar-nav flex-row order-md-last dropdown">
                        <div class="nav-item">
                            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                                aria-label="Open user menu" aria-expanded="false">
                                <span class="avatar avatar-sm text-primary">
                                    <i class="fa fa-user"></i>
                                </span>
                                <div class="d-none d-xl-block ps-2">
                                    <div>{{ ucwords(Auth::user()->name) }}</div>
                                    <div class="mt-1 small text-secondary">{{ Auth::user()->role }}</div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end flex-row order-md-last">
                                <a href="{{ route('transporter.showProfile') }}" class="dropdown-item">Profile</a>
                                <a href="#" class="dropdown-item">Feedback</a>
                                <div class="dropdown-divider"></div>
                                <a href="./settings.html" class="dropdown-item">Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <a href="route('logout')" class="dropdown-item" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                        Logout
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        </div>
        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="{{ asset('js/ferixstyle.js') }}"></script> -->
    <script src="{{ asset('js/ferixstyle.js') }}?v={{ filemtime(public_path('js/ferixstyle.js')) }}"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js">
    </script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384..."
        crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.1.1/dist/js/tabler.min.js"></script>
</body>

</html>