<!-- resources/views/layouts/auth.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <title>Auth</title>
</head>
<body>
    <div class="container">
        <div class="image-section">
            <img src="{{ asset('assets/images/CAISIMG.png') }}" alt="CAIS">
            <!-- <h1>CAIS</h1>
            <p>Career Alumni Information System</p> -->
        </div>
        <div class="form-section">
            <div class="tab-switch">
                <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Login</a>
                <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}">Registrasi</a>
            </div>
            <div class="form-wrapper">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
