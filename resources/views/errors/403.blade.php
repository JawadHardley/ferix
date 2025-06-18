<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ferix io</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.3.2/dist/css/tabler.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/ferixstyle.css') }}" />
    <!-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script> -->
</head>

<body class="bg-dark">

    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
        <div class="card bg-dark p-4 text-center text-secondary" style="max-width: 400px;">
            <h1 class="display-4 mb-2">403</h1>
            <h2 class="mb-3">Unautorized</h2>
            <p class="mb-4">Sorry, you don't have permission to access this page.</p>
            <button class="btn btn-primary" onclick="window.history.back();">
                <i class="fa fa-arrow-left me-2"></i>Go Back
            </button>
        </div>
    </div>

    <!-- <script src="{{ asset('js/ferixstyle.js') }}"></script> -->
    <script src="{{ asset('js/ferixstyle.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.3.2/dist/js/tabler.min.js"></script>
</body>

</html>
