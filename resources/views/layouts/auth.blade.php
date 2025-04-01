<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('admin_assets/img/llcc.png') }}" type="image/png">
</head>
<body class="bg-light">
    <div class="container mt-5">
        @yield('content')
    </div>
</body>
</html>
