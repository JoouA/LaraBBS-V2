<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-COMPATIBLE" content="IE-edge">
    <meta name="viewport" content="width-device-width,initial-scale=1">

    {{--csrf token--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title','LaraBBS')-Laravel 进阶教程</title>

    {{--style--}}
    <link rel="stylesheet" href="{{ asset('css/app.css')  }}">

</head>
<body>
    <div id="app" class="{{ route_class() }}-page">
        @include('layouts._head')

        <div class="container">
            @yield('content')
        </div>
        @include('layouts._footer')
    </div>

    {{--script--}}
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>