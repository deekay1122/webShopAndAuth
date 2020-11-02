<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex" />
    <link rel="stylesheet" href="{{ asset('css/myStyle.css') }}">
    <title>@yield('title')</title>
</head>
<body>
    @include('includes.navigation')
    <div class="body">
        @if (session('flash_message'))
            <div class="flash_message">
                {{ session('flash_message') }}
            </div>
        @endif
        @yield('content')
    </div>
</body>
</html>