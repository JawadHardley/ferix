<!DOCTYPE html>

@php
    $profile_pic = asset('images/profile.jpg');
    $front = asset('images/img-1.jpg');
@endphp
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ferix io</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/ferixstyle.css') }}" />

    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.3.2/dist/css/tabler.min.css" />
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}s" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}s" rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container position-relative d-flex align-items-center justify-content-between">

            <a href="/" class="logo d-flex align-items-center me-auto me-xl-0">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <h1 class="sitename">
                    <i class="fa fa-earth-asia"></i>
                    <b>Ferix io.</b>
                </h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#contact">Contact</a></li>

                    @if (Route::has('transporter.login'))
                        @auth
                            <li class="dropdown text-start">
                                <a href="#">
                                    <span class="text-teal px-2">
                                        <i class="fa fa-user fs-4"></i>
                                    </span>
                                    <span>{{ Auth::user()->name }}</span>
                                    <i class="bi bi-chevron-down toggle-dropdown"></i>
                                </a>
                                <ul>
                                    <li><a href="{{ route(Auth::user()->role . '.dashboard') }}">Dashboard</a></li>
                                    <li><a href="{{ route(Auth::user()->role . '.showProfile') }}">Profile</a></li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <li><a href="route('logout')"
                                                onclick="event.preventDefault();
                                        this.closest('form').submit();">Log
                                                Out</a></li>
                                    </form>
                                    <!-- <li><a href="#">Deep Dropdown 4</a></li> -->
                                </ul>
                            </li>
                        @else
                            <li class="dropdown"><a href="#"><span>Login/Register</span> <i
                                        class="bi bi-chevron-down toggle-dropdown"></i></a>
                                <ul>
                                    <li><a href="{{ route('transporter.login') }}">Login</a></li>
                                    <li><a href="{{ route('transporter.register') }}">Register</a></li>
                                </ul>
                            </li>
                        @endauth
                    @endif
                </ul>
                <i class="mobile-nav-toggle d-xl-none fa fa-bars"></i>


            </nav>


        </div>
    </header>


    <!-- Hero Section -->
    <section id="hero" class="hero section">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="badge-wrapper mb-3">
                        <div class="d-inline-flex align-items-center rounded-pill border border-accent-light">
                            <div class="icon-circle me-2">
                                <i class="fa fa-bell"></i>
                            </div>
                            <span class="badge-text me-3">Digital Trade Facilitation</span>
                        </div>
                    </div>

                    <h1 class="hero-title mb-4">Accelerating Cross-Border Trade with FERI Solutions </h1>

                    <p class="hero-description mb-4">Ferix streamlines the application, management, and tracking of FERI
                        certificates for transporters, vendors, and logistics companies moving goods into and within the
                        DRC. Our platform ensures compliance, transparency, and efficiency for all your freight
                        documentation needs.</p>

                    <div class="cta-wrapper">
                        <a href="{{ route('transporter.login') }}" class="btn btn-primary">Get Started</a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-image">
                        <img src="{{ asset('images/program.svg') }}" alt="Business Growth" class="img-fluid"
                            loading="lazy">
                    </div>
                </div>
            </div>

            <div class="row feature-boxes">
                <div class="col-lg-4 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-box">
                        <div class="feature-icon me-sm-4 mb-3 mb-sm-0">
                            <i class="fa fa-gear"></i>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">Easy Process</h3>
                            <p class="feature-text">Set up your FERI applications and documentation in minutes. Our
                                intuitive platform lets you upload, manage, and track all required documents with ease.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-box">
                        <div class="feature-icon me-sm-4 mb-3 mb-sm-0">
                            <i class="fa fa-shield-halved"></i>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">Advanced Security</h3>
                            <p class="feature-text">Your data and documents are protected with industry-standard
                                encryption and secure cloud storage, ensuring confidentiality and integrity at every
                                step.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-box">
                        <div class="feature-icon me-sm-4 mb-3 mb-sm-0">
                            <i class="fa fa-headset"></i>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">Dedicated Support</h3>
                            <p class="feature-text">Our support team is available to assist you with any queries or
                                issues, ensuring your freight documentation process is smooth and hassle-free.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section><!-- /Hero Section -->



    <!-- About Section -->
    <section id="about" class="about section">

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
                    <p class="who-we-are">Who We Are</p>
                    <h3>Empowering Trade with Technology </h3>
                    <p class="fst-italic">
                        Ferix io is a digital platform dedicated to simplifying and securing the FERI Certificate
                    </p>
                    <ul>
                        <li><i class="fa fa-check-circle"></i> <span>Automated FERI application and approval
                                workflow.</span></li>
                        <li><i class="fa fa-check-circle"></i> <span>Real-time tracking and status updates for all your
                                applications.</span></li>
                        <li><i class="fa fa-check-circle"></i> <span>Integrated document management for invoices and
                                certificates.</span></li>
                    </ul>
                    <a href="#" class="read-more"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
                </div>

                <div class="col-lg-6 about-images" data-aos="fade-up" data-aos-delay="200">
                    <div class="row gy-4">
                        <div class="col-lg-6">
                            <img src="{{ asset('images/feri1.jpg') }}" class="img-fluid" alt="">
                        </div>
                        <div class="col-lg-6">
                            <div class="row gy-4">
                                <div class="col-lg-12">
                                    <img src="{{ asset('images/feri2.jpg') }}" class="img-fluid" alt="">
                                </div>
                                <div class="col-lg-12">
                                    <img src="{{ asset('images/feri3.jpg') }}" class="img-fluid" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section><!-- /About Section -->


    <!-- Services Section -->
    <section id="services" class="services section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Services</h2>
            <p>Ferix io offers a comprehensive suite of tools for managing FERI certificates and related logistics
                documentation for all modes of transport.</p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row justify-content-center g-5">

                <div class="col-md-6" data-aos="fade-right" data-aos-delay="100">
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fa fa-truck-front fs-2"></i>
                        </div>
                        <div class="service-content">
                            <h3>Road Freight</h3>
                            <p>Apply for and manage FERI certificates for road shipments. Upload required documents,
                                track application status, and receive notifications on approvals.</p>
                            <a href="{{ route('transporter.applyferi') }}" class="service-link">
                                <span>Apply Now</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-md-6" data-aos="fade-left" data-aos-delay="100">
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fa fa-ferry fs-2"></i>
                        </div>
                        <div class="service-content">
                            <h3>Maritime Freight</h3>
                            <p>Coming soon: Full support for maritime FERI applications, including vessel documentation
                                and port clearance integration.</p>
                            <a href="#" class="service-link">
                                <span>Coming Soon ...</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-md-6" data-aos="fade-right" data-aos-delay="200">
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fa fa-train-tram fs-2"></i>
                        </div>
                        <div class="service-content">
                            <h3>Rail Freight</h3>
                            <p>Coming soon: Streamlined FERI processing for rail cargo, with integration to major rail
                                operators in the region.</p>
                            <a href="#" class="service-link">
                                <span>Coming Soon ...</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-md-6" data-aos="fade-left" data-aos-delay="200">
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fa fa-plane fs-2"></i>
                        </div>
                        <div class="service-content">
                            <h3>Air Freight</h3>
                            <p>Coming soon: Digital FERI solutions for air cargo, ensuring compliance and fast
                                turnaround for urgent shipments.</p>
                            <a href="#" class="service-link">
                                <span>Coming Soon ...</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div><!-- End Service Item -->

            </div>

        </div>

    </section><!-- /Services Section -->


    <!-- Contact Section -->
    <section id="contact" class="contact section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Contact</h2>
            <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4 mb-5">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="info-card">
                        <div class="icon-box">
                            <i class="fa fa-location-dot"></i>
                        </div>
                        <h3>Our Address</h3>
                        <p>P.O. Box 75391 Avocado street, Dar es Salaam</p>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="info-card">
                        <div class="icon-box">
                            <i class="fa fa-phone"></i>
                        </div>
                        <h3>Contact Number</h3>
                        <p>Mobile: +255 753 123 283<br>
                            Email: diane.presisfinance@gmail.com</p>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="info-card">
                        <div class="icon-box">
                            <i class="fa fa-clock"></i>
                        </div>
                        <h3>Opening Hour</h3>
                        <p>Monday - Tuesda: 8:00 - 16:30 EAT<br>
                            Sunday: Closed</p>
                    </div>
                </div>
            </div>

        </div>
    </section><!-- /Contact Section -->

    <footer id="footer" class="footer light-background">

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">Ferix io</strong> {{ today()->format('Y') }}
                <span>All Rights
                    Reserved</span>
            </p>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you've purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
                <a href="{{ route('vendor.login') }}" class="text-decoration-none bg-transparent border-0 p-0 m-0"
                    style="color: inherit; cursor: default; outline: none;">
                    Designed
                </a> by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>

    </footer>


    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="fa fa-arrow-up fs-5"></i></a>

    <!-- Preloader -->
    <!-- <div id="preloader"></div> -->

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>


    <script src="{{ asset('js/ferixstyle.js') }}"></script>
</body>

</html>
