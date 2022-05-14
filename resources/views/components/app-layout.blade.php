<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') @hasSection('title') | @endif PAASRS</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}" >
</head>
<body class="{{ $activeLink }}">
    <div class="container">
        <x-navbar :activeLink="$activeLink"/>
        <main class="my-4">
            {{ $slot }}
        </main>
    </div>
</body>

<script src="{{ asset('/js/app.js') }}"></script>
</html>