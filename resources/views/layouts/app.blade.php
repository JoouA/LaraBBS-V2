<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-COMPATIBLE" content="IE-edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    {{--csrf token--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title','LaraBBS'){{ setting('site_name', 'Laravel 进阶教程') }}</title>

    <meta name="description" content="@yield('description', setting('seo_description', 'LaraBBS 爱好者社区。'))"/>
    <meta name="keyword" content="@yield('keyword', setting('seo_keyword', 'LaraBBS,社区,论坛,开发者论坛'))" />

    {{--style--}}
    <link rel="stylesheet" href="{{ asset('css/app.css')  }}">

    @yield('styles')
</head>
<body>
    <div id="app" class="{{ route_class() }}-page">
        @include('layouts._header')

        <div class="container">
            @include('layouts._message')
            @yield('content')
        </div>
        @include('layouts._footer')
    </div>

    @if (config('app.debug'))
        @include('sudosu::user-selector')
    @endif
    <!-- script -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/js/nprojress.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/js/core.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/larabbs.js') }}"></script>
    <script>
        (function(){
            document.onreadystatechange = function(){
                NProgress.start();
                if(document.readyState == "Uninitialized"){
                    NProgress.set(1);
                }
                if(document.readyState == "Interactive"){
                    NProgress.set(0.5);
                }
                if(document.readyState == "complete"){
                    NProgress.done();
                }
            }
        })();
    </script>

    <!-- scrollUp -->
    <script>
        $(function(){
            $.scrollUp();
        });
    </script>
    @yield('scripts')
</body>
</html>
