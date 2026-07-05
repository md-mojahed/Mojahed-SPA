<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>

    @spacss
    @stack('styles')
</head>

<body @spadata>

    @include('partials.sidebar')

    <div class="main-content">
        @include('partials.topbar')

        <div class="page-content">
            @yield('content')
        </div>
    </div>

    @spajs
    @stack('scripts')

</body>
</html>
