<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', '')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<aside class="menu">
    <ul class="menu-list">
        <li>
            <a href="{{ route('apikeys.create') }}">Api Key</a>
            <a href="{{ route('subscribers.index') }}">All subscribers</a>
            <a href="{{ route('subscribers.create') }}">Create subscriber</a>
        </li>
    </ul>
</aside>
<section class="section">
    <div class="container">
        @yield('content', '')
    </div>
</section>

@yield('scripts', '')
</body>
</html>
