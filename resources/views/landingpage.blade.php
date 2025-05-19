<!DOCTYPE html>

@php
$profile_pic = asset('images/profile.jpg');
$front = asset('images/img-1.jpg');
@endphp
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ferix</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.2.0/dist/css/tabler.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/ferixstyle.css') }}" />
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>

<body>
    <!-- here is the real nav bar -->
    <nav class="navbar navbar-expand-lg navbar-light" aria-label="Fifth navbar example">
        <div class="container">
            <a href="/" class="navbar-brand navbar-brand-autodark me-3">
                <i class="fa fa-earth-asia text-dark"></i>
                <b>Ferix</b>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample05"
                aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExample05">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                </ul>
                @if (Route::has('transporter.login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                    <div class="navbar-nav flex-row order-md-last ms-auto">
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link d-flex lh-1 text-reset" data-bs-toggle="dropdown"
                                aria-label="Open user menu">
                                <span class="avatar avatar-sm" style="background-image: url('{{ $profile_pic }}');">
                                </span>
                                <div class="d-none d-xl-block ps-2">
                                    <div>{{ Auth::user()->name}}</div>
                                    <div class="mt-1 small text-secondary">
                                        {{ Auth::user()->role}}
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <a href="{{ route(Auth::user()->role . '.dashboard') }}"
                                    class="dropdown-item">Dashboard</a>
                                <a href="./profile.html" class="dropdown-item">Profile</a>
                                <a href="#" class="dropdown-item">Feedback</a>
                                <div class="dropdown-divider"></div>
                                <a href="./settings.html" class="dropdown-item">Settings</a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <a href="route('logout')" class="dropdown-item" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                        Log Out
                                    </a>
                                </form>
                            </div>
                        </div>
                        <a href="#" class="badge bg-azure-lt ms-2 px-3 py-2 fs-3 text-decoration-none" id="themeToggle">
                            <i class="fa fa-moon" id="themeIcon"></i>
                        </a>
                    </div>

                    @else
                    <div class="mx-2">
                        <a href="{{ route('transporter.login') }}" class="btn btn-outline-primary">
                            login
                        </a>

                        <a href="{{ route('transporter.register') }}" class="btn btn-primary">
                            Register
                        </a>
                        <a href="#" class="badge bg-azure-lt ms-2 px-3 py-2 fs-3 text-decoration-none" id="themeToggle">
                            <i class="fa fa-moon" id="themeIcon"></i>
                        </a>
                        @endauth
                    </div>
                </nav>
                @endif
            </div>
        </div>
    </nav>
    <main>
        <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-light">Feri Application</h1>
                    <p class="lead text-body-secondary">
                        Welcome to the one point where design meets harmony
                        but also a process finds a cure. Ease the feri
                        application process like never before. The future is
                        brighter than ever
                    </p>
                </div>
            </div>
        </section>

        <div class="album py-5 bg-body-tertiary">
            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <a href="#" class="card card-link">
                            <div class="card shadow">
                                <!-- Photo -->
                                <div class="img-responsive img-responsive-21x9 card-img-top" style="
                                        background-image: url({{ $front }});
                                    ">
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title">
                                        Apply for Ferry
                                    </h3>
                                    <p class="text-secondary">
                                        Life made easy with our online
                                        system in place to assist you
                                        in the whole process
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-body-secondary pt-5">
        <div class="container">
            <p class="mb-1">
                Ferix © {{ today()->format('Y') }}
            </p>
            <p class="mb-0">
                Made by <i class="fa fa-heart px-2"></i> Friday
            </p>
        </div>
    </footer>
    <script src="{{ asset('js/ferixstyle.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js">
    </script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384..."
        crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.1.1/dist/js/tabler.min.js"></script>
</body>

</html>