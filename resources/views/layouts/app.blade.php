<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SA EarniX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Polished site logo styling */
        .site-logo{ background:transparent; border-radius:6px; padding:4px; box-shadow:0 6px 18px rgba(0,0,0,0.12); object-fit:contain; display:inline-block; }
        .site-logo.navbar{ height:32px; }
        .site-logo.auth-large{ height:56px; }
        .site-logo.pay-small{ height:40px; }
    </style>
</head>
<body style="background:#f4f7fb;">
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('user.home') }}">
                <img class="site-logo" src="{{ asset('images/logo.png') }}" alt="SA EarniX">
            </a>
            <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm">Logout</a>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @yield('content')
        </div>
    </main>
</body>
</html>
