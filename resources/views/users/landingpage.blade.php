
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>K - CKET</title>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/cover.css') }}" rel="stylesheet">
</head>
<body class="text-center">
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="masthead mb-auto">
        <div class="inner">
            <h3 class="masthead-brand">K-CKET</h3>
            @if (Route::has('login'))
            <nav class="nav nav-masthead justify-content-center">
                @auth
                <a class="nav-link active" href="/user/concerts">Home</a>
                @else
                <a class="nav-link" href="/login">Login</a>
                    @if (Route::has('register'))
                <a class="nav-link" href="/register">Register</a>
                    @endif
                @endauth
            </nav>
            @endif
        </div>
    </header>

    <main role="main" class="inner cover">
        <h1 class="cover-heading">Get you K-Pop concert ticket!</h1>
        <p class="lead">Beli konser kpop tanpa repot di K-Cket dengan prosedur yang mudah dan terpercaya!</p>
        <p class="lead">
            @auth
                <a href="/user/concerts" class="btn btn-lg btn-secondary">Home</a>
            @else
                <a href="/register" class="btn btn-lg btn-secondary">Sign Up!</a>
            @endauth
        </p>
    </main>

    <footer class="mastfoot mt-auto">
        <div class="inner">
            <p>Cover template for <a href="https://getbootstrap.com/">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a>.</p>
        </div>
    </footer>
</div>
</body>
</html>
