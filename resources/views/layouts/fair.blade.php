<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ferix io</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.3.2/dist/css/tabler.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet"
        href="{{ asset('css/ferixstyle.css') }}?v={{ filemtime(public_path('css/ferixstyle.css')) }}" />
    <!-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script> -->
</head>

<body>
    <!-- Loading Spinner Overlay -->
    <div id="pageLoader" style="
    position: fixed;
    z-index: 9999;
    background: rgba(255,255,255,0.85);
    top: 0; left: 0; width: 100vw; height: 100vh;
    display: flex; align-items: center; justify-content: center;
">
        <div class="spinner-grow text-primary" style="width: 4rem; height: 4rem;" role="status"></div>
    </div>
    <!-- <div class="page">
        <div class="page-wrapper">
            <div class="page-body m-0">
                <div class="container-fluid"> -->
    @yield('content')
    <!-- </div>
            </div>
        </div>
    </div> -->


    <script>
    window.addEventListener('load', function() {
        var loader = document.getElementById('pageLoader');
        if (loader) loader.style.display = 'none';
    });

    // Show loader on any form submit, but only if valid
    document.addEventListener('DOMContentLoaded', function() {
        var loader = document.getElementById('pageLoader');
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (form.checkValidity()) {
                    if (loader) loader.style.display = 'flex';
                } else {
                    if (loader) loader.style.display = 'none';
                }
            });
        });
    });
    </script>

    <!-- <script src="{{ asset('js/ferixstyle.js') }}"></script> -->
    <script src="{{ asset('js/ferixstyle.js') }}?v={{ filemtime(public_path('js/ferixstyle.js')) }}"></script>
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"> -->
    </script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384..."
        crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.3.2/dist/js/tabler.min.js"></script>
</body>

</html>