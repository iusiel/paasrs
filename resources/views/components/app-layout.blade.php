<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="base_url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') @hasSection('title') | @endif PAASRS</title>
    @yield('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}" >
</head>
<body class="{{ $activeLink }} pb-5">
    <div class="container">
        <x-navbar :activeLink="$activeLink"/>
        <main class="my-4">
            {{ $slot }}
        </main>
    </div>
</body>

@yield('scripts')
<script src="{{ asset('/js/app.js') }}"></script>
</html>